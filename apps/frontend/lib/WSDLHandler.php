<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WSDLHandler
 *
 * @author Allan
 */
class WSDLHandler {
    //put your code here
    var $wsSAAC = "/webservices/wsSAAC.wsdl";
    var $directorioEspol = "/webservices/directorioEspol.wsdl";
    var $client;

    public function initWSSAACHandler() {
        $this->client = new SoapClient(sfConfig::get("sf_web_dir") . $this->wsSAAC, array());
    }

    public function initDirectorioEspolHandler() {
        $this->client = new SoapClient(sfConfig::get("sf_web_dir") . $this->directorioEspol, array());
    }

    public function authenticate($user,$password) {
        $results = (array) $this->client->autenticacion(array("varUser" => $user,"varContrasenia" => $password));
        $results = (array) ($results['autenticacionResult']);
        return $results[0];
    }
    
    public function userData($user,$password) {
        $results = (array) $this->client->datosUsuario(array("varUser" => $user,"varContrasenia" => $password));
        $results = (array) ($results['datosUsuarioResult']);
        return $this->cleanUserDataResult($results['any']);
    }
    
    public function scheduler($matricula) {
        $results = (array) $this->client->HorarioClasesScheluder(array("matricula" => $matricula));
        $results = (array) ($results['HorarioClasesScheluderResult']);
        return $this->cleanSchedulerResult($results['any']);
    }

    private function cleanUserDataResult($results) {
        $results = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".$results;
        $results = str_replace('<xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet">', "<NewDataSet>", $results);
        $results = str_replace('<xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="DATOS_USUARIO">', "", $results);
        $results = str_replace('<xs:complexType><xs:sequence><xs:element name="MATRICULA" type="xs:string" minOccurs="0"/><xs:element name="CEDULA" type="xs:string" minOccurs="0"/><xs:element name="APELLIDOS" type="xs:string" minOccurs="0"/><xs:element name="NOMBRES" type="xs:string" minOccurs="0"/>', "", $results);
        $results = str_replace('<xs:element name="NOMBRE_COMPLETO" type="xs:string" minOccurs="0"/><xs:element name="UNIDAD" type="xs:string" minOccurs="0"/><xs:element name="ROL" type="xs:string" minOccurs="0"/><xs:element name="CORREO" type="xs:string" minOccurs="0"/><xs:element name="DIRECCION" type="xs:string" minOccurs="0"/><xs:element name="TELEFONO" type="xs:string" minOccurs="0"/>', "", $results);
        $results = str_replace('<xs:element name="CELULAR" type="xs:string" minOccurs="0"/><xs:element name="SEXO" type="xs:string" minOccurs="0"/><xs:element name="CARRERA" type="xs:string" minOccurs="0"/><xs:element name="ESPECIALIZACION" type="xs:string" minOccurs="0"/><xs:element name="FECHANACIMIENTO" type="xs:string" minOccurs="0"/><xs:element name="PROMEDIO" type="xs:decimal" minOccurs="0"/><xs:element name="MATERIASAPROBADAS" type="xs:int" minOccurs="0"/><xs:element name="LASTCHANGED" type="xs:dateTime" minOccurs="0"/></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">', "", $results);
        $results = str_replace('<NewDataSet xmlns="">', "", $results);
        $results = str_replace('</diffgr:diffgram>', "", $results);
        return $results;
    }

    private function cleanSchedulerResult($results){
        $results = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".$results;
        $results = str_replace('<xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" id="NewDataSet"><xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true"><xs:complexType><xs:choice minOccurs="0" maxOccurs="unbounded"><xs:element name="V_MAT_REGISTRADAS"><xs:complexType><xs:sequence><xs:element name="IDCURSO" type="xs:int" minOccurs="0"/><xs:element name="CODIGOMATERIA" type="xs:string" minOccurs="0"/><xs:element name="NOMBREMATERIA" type="xs:string" minOccurs="0"/><xs:element name="PARALELO" type="xs:short" minOccurs="0"/><xs:element name="PROFESOR" type="xs:string" minOccurs="0"/><xs:element name="NUMHORAS" type="xs:int" minOccurs="0"/><xs:element name="TIPOCURSO" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element><xs:element name="V_HORARIO_CLASES"><xs:complexType><xs:sequence><xs:element name="IDCURSO" type="xs:int" minOccurs="0"/><xs:element name="DIA" type="xs:short" minOccurs="0"/><xs:element name="HORAINICIO" type="xs:string" minOccurs="0"/><xs:element name="HORAFIN" type="xs:string" minOccurs="0"/><xs:element name="AULA" type="xs:string" minOccurs="0"/><xs:element name="BLOQUE" type="xs:string" minOccurs="0"/><xs:element name="CAMPUS" type="xs:string" minOccurs="0"/></xs:sequence></xs:complexType></xs:element></xs:choice></xs:complexType></xs:element></xs:schema><diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">', "<NewDataSet>", $results);
        $results = str_replace('<NewDataSet xmlns="">', "", $results);
        $results = str_replace('</diffgr:diffgram>', "", $results);
        $results = str_replace('</soap:Body>', "", $results);
        $results = str_replace('</soap:Envelope>', "", $results);
        $results = str_replace('msdata:', "", $results);
        $results = str_replace('diffgr:', "", $results);
        return $results;
    }
}
?>
