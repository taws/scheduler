<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WSHandler
 *
 * @author Lisette
 */
class WSHandler {

    var $SERVER = "https://www.academico.espol.edu.ec/";
    var $wsdl;
    var $client;

    public function initAcademico() {
        $this->wsdl = "Services/wsSAAC.asmx?WSDL";
        //$this->client = new SoapClient($this->SERVER.$this->wsdl);
        $this->client = new SoapClient(sfConfig::get("sf_web_dir") . "/webservices/wsSAAC.wsdl", array());
    }

    public function initDirectorio() {
        $this->wsdl = "services/serviceDirectorio.asmx?WSDL";
    }

    /**
     * Carga o actualiza la planificación del periodo dado
     * El superuser es el usuario administrador
     * @param <type> $superuser
     * @param <type> $periodo
     * @return <type>
     */
    public function cargarPlanificacion($superuser, $periodo) {
        //flush();
//        set_time_limit(0);
//        ignore_user_abort(1);

        try{
            $results = (array) ($this->client->InformacionPlanficacion(array("anio" => $periodo->getAnio(), "termino" => $periodo->getTermino())));
            $results = (array) ($results['InformacionPlanficacionResult']);            
            $results = $this->cleanWSPlanificacion($results['any']);

            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->loadXML($results);
            $elements = $doc->getElementsByTagName("MATERIA_PARALELO");

            echo "\nSe actualizaran ".$elements->length." cursos.\n";
            sfContext::getInstance()->getLogger()->info("{Planificacion} Se actualizaran ".$elements->length." cursos.");
            // RECORREMOS LOS CURSOS
//            ob_start();
            for ($i = 0; $i < $elements->length; $i++) {

                try{
                    $node = $elements->item($i);

                    $codigo_materia = $node->getElementsByTagName("CODIGOMATERIA")->length!=0 ? $node->getElementsByTagName("CODIGOMATERIA")->item(0)->nodeValue : "";
                    $nombre_materia = $node->getElementsByTagName("NOMBREMATERIA")->length!=0 ? $node->getElementsByTagName("NOMBREMATERIA")->item(0)->nodeValue: "";
                    $paralelo = $node->getElementsByTagName("PARALELO")->length!=0 ? $node->getElementsByTagName("PARALELO")->item(0)->nodeValue: "";
                    $examen_parcial = $node->getElementsByTagName("EXAMENPARCIAL")->length!=0 ? $node->getElementsByTagName("EXAMENPARCIAL")->item(0)->nodeValue: "";
                    $examen_final = $node->getElementsByTagName("EXAMENFINAL")->length!=0 ? $node->getElementsByTagName("EXAMENFINAL")->item(0)->nodeValue: "";
                    $examen_mejoramiento = $node->getElementsByTagName("EXAMENMEJORAMIENTO")->length!=0 ? $node->getElementsByTagName("EXAMENMEJORAMIENTO")->item(0)->nodeValue: "";
                    $num_creditos = $node->getElementsByTagName("NUMCREDITOS")->length!=0 ? $node->getElementsByTagName("NUMCREDITOS")->item(0)->nodeValue: "";
                    $cod_unidad = $node->getElementsByTagName("CODUNIDAD")->length!=0 ? $node->getElementsByTagName("CODUNIDAD")->item(0)->nodeValue: "";

                    //departamento
                    $departamento = new Departamento();
                    $departamento->setSiglas(trim($cod_unidad));
                    $departamento->setEliminado(false);

                    //materia
                    $materia = new Materia();
                    $materia->setCodigo(trim($codigo_materia));
                    $materia->setNombre(($nombre_materia));
                    $materia->setDepartamento($departamento);
                    $materia->setCreditos(($num_creditos));
                    $materia->setEliminado(false);

                    //curso (paralelo)
                    $curso = new Curso();
                    $curso->setEliminado(false);
                    $curso->setPeriodo($periodo);
                    $curso->setParalelo(trim($paralelo));
                    $curso->setMateria($materia);
                    
                    if($examen_parcial) $curso->setExamenParcial($examen_parcial);
                    if($examen_final) $curso->setExamenFinal($examen_final);
                    if($examen_mejoramiento) $curso->setExamenMejoramiento($examen_mejoramiento);
                    
                    $curso->save();
                    $curso->free(true);

                    echo "$i) ".$codigo_materia . "(" .trim($paralelo) . ") actualizado! \n";
//                    $this->flush_buffers();
                    
                }catch(Exception $ee){
                    echo "$i) ".$codigo_materia . "(" .trim($paralelo) . ") error! [".$ee->getMessage()."] \n";
                    sfContext::getInstance()->getLogger()->info("{Planificacion} cargarPlanificacion error $codigo_materia (".trim($paralelo).")");
                }           

            }

        }  catch (Exception $e){
            sfContext::getInstance()->getLogger()->info("{Planificacion} cargarPlanificacion error general");
            echo $e->getMessage();
            return false;
        }

//        ob_flush();
        sfContext::getInstance()->getLogger()->info("{Planificacion}  ".$periodo->getPeriodoSlug()." exitosa");
        return true;
    }

    /**
     *
     * @param <type> $superuser
     * @param <type> $periodo
     * @return <type> 
     */
    public function cargarUsuarios($superuser, $periodo) {
        // 1. CARGAMOS LOS USUARIOS DEL PERIODO (estudiantes, profesores)
        //flush();
//        set_time_limit(0);
//        ignore_user_abort(1);               

        try{
            $results = (array) ($this->client->InfoProfesoresEstudiantesIds(array("anio" => $periodo->getAnio(), "termino" => $periodo->getTermino())));
            $results = (array) ($results['InfoProfesoresEstudiantesIdsResult']);
            $results = $this->cleanWSUsuarios($results['any']);

//            $results = (array) ($this->client->InformacionPlanficacion(array("anio" => $periodo->getAnio(), "termino" => $periodo->getTermino())));
//            $results = (array) ($results['InformacionPlanficacionResult']);
//            $results = $this->cleanWSPlanificacion($results['any']);
            
            $doc = new DOMDocument('1.0', 'utf-8');           
            $doc->loadXML($results);

            $elements_profesor = $doc->getElementsByTagName("IDENTIFICACION");
            $elements_estudiante = $doc->getElementsByTagName("MATRICULA");

            $this->actualizarUsuarios($elements_profesor,true);
            $this->actualizarUsuarios($elements_estudiante,false);
            
        }  catch (Exception $e){
            sfContext::getInstance()->getLogger()->info("{Planificacion-Usuarios} cargarUsuarios error general: ".$e->getMessage());
            echo $e->getMessage();
            return false;
        }

        sfContext::getInstance()->getLogger()->info("{Planificacion-Usuarios}  ".$periodo->getPeriodoSlug()." exitosa");
        return true;
    }

    /**
     *
     * @param <type> $elements 
     */
    private function actualizarUsuarios($elements,$esprofesor){

        echo "\nSe actualizaran ".$elements->length." usuarios.\n";
        sfContext::getInstance()->getLogger()->info("{Planificacion-Usuario} Se actualizaran ".$elements->length." usuarios.");
        // RECORREMOS LOS USUARIOS
//        ob_start();
        //for ($i = ($elements->length-1); $i >= 0; $i--) {
        $contador=0;
        for ($i=0; $i < $elements->length; $i++) {

            try{
                $node = $elements->item($i);

                $codigo = isset($node->nodeValue) ? trim($node->nodeValue) : "";

                // POR CADA USUARIO LEIDO, SE LO BUSCA POR LDAP Y SE ACTUALIZAN DATOS
                $ldap = new LDAPHandler();
                
                $usuario = new Usuario();
                if($esprofesor) $usuario = $ldap->buscarUsuario($codigo,null);
                else $usuario = $ldap->buscarUsuario(null,$codigo);

                //guardamos nuevo usuario (si ya existe no hace nada)
                if($usuario){
                    if($usuario->save()!=-1) $contador++;                    
                    echo "$i) ". $usuario->getIdentificacion() ."-". $usuario->getMatricula() . "(" . $usuario->getNombreUsuario() . ") actualizado! \n";
                    sfContext::getInstance()->getLogger()->info("$i) ". $usuario->getIdentificacion() ."-". $usuario->getMatricula() . "(" . $usuario->getNombreUsuario() . ") actualizado!");
                }else{
                    echo "$i) ". $codigo ."-". ($esprofesor ? "profesor" : "estudiante") . "[no encontrado]\n";
                    sfContext::getInstance()->getLogger()->info("$i) ". $codigo ."-". ($esprofesor ? "profesor" : "estudiante") . "[no encontrado]");
                }
//                $this->flush_buffers();

            }catch(Exception $ee){
                echo "$i) ". $codigo ."-". ($esprofesor ? "profesor" : "estudiante") . "[".$ee->getMessage()."] \n";
                sfContext::getInstance()->getLogger()->info("{Planificacion-Usuario} cargarUsuarios error ".$codigo." [".$ee->getMessage()."]");
            }

        }

        echo "Se actualizaron ".$contador." usuarios.\n";
        sfContext::getInstance()->getLogger()->info("Se actualizaron ".$contador." usuarios.");
    }

    /**
     *
     * @param <type> $superuser
     * @param <type> $periodo
     * @return <type>
     */
    public function cargarUsuariosInactivos($superuser) {        
//        set_time_limit(0);
//        ignore_user_abort(1);
        try{
            
            Usuario::actualizarUsuariosInactivos();
            
        }  catch (Exception $e){
            sfContext::getInstance()->getLogger()->info("{Planificacion-UsuariosInactivos} cargarUsuariosInactivos error general [".$e->getMessage()."]");
            echo("{Planificacion-UsuariosInactivos} cargarUsuariosInactivos error general [".$e->getMessage()."]");
            return false;
        }

        sfContext::getInstance()->getLogger()->info("{Planificacion-UsuariosInactivos} exitosa");
        echo("{Planificacion-UsuariosInactivos} exitosa");
        return true;
    }

    public function cargarRegistros($superuser, $periodo, $rol, $inicio=null) {
        //flush();
//        set_time_limit(0);
//        ignore_user_abort(1);

        try{
            $results = (array) ($this->client->InformacionPlanficacion(array("anio" => $periodo->getAnio(), "termino" => $periodo->getTermino())));
            $results = (array) ($results['InformacionPlanficacionResult']);
            $results = $this->cleanWSPlanificacion($results['any']);

            $doc = new DOMDocument('1.0', 'utf-8');
            $doc->loadXML($results);
            
            if($rol=="Profesor" || $rol=="Todos")
            $elements_profesores = $doc->getElementsByTagName("PARALELO_PROFESOR");

            if($rol=="Estudiante" || $rol=="Todos")
            $elements_estudiantes = $doc->getElementsByTagName("PARALELO_ESTUDIANTE");

            //enviamos a false el campo actualizado para conocer a los que han anulado la materia
            //Registro::setearActualizadoRegsitrosPorTermino($superuser,$rol,$periodo,false);

            if($rol=="Profesor" || $rol=="Todos")
            $this->actualizarRegistros($superuser, $periodo, $elements_profesores,true,$inicio);

            if($rol=="Estudiante" || $rol=="Todos")
            $this->actualizarRegistros($superuser, $periodo, $elements_estudiantes,false,$inicio);

            //eliminamos a los que han anulado la materia
            //Registro::eliminarRegistrosAnulados($superuser,$rol,$periodo);

        }  catch (Exception $e){
            sfContext::getInstance()->getLogger()->info("{Planificacion} cargarPlanificacion error general");
            echo $e->getMessage();
            return false;
        }

        sfContext::getInstance()->getLogger()->info("{Planificacion}  ".$periodo->getPeriodoSlug()." exitosa");
        return true;
    }

    private function actualizarRegistros($superadmin, $periodo, $elements,$esprofesor,$inicio=null){

        echo "\nSe actualizaran ".$elements->length." registros ".($esprofesor ? "profesor" : "estudiante")."\n";
        sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} Se actualizarán ".$elements->length." registros.".($esprofesor ? "profesor" : "estudiante"));
        // RECORREMOS LOS REGISTROS
//        ob_start();       

        $start = 0;
        if($inicio!=null) $start = $inicio;

        for ($i = $start; $i < $elements->length; $i++) {

            try{
                $node = $elements->item($i);
                $codigo = isset($node->childNodes->item(0)->nodeValue) ? trim($node->childNodes->item(0)->nodeValue) : "";
                $codigo_materia = isset($node->childNodes->item(1)->nodeValue) ? trim($node->childNodes->item(1)->nodeValue) : "";
                $paralelo = isset($node->childNodes->item(2)->nodeValue) ? trim($node->childNodes->item(2)->nodeValue) : "";

                echo $i.") ".$codigo."-".$codigo_materia."-".$paralelo;
                sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} ".$codigo."-".$codigo_materia."-".$paralelo);                
                $curso = Curso::getCursoPorPeriodoMateriaParalelo($periodo,$codigo_materia,$paralelo);

                //Si el curso no existe, lo creamos (siempre y cuando exista la materia)
                if(!$curso){
                    echo " - [Curso no existe, intentar crearlo] ";
                    $curso = Curso::crearCurso($periodo,$codigo_materia,$paralelo);
                    if($curso->getId()){
                        echo " - [Curso creado existosamente] ";
                    }else{
                        echo " - [Curso no se creo] ";
                    }
                }

                if($curso){
                    echo " - [Curso encontrado] ";
                    $registro = new Registro();
                    $registro->setCurso($curso); //paralelo, materia
                    $registro->setRegistradoPor($superadmin->getId()); //admin
                    $registro->setRol(Rol::getRolPorNombre($esprofesor ? "Profesor" : "Estudiante")); //profesor, estudiante                    
                    $usuario = Usuario::getUsuarioPorCodigo($codigo);

                    if($usuario){
                        echo " - [Usuario encontrado]";
                        $registro->setUsuarioId($usuario->getId()); //persona
                        $registro->setActualizado(true);
                        $registro->save();

                        if($registro->getId()){
                            echo " - [Registro exitoso] \n";
                            sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} GUARDADO ".$codigo."-".$codigo_materia."-".$paralelo);
                        }else{
                            echo " - [Registro fallido] \n";
                            sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} FALLIDO ".$codigo."-".$codigo_materia."-".$paralelo);
                        }
                        
                    }else{
                        echo " - [Registro fallido] El usuario no existe";
                        sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} NO EXISTE USUARIO ".$codigo);

                        //si no existe, tratamos de crearlo
                        $ldap = new LDAPHandler();
                        $usuario = new Usuario();
                        if($esprofesor) $usuario = $ldap->buscarUsuario(trim($codigo),null);
                        else $usuario = $ldap->buscarUsuario(null,trim($codigo));
                        if($usuario){
                            echo " - [Usuario encontrado LDAP]";

                            $usuario->save();
                            $registro->setUsuarioId($usuario->getId()); //persona
                            $registro->save();
                            echo " - [Registro exitoso]\n";
                            sfContext::getInstance()->getLogger()->info(" - [Registro exitoso]: ".$codigo."-".$codigo_materia."-".$paralelo);
                            
                        }else{
                            echo " - [Usuario LDAP no existe] (".$codigo.")\n";
                            sfContext::getInstance()->getLogger()->info(" [Usuario LDAP no existe] (".$codigo.")");
                        }
                    }
                    
                    unset($registro);
                    
                }else{
                    echo " - [El curso no existe]\n";
                    sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} NO EXISTE CURSO ".$codigo."-".$codigo_materia."-".$paralelo);                   
                }
//                $this->flush_buffers();

//                $usuario->free(true);
//                $curso->free(true);
                //$registro->free(true);
                //$node->free(true);
                //$codigo->free(true);
                //$codigo_materia->free(true);
                //$paralelo->free(true);

                unset($usuario);
                unset($curso);
                unset($node);
                unset($codigo);
                unset($codigo_materia);
                unset($paralelo);
                //unset($registro);
                
            }catch(Exception $ee){
//                echo $ee->getTraceAsString()."\n";
//                echo $ee->getCode()."\n";
                echo "Error ". ($esprofesor ? "profesor" : "estudiante") . " [".$ee->getMessage()."] \n";
                sfContext::getInstance()->getLogger()->info("{Planificacion-Registro} actualizarRegistros error ".$ee->getMessage().")");
            }
            
        }
    }
    
    /**
     * Limpia el buffer
     */
    private function flush_buffers() {
        ob_end_flush();
        ob_flush();
        flush();
        usleep(300000);
        ob_start();
    }

    private function cleanWSPlanificacion($xmlstr){        
        $results = "<?xml version='1.0' encoding='utf-8'?>" . $xmlstr;
        $results = str_replace('<xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"><xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="MATERIA_PARALELO"><xs:complexType><xs:sequence><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="NOMBREMATERIA" type="xs:string" minOccurs="0"/><xs:element name="PARALELO" type="xs:string" minOccurs="0"/><xs:element name="EXAMENPARCIAL" type="xs:dateTime" minOccurs="0"/><xs:element name="EXAMENFINAL" type="xs:dateTime" minOccurs="0"/><xs:element name="EXAMENMEJORAMIENTO" type="xs:dateTime" minOccurs="0"/><xs:element name="NUMCREDITOS" type="xs:short" minOccurs="0"/><xs:element name="CODUNIDAD" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element><xs:element name="PARALELO_PROFESOR"><xs:complexType><xs:sequence><xs:element name="IDENTIFICACION" type="xs:string" minOccurs="0"/><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="PARALELO" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element><xs:element name="PARALELO_ESTUDIANTE"><xs:complexType><xs:sequence><xs:element name="MATRICULA" type="xs:string" minOccurs="0"/><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="PARALELO" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element><xs:element name="HORARIO_PARALELO"><xs:complexType><xs:sequence><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="PARALELO" type="xs:string" minOccurs="0"/><xs:element name="NUMDIA" type="xs:string" minOccurs="0"/><xs:element name="HI" type="xs:duration" minOccurs="0"/><xs:element name="HF" type="xs:duration" minOccurs="0"/></xs:sequence></xs:complexType></xs:element><xs:element name="REQUISITOS_MATERIA"><xs:complexType><xs:sequence><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="NUMCREDITOS" type="xs:short" minOccurs="0"/><xs:element name="CODIGOMATREQ" type="xs:string" minOccurs="0"/><xs:element name="NOMBREMATREQ" type="xs:string" minOccurs="0"/><xs:element name="TIPO" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"><NewDataSet xmlns="">', "<NewDataSet>", $results);
        $results = str_replace('</diffgr:diffgram>', "", $results);
        $results = str_replace('</InformacionPlanficacionResult>', "", $results);
        $results = str_replace('</InformacionPlanficacionResponse>', "", $results);
        $results = str_replace('</soap:Body>', "", $results);
        $results = str_replace('</soap:Envelope>', "", $results);
        $results = str_replace('msdata:', "", $results);
        $results = str_replace('diffgr:', "", $results);
        return $results;
    }
    private function cleanWSUsuarios($xmlstr){        
        /*$results = "<?xml version='1.0' encoding='utf-8'?>" . $xmlstr;*/
        $results = $xmlstr;
        /*$results = str_replace('<xs:schema id="NewDataSet" xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"><xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="PROFESOR"><xs:complexType><xs:sequence><xs:element name="IDENTIFICACION" type="xs:string" minOccurs="0" /><xs:element name="NOMBRE_COMPLETO" type="xs:string" minOccurs="0" /><xs:element name="FACULTAD" type="xs:string" minOccurs="0" /></xs:sequence></xs:complexType></xs:element><xs:element name="ESTUDIANTE"><xs:complexType><xs:sequence><xs:element name="MATRICULA" type="xs:string" minOccurs="0" /></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1"><NewDataSet xmlns="">', "<NewDataSet>", $results);*/
        $results = str_replace('<NewDataSet xmlns="">', "<NewDataSet>", $results);
        $results = str_replace('</diffgr:diffgram>', "", $results);
        $results = str_replace('</InfoProfesoresEstudiantesIdsResult>', "", $results);
        $results = str_replace('</InfoProfesoresEstudiantesIdsResponse>', "", $results);
        $results = str_replace('</soap:Body>', "", $results);
        $results = str_replace('</soap:Envelope>', "", $results);
        $results = str_replace('msdata:', "", $results);
        $results = str_replace('diffgr:', "", $results);
        $results = substr($results,strpos($results,"<NewDataSet>"),strlen($results));
        return $results;
    }

//    private static function obj2array($obj) {
//        $out = array();
//        foreach ($obj as $key => $val) {
//            switch (true) {
//                case is_object($val):
//                    $out[$key] = WSHandler::obj2array($val);
//                    break;
//                case is_array($val):
//                    $out[$key] = WSHandler::obj2array($val);
//                    break;
//                default:
//                    $out[$key] = $val;
//            }
//        }
//        return $out;
//    }

}

?>