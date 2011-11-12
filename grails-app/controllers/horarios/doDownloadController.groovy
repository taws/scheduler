package horarios

import horarios.Academic
import com.lowagie.text.*;
import com.lowagie.text.pdf.PdfWriter;

class doDownloadController {

    def index = { }

    def pdf = {
        def schedule = new Academic("WEB-INF/wsSAAC.wsdl")
        def horarios = schedule.retrieveSchedule(params.matricula)

        Document document;

        try {

        } catch (Exception ex) {
            
        }
    }
}
