package horarios

import grails.converters.*
import horarios.Academic
import java.util.Date

class AcademicController {

    private final static String WSSAAC = "WEB-INF/wsSAAC.wsdl"
    private final static String DIRECTORIOESPOL = "WEB-INF/directorioEspol.wsdl"

    static allowedMethods = [save: "POST", update: "POST", delete: "POST"]

    def index = {
        redirect(action: "index", params: params)
    }

    def list = {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
        [academicInstanceList: Academic.list(params), academicInstanceTotal: Academic.count()]
    }

    def create = {
        def academicInstance = new Academic()
        academicInstance.properties = params
        return [academicInstance: academicInstance]
    }

    def save = {
        def academicInstance = new Academic(params)
        if (academicInstance.save(flush: true)) {
            flash.message = "${message(code: 'default.created.message', args: [message(code: 'academic.label', default: 'Academic'), academicInstance.id])}"
            redirect(action: "show", id: academicInstance.id)
        }
        else {
            render(view: "create", model: [academicInstance: academicInstance])
        }
    }

    def show = {
        def academicInstance = Academic.get(params.id)
        if (!academicInstance) {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
            redirect(action: "list")
        }
        else {
            [academicInstance: academicInstance]
        }
    }

    def edit = {
        def academicInstance = Academic.get(params.id)
        if (!academicInstance) {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
            redirect(action: "list")
        }
        else {
            return [academicInstance: academicInstance]
        }
    }

    def update = {
        def academicInstance = Academic.get(params.id)
        if (academicInstance) {
            if (params.version) {
                def version = params.version.toLong()
                if (academicInstance.version > version) {
                    
                    academicInstance.errors.rejectValue("version", "default.optimistic.locking.failure", [message(code: 'academic.label', default: 'Academic')] as Object[], "Another user has updated this Academic while you were editing")
                    render(view: "edit", model: [academicInstance: academicInstance])
                    return
                }
            }
            academicInstance.properties = params
            if (!academicInstance.hasErrors() && academicInstance.save(flush: true)) {
                flash.message = "${message(code: 'default.updated.message', args: [message(code: 'academic.label', default: 'Academic'), academicInstance.id])}"
                redirect(action: "show", id: academicInstance.id)
            }
            else {
                render(view: "edit", model: [academicInstance: academicInstance])
            }
        }
        else {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
            redirect(action: "list")
        }
    }

    def delete = {
        def academicInstance = Academic.get(params.id)
        if (academicInstance) {
            try {
                academicInstance.delete(flush: true)
                flash.message = "${message(code: 'default.deleted.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
                redirect(action: "list")
            }
            catch (org.springframework.dao.DataIntegrityViolationException e) {
                flash.message = "${message(code: 'default.not.deleted.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
                redirect(action: "show", id: params.id)
            }
        }
        else {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'academic.label', default: 'Academic'), params.id])}"
            redirect(action: "list")
        }
    }

    def schedule = {
        def schedule = new Academic(WSSAAC)
        render schedule.retrieveSchedule(params.matricula) as JSON
    }

    def scheduleXml = {
        def schedule = new Academic(WSSAAC)
        def list = schedule.retrieveSchedule(params.matricula)
        render(contentType:"text/xml"){
            horarios {
              for(a in list){
                horario {
                  codigoparalelo(a.codigoparalelo)
                  materia(a.materia)
                  dia(a.dia)
                  horaini(a.horaini)
                  minutoini(a.minutoini)
                  horafin(a.horafin)
                  minutofin(a.minutofin)
                  paralelo(a.paralelo)
                  aula(a.aula)
                  profesor(a.profesor)
                  ubicacion(a.ubicacion)
                }
              }
            }
        }
    }

    def authentication = {
        def acad = new Academic(DIRECTORIOESPOL)
        def result = acad.getIdentification(params.user,params.password)

        if(result.authentication) {
            log.trace("User: "+params.user+" Params: "+params.password)
            render result as JSON
        } else render result as JSON
    }

}
