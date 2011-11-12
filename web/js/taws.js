/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function evalMatricula() {
    var url = "doFind";
    
    $A(arguments).each(function(matricula,index){
      
      $('rm'+index).update();        
      
      if(matricula.length > 0) {
          new Ajax.Request(url,{
          method: "POST",
          parameters: {
              matricula: matricula,
              tipoHorario: 'clases'
          },onFailure: function(){ 
              
              $('rm'+index).update("\tMatr&iacute;cula inactiva");
          }
          });
      }          
    });
        
}

function meeting() {
    var url = "doMeeting.do";
    var m0 = $('m0').getValue(); 
    var m1 = $('m1').getValue();               
    var m2 = $('m2').getValue();               
    var m3 = $('m3').getValue();               
    var m4 = $('m4').getValue();               
    var m5 = $('m5').getValue();               
    
    
        
      Grid.restoreAll();
      new Ajax.Request(url,{
          method: "POST",
          parameters: {
              matricula: m0,
              m1: m1,
              m2: m2,
              m3: m3,
              m4: m4,
              m5: m5
          },
          onSuccess: function(transport) {
              var xmlHorario = transport.responseText;

              $("loading").hide();
              if(xmlHorario.toString().length == 0) {
                  $('message').update('No existe informaci&oacute;n disponible');
                  return null;
              }
              Subject.chargeSchedule(xmlHorario);
          },
          onLoading: function() {

              $("loading").setStyle(
                  {
                      backgroundImage: 'url(images/ajax-loader.gif)',
                      backgroundRepeat: 'no-repeat',
                      marginLeft: '190px',
                      marginTop: '-45px',
                      display: 'block',
                      height: '35px',
                      width: '35px',
                      textAlign: 'right'
                  }
              );

          }
      });
        
    evalMatricula(m0,m1,m2,m3,m4,m5);
}

function freecourse() {
    var url = "doFreeTime.do";
    var codigo = $('codigo').getValue();
    var paralelo = $('paralelo').getValue();
    
    new Ajax.Request(url,{
        method: "POST",
        parameters: {
            codigo: codigo,
            paralelo: paralelo
        },
        onSuccess: function(transport) {

            Grid.restoreAll();
            var xmlHorario = transport.responseText;
            
            $("loading").hide();
            if(xmlHorario.toString().length == 0) {
                $('message').update('No existe informaci&oacute;n disponible');
                return null;
            }
                
            
            Subject.chargeSchedule(xmlHorario);
            $('message').update('Materia: '+codigo+', paralelo '+paralelo);
            
        },
        onLoading: function() {

            $("loading").setStyle(
                {
                    backgroundImage: 'url(images/ajax-loader.gif)',
                    backgroundRepeat: 'no-repeat',
                    marginLeft: '190px',
                    marginTop: '-45px',
                    display: 'block',
                    height: '35px',
                    width: '35px',
                    textAlign: 'right'
                }
            );
        
        }
        
        
    });
    
}

function loadExternalSchedule() {
    
    new Spry.Effect.Squish('freetime', {from: '0%', to: '100%', duration: 2000}).start();
    new Spry.Effect.Highlight('freetime', {to:'#FF833F'}).start();
    
    var search = location.search;
    if(search.length > 0) {
        var clases = search.split("/")[0];
        if(clases == '?d') {
        
            var matricula = search.split("?d/")[1];
            var url = "doFind";

            new Ajax.Request(url,{

                method: "POST",
                parameters: {
                    matricula: matricula,
                    tipoHorario: 'clases'
                },
                onSuccess: function(transport) {

                    Grid.restoreAll();
                    var xmlHorario = transport.responseText;
                    Subject.chargeSchedule(xmlHorario);
                },
                onLoading: function() {
                }   

            });
        
        } 
    }
}

function miHorario() {
    var url = "horario/doFind";
    var matricula = $('matricula').getValue();
    var tipoHorario = $('tipoHorario').getValue();
    
    new Ajax.Request(url,{
        method: "POST",
        parameters: {
            matricula: matricula,
            tipoHorario: tipoHorario
        },
        onSuccess: function(transport) {

            Grid.restoreAll();
            var xmlHorario = transport.responseText;
            Subject.chargeSchedule(xmlHorario);

            $("instrucciones").setStyle({visibility: 'visible'});
            $('verhorario').hide();
            $("mensaje").update('');
            $("mensaje").setStyle(
                {
                    backgroundImage: 'url(images/downloadPDF.png)',
                    backgroundRepeat: 'no-repeat',
                    marginLeft: '42px',
                    marginTop: '18px',
                    width: '57px',
                    height: '42px',
                    display: 'block'
                }
            );

            $('mensaje').writeAttribute("href","myPDF?matricula="+matricula+"&tipoHorario="+tipoHorario);
            
            //download excel
            $("dwexcel").setStyle(
                {
                    backgroundImage: 'url(images/excel.jpg)',
                    backgroundRepeat: 'no-repeat',
                    marginLeft: '100px',
                    marginTop: '-70px',
                    width: '74px',
                    height: '73px',
                    display: 'block'
                }
            );
            
            $('dwexcel').writeAttribute("href","doExcel.do?matricula="+matricula+"&tipoHorario="+tipoHorario);
            
            $('contactus').setStyle({
                top: '303px'
            });

            //refresh de la página
            $('finder').update();
            var button = new Element('button').update('Nueva Consulta');
            button.setStyle({marginLeft: '12px'});
            button.observe('click',function(){
                window.location = window.location;
            });
            $('finder').insert(button);
        
        },
        onFailure: function(){
            $("mensaje").update('La matr&iacute;cula est&aacute; inactiva');
            $("mensaje").setStyle({
                    backgroundImage: 'none',
                    marginLeft: '0px',
                    marginTop: '0px',
                    height: '19px',
                    width: '158px'
           });
           $('contactus').setStyle({
                top: '330px'
            });

            //refresh de la página
            $('finder').update();
            var button = new Element('button').update('Nueva Consulta');
            button.setStyle({marginLeft: '12px'});
            button.observe('click',function(){
                window.location = window.location;
            });
            $('finder').insert(button);

        },
        onLoading: function() {

            $("mensaje").update('');
            $("mensaje").setStyle(
                {
                    backgroundImage: 'url(images/ajax-loader.gif)',
                    backgroundRepeat: 'no-repeat',
                    marginLeft: '75px',
                    marginTop: '25px',
                    display: 'block',
                    height: '38px',
                    width: '100px'
                }
            );
            $('contactus').setStyle({
                top: '248px'
            });

        }
    });
    
    
    
}

