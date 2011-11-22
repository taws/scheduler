var request;

function loadSchedule(url,link_to_share) {
       
       Grid.restoreAll();

        $('sharethis').hide();
        $('sharethis').update();

        $('message-error').hide();
        $('message-error').update();

        request= new Ajax.Request(url,{
            method: "POST",
            parameters: {
                internal: true
            },
            onSuccess: function(schedule) {

                if(schedule.responseJSON.constructor.toString().indexOf("Array") != -1) {
                    //linkToShare(user, password,link_to_share); //Change to the new enviroment
                    Subject.chargeJSON(schedule.responseJSON);
                    
                } else {
                    //Error
                    $('message-error').show();
                    $('message-error').update(schedule.responseJSON.error);
                }

                $('loader-bar').hide();


            },
            onLoading: function() {
                $('loader-bar').show();
            }
        });
    

}

function loadSchedulePpl(url,u_id,pos) {
    Grid.restoreAll();

    $('message-error').hide();
    $('message-error').update();

    request=new Ajax.Request(url,{
        method: "POST",
        parameters: {
            matricula: u_id,
            internal: true
        },
        onSuccess: function(schedule) {

            if(schedule.responseJSON.constructor.toString().indexOf("Array") != -1) {
                Subject.chargeJSON(schedule.responseJSON);

            } else {
                //Error
                $('message-error').show();
                $('message-error').update(schedule.responseJSON.error);
            }

            document.getElementsByClassName('loader')[pos].style.display= 'none';

        },
        onLoading: function() {
            document.getElementsByClassName('loader')[pos].style.display= 'block';
            document.getElementsByClassName("list-ppl")[pos].setAttribute("style",'background-color: #D8DFEA;');
        }
    });
}
function linkToShare(user,password,link_to_share) {

    //var link_to_share = "link_to_share";
    new Ajax.Request(link_to_share,{
        method: "POST",
        parameters: {
            user: user,
            password: password
        },
        onSuccess: function(link) {
            $('sharethis').show();
            
            stWidget.addEntry({
                "service":"sharethis",
                "element":$('sharethis'),
                "url":'http://scheduler.taws.espol.edu.ec/'+link.responseJSON.code+"/"+link.responseJSON.zm,
                "title":"TAWS Scheduler",
                "type":"hcount",
                "summary": 'TAWS - SCHEDULER',
                "image": 'http://profile.ak.fbcdn.net/hprofile-ak-snc4/41575_84247561221_7928828_n.jpg'
            },{offsetLeft: -100, offsetTop: -5});
            
            $('sharethis').focus();
            $('stwrapper').setStyle({left:'655px'});
            
        }
    });

}

function loadShareThis(code) {
    var ctrl;
    var url = "http://scheduler.taws.espol.edu.ec/lln/"+code;

    new Ajax.Request(url,{
        method: "GET",
        onSuccess: function(schedule) {
            Subject.chargeJSON(schedule.responseJSON);
            ctrl.remove();
        },
        onLoading: function() {
            ctrl = getBusyOverlay($('main'),
                        {color:'black', opacity:0.5, text:'cargando...', style:'font-weight:bold;font-size:14px;color:white'},
                        {color:'#fff', size:128, type:'o'});
        }
    });

}

function loadScheduleOld(url,link_to_share) {

    var ctrl;
    var user = $('user').getValue();
    var password = $('password').getValue();
    

    if(user == '') {
        $('user').focus();
        $('user').setStyle({backgroundColor: '#FFF6B2'});
        setTimeout(function(){
            $('user').setStyle({backgroundColor: '#FFFFFF'});
        },1000);
    }
    else if(password == '') {
        $('password').focus();
        $('password').setStyle({backgroundColor: '#FFF6B2'});
        setTimeout(function(){
            $('password').setStyle({backgroundColor: '#FFFFFF'});
        },1000);
    }
    else {
        
       Grid.restoreAll();

        $('sharethis').hide();
        $('sharethis').update();

        $('message-error').hide();
        $('message-error').update();

        request= new Ajax.Request(url,{
            method: "POST",
            parameters: {
                user: user,
                password: password
            },
            onSuccess: function(schedule) {

                if(schedule.responseJSON.constructor.toString().indexOf("Array") != -1) {
                    linkToShare(user, password,link_to_share);
                    Subject.chargeJSON(schedule.responseJSON);
                    
                } else {
                    //Error
                    $('message-error').show();
                    $('message-error').update(schedule.responseJSON.error);
                }

                $('password').setValue('');
                $('user').setValue('');

                ctrl.remove();


            },
            onLoading: function() {
                ctrl = getBusyOverlay($('main'),
                            {color:'black', opacity:0.5, text:'Cargando...', style:'font-weight:bold;font-size:14px;color:white'},
                            {color:'#fff', size:128, type:'o'});
            }
        });
    }

}