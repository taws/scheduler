function share(url){
	tlist2.update(); 
	
	$('tabs-top').show();
        $('message-error').hide();
        $('message-error').update();
        
	if($F('facebook-demo').length==0){
            $('message-error').show();
            $('message-error').update('No ha escogido a nadie');
            document.getElementById('message-error').style.color= "red";
        }else{
            new Ajax.Request(url,{
            method: "POST",
            parameters: {
                ppl: $F('facebook-demo'),
                internal: true
            },
            onSuccess: function(sharing) {

                    $('message-error').show();
                    $('message-error').update(sharing.responseJSON.msg)
                    if(sharing.responseJSON.error)
                        document.getElementById('message-error').style.color= "red";
                    else
                        document.getElementById('message-error').style.color= "green";
                
//                $('tabs-top').hide();
                $('loader-bar').hide();
                closeShare();

            },
            onLoading: function() {
                $('loader-bar').show();
            }
            });
        }
	
	
}
function shareStart(){
    $('tabs-top').hide();
    $('start-share').hide();
    $('search-bar').show();
//    document.getElementById('sharing-buttons').style.paddingRight= '14%';
    $('share').show();
}
function closeShare(){
    cleanSelected();
    $('search-bar').hide();
    $('share').hide();
//    document.getElementById('sharing-buttons').style.paddingRight= '0';
    $('start-share').show();
}
function cleanSelected(){
    tlist2.restart();
    var selected= document.getElementsByClassName('bit-box');
    var size=selected.length;
    for(var k=0;k<size;k++){
       document.getElementsByClassName('bit-box')[0].remove();
    }
}