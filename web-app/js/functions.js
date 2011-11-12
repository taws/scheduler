function loadSchedule() {
    var url = "academic/authentication";

    var user = $('user').getValue();
    var password = $('password').getValue();
    
    $('finder').update();

    new Ajax.Request(url,{
        method: "POST",
        parameters: {
            user: user,
            password: password
        },
        onSuccess: function(transport) {

            if(transport.responseJSON.authentication){
                new Ajax.Request("academic/schedule",{
                    method: "POST",
                    parameters: {
                        matricula:transport.responseJSON.matricula
                    },
                    onSuccess: function(schedule) {

                        if(schedule.responseJSON.length > 0) {
                            Subject.chargeJSON(schedule.responseJSON)
                            $("finder").update();
                            $("finder").update('Gracias por utilizar el Schedule Viewer, pronto descarga tu horario en formato PDF y XLS');
                            $("finder").setStyle(
                              {
                                  backgroundImage: 'url()',
                                  marginLeft: '40px',
                                  marginTop: '160px',
                                  height: '35px',
                                  width: '110px',
                                  textAlign: 'center',
                                  color: 'green'

                              }
                            );
                        } else {
                            $("finder").update();
                            $("finder").update('No se encuentra registrad@ en ninguna materia');
                            $("finder").setStyle(
                              {
                                  backgroundImage: 'url()',
                                  marginLeft: '40px',
                                  marginTop: '160px',
                                  height: '35px',
                                  width: '110px',
                                  textAlign: 'center',
                                  color: 'red'
                              }
                            );
                        }

                        
                        /*$("finder").setStyle({
                            backgroundImage: 'url(images/downloadPDF.png)',
                            backgroundRepeat: 'no-repeat',
                            marginLeft: '67px',
                            marginTop: '160px',
                            width: '77px',
                            height: '66px'
                        });*/
                    }
                });
            } else {
                $("finder").update();
                $("finder").update('Usuario o Contraseña incorrecto(s)');
                $("finder").setStyle(
                  {
                      backgroundImage: 'url()',
                      marginLeft: '40px',
                      marginTop: '160px',
                      height: '35px',
                      width: '110px',
                      textAlign: 'center',
                      color: 'red'
                  }
                );
            }

        },
        onLoading: function() {

              $("finder").setStyle(
                  {
                      backgroundImage: 'url(images/ajax-loader.gif)',
                      backgroundRepeat: 'no-repeat',
                      marginLeft: '89px',
                      marginTop: '160px',
                      height: '35px',
                      width: '35px',
                      textAlign: 'right'
                  }
              );

          }
    });
}