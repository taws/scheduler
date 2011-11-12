class UrlMappings {
    static mappings = {
      "/$controller/$action?/$id?"{
	      constraints {
			 // apply constraints here
		  }
	  }
      "/doFind" {
            controller = "academic"
            action = "scheduleXml"
      }
      "/doDownload/pdf/$matricula" {
            controller = "doDownload"
            action = "pdf"
      }
      "/"(view:"/index")
      "500"(view:'/error')
    }
}
