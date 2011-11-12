package horarios

import groovyx.net.ws.WSClient
import org.codehaus.groovy.grails.commons.ApplicationHolder
import groovy.xml.DOMBuilder
import groovy.xml.dom.DOMCategory
import org.grails.plugins.wsclient.service.WebService
import org.apache.log4j.*

class Academic {

    String wsdl_path_saac
    public def proxy
    
    static constraints = {
    }

    public Academic(String wsdl_path) {
        wsdl_path_saac = wsdl_path
        this.initializeProxy()
    }

    public void initializeProxy(){
        def wsdl = ApplicationHolder.application.parentContext.getResource(this.wsdl_path_saac)
        proxy = new WSClient(wsdl.getURL().toString(), this.class.classLoader)
        proxy.initialize()
    }

     private Object retrieveSchedule(String matricula){
        def horarios = []
        def horario = [:]
        def materias = []
        def materia = [:]

        try{
            def grandpa = proxy.HorarioClasesScheluder(matricula)

            if(grandpa.any[1].firstChild == null) {
               log.info "OFF. Matricula: "+matricula
               return []
            }

            grandpa.any[1].firstChild.getChildNodes().each{ children ->
                if(children.getLocalName().equals("V_MAT_REGISTRADAS")) {
                    materia = [:]
                    children.getChildNodes().each{ nephew ->
                        switch(nephew.getLocalName()) {
                            case "IDCURSO":
                                materia.put("codigoparalelo",nephew.textContent)
                                break
                            case "NOMBREMATERIA":
                                materia.put("materia",nephew.textContent)
                                break
                            case "PROFESOR":
                                materia.put("profesor",nephew.textContent)
                                break
                            case "PARALELO":
                                materia.put("paralelo",nephew.textContent)
                                break
                            default:
                                break
                        }
                    }
                    materias.add(materia)
                }
                if(children.getLocalName().equals("V_HORARIO_CLASES")) {
                    horario = [:]
                    children.getChildNodes().each{ nephew ->
                        switch(nephew.getLocalName()) {
                            case "IDCURSO":
                                horario.put("codigoparalelo",nephew.textContent)
                                break
                            case "CODIGOMATERIA":
                                horario.put("codigomateria",this.getDia(nephew.textContent))
                                break
                            case "DIA":
                                horario.put("dia",this.getDia(nephew.textContent))
                                break
                            case "BLOQUE":
                                horario.put("ubicacion",nephew.textContent)
                                break
                            case "AULA":
                                horario.put("aula",nephew.textContent)
                                break
                            case "HORAINICIO":
                                horario.put("horaini",getHM(nephew.textContent,0))
                                horario.put("minutoini",getHM(nephew.textContent,1))
                                break
                            case "HORAFIN":
                                horario.put("horafin",getHM(nephew.textContent,0))
                                horario.put("minutofin",getHM(nephew.textContent,1))
                                break
                            default:
                                break
                        }
                    }
                    horarios.add(horario)
                }
            }

            horarios.each { item ->
                def found = materias.find{ it.codigoparalelo == item.codigoparalelo }
                item.put("profesor",found.profesor)
                item.put("materia",found.materia)
                item.put("paralelo",found.paralelo)
            }
            
            log.info " OK. Matricula: "+matricula

            return horarios
            
        }catch(Exception e){
            println "wsAcademic | retrieve | error: " + e.toString()
        }

        return null
    }

    private Object getIdentification(String user,String password){
        def matricula
        def grandpa = proxy.datosUsuario(user,password)

        log.info " IN. USR "+user+" PWD "+password

        if(grandpa == null) {
            log.info "ERR. USR "+user
            return ["authentication":false]
        }
        
        grandpa.any[1].firstChild.getChildNodes().each{ children ->
            children.getChildNodes().each{ nephew ->
                if(nephew.getLocalName().equals("MATRICULA")) {
                    matricula = nephew.textContent
                }
            }
        }

        return ["authentication":true, "matricula":matricula]
    }

    public String getDia(String dia) {
        switch(dia){
          case "0":
            return "domingo"
          case "1":
            return "lunes"
          case "2":
            return "martes"
          case "3":
            return "miercoles"
          case "4":
            return "jueves"
          case "5":
            return "viernes"
          case "6":
            return "sabado"
          default:
            break
        }
    }

    public String getHM(String hora, int indice) {
        return hora.split('[.]')[indice]
    }

}
