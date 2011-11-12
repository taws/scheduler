var Subject = new Class.create({
  initialize: function(attr){
    this.attr = {
      parallelcode: -1,
      name: '',
      day: null,
      initHour: '',
      endHour: '',
      parallel: -1,
      room: '',
      ubication: '',
      professor: ''
    };
    
    Object.extend(this.attr, attr);
    
  }
});

var counter = 1;

Object.extend(Subject,{
  chargeJSON: function(jsonSchedule) {
    jsonSchedule.each(function(horario){
        
        var subject = new Subject({
          name: horario.materia,
          parallel: horario.paralelo,
          room: horario.aula,
          ubication: horario.ubicacion,
          parallelcode: horario.codigoparalelo,
          professor: horario.profesor
        });

        var objIni = {
          id: -1,
          value: horario.dia,
          hour: horario.horaini,
          minute: horario.minutoini,
          isEnd: false,
          result: 'undefined'
        };

        var objEnd = {
          id: -1,
          value: horario.dia,
          hour: horario.horafin,
          minute: horario.minutofin,
          isEnd: true,
          result: 'undefined'
        };

        Grid.toCells(objIni);
        Grid.toCells(objEnd);

        if(objIni.result != 'undefined' && objEnd.result != 'undefined') {
          var content = subject.attr.name;

          if(Grid.addContentCell(objIni.result,objEnd.result,content)) {
            Scheduler.addToSchedule(horario.codigoparalelo,objIni.result);

            $(objIni.result).observe('mouseover',function(){

              var balloon;
              balloon = "<label style=\"font-weight: bolder; font-style:italic; text-decoration: underline;\">"+subject.attr.name;
              balloon += "<\/label> Par.: "+subject.attr.parallel+"<br>";
              balloon += "<label style=\"font-weight:bold;\">Profesor: <\/label>";
              balloon += "<label style=\"text-align:right; font-style:italic;\">"+subject.attr.professor+"<\/label><br>";
              balloon += "<label style=\"font-weight:bold;\">Aula:     <\/label>"+subject.attr.room;
              balloon += " ("+subject.attr.ubication+")<br>";
              Tip(balloon, BALLOON, true, ABOVE, true, OFFSETX, -17, PADDING, 8);
            });

            $(objIni.result).observe('mouseout',function(){
                UnTip();
            });
          }
        }
    })
  },
  chargeSchedule: function(xmlSchedule){
  
    if(xmlSchedule != null && xmlSchedule.length > 0) { 
      
      var schedule = xmlSchedule.split('<horario>');
      
      $R(1,schedule.length-1).each(function(i){
        var string = schedule[i];
        
        var parallelcode    = this.getXMLValue(string,"codigoparalelo");
        var name            = this.getXMLValue(string,"materia");
        var day             = this.getXMLValue(string,"dia");
        
        var initHour        = parseInt(this.getXMLValue(string,"horaini"),10);
        var initMinute      = parseInt(this.getXMLValue(string,"minutoini"),10);
        var endHour         = parseInt(this.getXMLValue(string,"horafin"),10);
        var endMinute       = parseInt(this.getXMLValue(string,"minutofin"),10);
        
        if(initMinute == 0)
          initMinute = '00';
        
        if(endMinute == 0);
          endMinute = '00';
        
        var parallel        = this.getXMLValue(string,"paralelo");
        var room            = this.getXMLValue(string,"aula");
        var ubication       = this.getXMLValue(string,"ubicacion");
        var professor       = this.getXMLValue(string,"profesor");
        
        
        var subject = new Subject({
          name: name,
          parallel: parallel,
          room: room,
          ubication: ubication,
          parallelcode: parallelcode,
          professor: professor
        });
        
        var objIni = {
          id: -1,
          value: day,
          hour: initHour,
          minute: initMinute,
          isEnd: false,
          result: 'undefined'
        };
      
        var objEnd = {
          id: -1,
          value: day,
          hour: endHour,
          minute: endMinute,
          isEnd: true,
          result: 'undefined'
        };
        
        Grid.toCells(objIni);
        Grid.toCells(objEnd);
        
        if(objIni.result != 'undefined' && objEnd.result != 'undefined') {
          var content = subject.attr.name;
          
          if(Grid.addContentCell(objIni.result,objEnd.result,content)) {
            Scheduler.addToSchedule(parallelcode,objIni.result);
          
            $(objIni.result).observe('mouseover',function(){
              
              var balloon;
              balloon = "<label style=\"font-weight: bolder; font-style:italic; text-decoration: underline;\">"+subject.attr.name;
              balloon += "<\/label> Par.: "+subject.attr.parallel+"<br>";
              balloon += "<label style=\"font-weight:bold;\">Profesor: <\/label>";
              balloon += "<label style=\"text-align:right; font-style:italic;\">"+subject.attr.professor+"<\/label><br>";
              balloon += "<label style=\"font-weight:bold;\">Aula:     <\/label>"+subject.attr.room;
              balloon += " ("+subject.attr.ubication+")<br>";
              Tip(balloon, BALLOON, true, ABOVE, true, OFFSETX, -17, PADDING, 8);
            });
            
            $(objIni.result).observe('mouseout',function(){
                UnTip();
            });
            
          } 
            
        }
      },{getXMLValue: this.getXMLValue});
      
    }
  },
  getXMLValue: function(string,element){
    return(string.split('<'+element+'>')[1].split('</'+element+'>')[0]);
  },
  prueba: function(){
    
   var horario = new Hash();
   
   horario.set(1,"<horarios><horario><codigoparalelo>2202</codigoparalelo><materia>CONMUTACI�N Y ENRUTAMIENTO I</materia><dia>lunes</dia><horaini>13</horaini><minutoini>0</minutoini><horafin>15</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>LCIS</aula><profesor>Diana Villacis</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>2202</codigoparalelo><materia>CONMUTACI�N Y ENRUTAMIENTO I</materia><dia>miercoles</dia><horaini>13</horaini><minutoini>0</minutoini><horafin>15</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>LCIS</aula><profesor>Diana Villacis</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>viernes</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-01</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>miercoles</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-03</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2198</codigoparalelo><materia>INGENIER�A DE SOFTWARE II</materia><dia>lunes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM3</aula><profesor>Monica Villavicencio</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2198</codigoparalelo><materia>INGENIER�A DE SOFTWARE II</materia><dia>miercoles</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM3</aula><profesor>Monica Villavicencio</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2056</codigoparalelo><materia>INTERACCI�N HOMBRE M�QUINA</materia><dia>jueves</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-01</aula><profesor>Katherine Chiluiza</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2056</codigoparalelo><materia>INTERACCI�N HOMBRE M�QUINA</materia><dia>martes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-01</aula><profesor>Katherine Chiluiza</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3019</codigoparalelo><materia>SISTEMAS  MULTIMEDIA</materia><dia>martes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-01</aula><profesor>Cristina Guerrero</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3019</codigoparalelo><materia>SISTEMAS  MULTIMEDIA</materia><dia>lunes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM2</aula><profesor>Cristina Guerrero</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2292</codigoparalelo><materia>SISTEMAS OPERATIVOS </materia><dia>martes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Carmen Vaca</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2292</codigoparalelo><materia>SISTEMAS OPERATIVOS </materia><dia>jueves</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Carmen Vaca</profesor><ubicacion>BLOQUE 16C</ubicacion></horario></horarios>");
   
   horario.set(3,"<horarios><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>viernes</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-01</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>miercoles</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-03</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2198</codigoparalelo><materia>INGENIER�A DE SOFTWARE II</materia><dia>lunes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM3</aula><profesor>Monica Villavicencio</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2198</codigoparalelo><materia>INGENIER�A DE SOFTWARE II</materia><dia>miercoles</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM3</aula><profesor>Monica Villavicencio</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2306</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>viernes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>323</paralelo><aula>IC-11</aula><profesor>Jenny Villarreal</profesor><ubicacion>BLOQUE 32C</ubicacion></horario><horario><codigoparalelo>2306</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>lunes</dia><horaini>13</horaini><minutoini>0</minutoini><horafin>15</horafin><minutofin>0</minutofin><paralelo>323</paralelo><aula>IC-11</aula><profesor>Jenny Villarreal</profesor><ubicacion>BLOQUE 32C</ubicacion></horario><horario><codigoparalelo>2306</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>miercoles</dia><horaini>12</horaini><minutoini>0</minutoini><horafin>14</horafin><minutofin>0</minutofin><paralelo>323</paralelo><aula>IC-11</aula><profesor>Jenny Villarreal</profesor><ubicacion>BLOQUE 32C</ubicacion></horario><horario><codigoparalelo>2056</codigoparalelo><materia>INTERACCI�N HOMBRE M�QUINA</materia><dia>jueves</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-01</aula><profesor>Katherine Chiluiza</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2056</codigoparalelo><materia>INTERACCI�N HOMBRE M�QUINA</materia><dia>martes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-01</aula><profesor>Katherine Chiluiza</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2292</codigoparalelo><materia>SISTEMAS OPERATIVOS </materia><dia>martes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Carmen Vaca</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2292</codigoparalelo><materia>SISTEMAS OPERATIVOS </materia><dia>jueves</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Carmen Vaca</profesor><ubicacion>BLOQUE 16C</ubicacion></horario></horarios>");
   
   horario.set(5,"<horarios><horario><codigoparalelo>2320</codigoparalelo><materia>ELECTR�NICA I</materia><dia>martes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>10</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>A-207</aula><profesor>Carlos Salazar</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>2320</codigoparalelo><materia>ELECTR�NICA I</materia><dia>jueves</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>10</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>A-207</aula><profesor>Carlos Salazar</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>viernes</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-01</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>miercoles</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-03</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2219</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>martes</dia><horaini>16</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>8</paralelo><aula>A-204</aula><profesor>Karina Leon</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>2219</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>jueves</dia><horaini>16</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>8</paralelo><aula>A-204</aula><profesor>Karina Leon</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>2219</codigoparalelo><materia>INGL�S B�SICO B</materia><dia>viernes</dia><horaini>16</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>8</paralelo><aula>A-204</aula><profesor>Karina Leon</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>3437</codigoparalelo><materia>LABORATORIO DE REDES EL�CTRICAS</materia><dia>sabado</dia><horaini>8</horaini><minutoini>0</minutoini><horafin>11</horafin><minutofin>0</minutofin><paralelo>16</paralelo><aula>LABORATORIO DE REDES ELECTRICAS</aula><profesor>Holger Cevallos</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2189</codigoparalelo><materia>SISTEMAS DE BASES DE DATOS I</materia><dia>lunes</dia><horaini>19</horaini><minutoini>30</minutoini><horafin>21</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Pedro Echeverria</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2189</codigoparalelo><materia>SISTEMAS DE BASES DE DATOS I</materia><dia>miercoles</dia><horaini>19</horaini><minutoini>30</minutoini><horafin>21</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Pedro Echeverria</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>3127</codigoparalelo><materia>SISTEMAS DIGITALES I </materia><dia>viernes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>3</paralelo><aula>A-113</aula><profesor>Cesar Martin</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>3127</codigoparalelo><materia>SISTEMAS DIGITALES I </materia><dia>miercoles</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>3</paralelo><aula>A-113</aula><profesor>Cesar Martin</profesor><ubicacion>BLOQUE 24A</ubicacion></horario></horarios>");
   
   horario.set(2,"<horarios><horario><codigoparalelo>3420</codigoparalelo><materia>LAB.QU�MICA GENERAL I</materia><dia>lunes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>11</paralelo><aula>27-LQ1A</aula><profesor>Efrain Lindao</profesor><ubicacion>BLOQUE 27A</ubicacion></horario><horario><codigoparalelo>3330</codigoparalelo><materia>PRODUCCI�N AUDIOVISUAL</materia><dia>martes</dia><horaini>7</horaini><minutoini>0</minutoini><horafin>9</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>G307</aula><profesor>Edgar Freire</profesor><ubicacion>BLOQUE G</ubicacion></horario><horario><codigoparalelo>3330</codigoparalelo><materia>PRODUCCI�N AUDIOVISUAL</materia><dia>jueves</dia><horaini>7</horaini><minutoini>0</minutoini><horafin>9</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>G307</aula><profesor>Edgar Freire</profesor><ubicacion>BLOQUE G</ubicacion></horario><horario><codigoparalelo>3247</codigoparalelo><materia>QU�MICA GENERAL I (B)</materia><dia>miercoles</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>16</paralelo><aula>BA17</aula><profesor>Oswaldo Valle</profesor><ubicacion>BLOQUE 32A</ubicacion></horario><horario><codigoparalelo>3247</codigoparalelo><materia>QU�MICA GENERAL I (B)</materia><dia>viernes</dia><horaini>12</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>16</paralelo><aula>BA17</aula><profesor>Oswaldo Valle</profesor><ubicacion>BLOQUE 32A</ubicacion></horario><horario><codigoparalelo>3676</codigoparalelo><materia>T�C.EXP.ORAL ESCRITA E INVESTIGACI�N (B)</materia><dia>lunes</dia><horaini>13</horaini><minutoini>30</minutoini><horafin>15</horafin><minutofin>30</minutofin><paralelo>32</paralelo><aula>IE-22</aula><profesor>Zoila Palacios</profesor><ubicacion>BLOQUE 32E</ubicacion></horario><horario><codigoparalelo>3676</codigoparalelo><materia>T�C.EXP.ORAL ESCRITA E INVESTIGACI�N (B)</materia><dia>miercoles</dia><horaini>13</horaini><minutoini>30</minutoini><horafin>15</horafin><minutofin>30</minutofin><paralelo>32</paralelo><aula>IE-22</aula><profesor>Zoila Palacios</profesor><ubicacion>BLOQUE 32E</ubicacion></horario></horarios>");
   
   horario.set(4,"<horarios><horario><codigoparalelo>2867</codigoparalelo><materia>F�SICA D</materia><dia>lunes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>4</paralelo><aula>BA28</aula><profesor>Ronald Rovira</profesor><ubicacion>BLOQUE 32A</ubicacion></horario><horario><codigoparalelo>2867</codigoparalelo><materia>F�SICA D</materia><dia>jueves</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>4</paralelo><aula>BA28</aula><profesor>Ronald Rovira</profesor><ubicacion>BLOQUE 32A</ubicacion></horario><horario><codigoparalelo>3249</codigoparalelo><materia>LAB.F�SICA D</materia><dia>martes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>4</paralelo><aula>27-1D</aula><profesor>Ronald Rovira</profesor><ubicacion>BLOQUE 27A</ubicacion></horario><horario><codigoparalelo>2953</codigoparalelo><materia>MANTENIMIENTO INDUSTRIAL</materia><dia>miercoles</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>24C-204</aula><profesor>Angel Vargas</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2953</codigoparalelo><materia>MANTENIMIENTO INDUSTRIAL</materia><dia>lunes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>24C-102</aula><profesor>Angel Vargas</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2955</codigoparalelo><materia>MEC�NICA DE MAQUINARIA I</materia><dia>lunes</dia><horaini>18</horaini><minutoini>0</minutoini><horafin>20</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>AULA DE MATERIALES</aula><profesor>Renato Parodi</profesor><ubicacion>BLOQUE 18B</ubicacion></horario><horario><codigoparalelo>2955</codigoparalelo><materia>MEC�NICA DE MAQUINARIA I</materia><dia>miercoles</dia><horaini>18</horaini><minutoini>0</minutoini><horafin>20</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>AULA DE MATERIALES</aula><profesor>Renato Parodi</profesor><ubicacion>BLOQUE 18B</ubicacion></horario><horario><codigoparalelo>3484</codigoparalelo><materia>MEC�NICA DE S�LIDOS I</materia><dia>lunes</dia><horaini>20</horaini><minutoini>0</minutoini><horafin>22</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>24C-106</aula><profesor>Jorge Felix</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>3484</codigoparalelo><materia>MEC�NICA DE S�LIDOS I</materia><dia>miercoles</dia><horaini>20</horaini><minutoini>0</minutoini><horafin>22</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>24C-106</aula><profesor>Jorge Felix</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2971</codigoparalelo><materia>MOTORES DE COMBUSTI�N INTERNA</materia><dia>lunes</dia><horaini>16</horaini><minutoini>0</minutoini><horafin>18</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>24C-101</aula><profesor>Luis Tinoco</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2971</codigoparalelo><materia>MOTORES DE COMBUSTI�N INTERNA</materia><dia>jueves</dia><horaini>18</horaini><minutoini>0</minutoini><horafin>20</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>24C-102</aula><profesor>Luis Tinoco</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2633</codigoparalelo><materia>TALLER B�SICO (B)</materia><dia>lunes</dia><horaini>14</horaini><minutoini>0</minutoini><horafin>15</horafin><minutofin>0</minutofin><paralelo>1</paralelo><aula>24C-205</aula><profesor>Hugo Zabala</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2634</codigoparalelo><materia>TALLER B�SICO (B)</materia><dia>lunes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>LABORATORIO PRACTICAS 1</aula><profesor>Hugo Zabala</profesor><ubicacion>BLOQUE 18B</ubicacion></horario><horario><codigoparalelo>2963</codigoparalelo><materia>TRANSFERENCIA DE CALOR II               </materia><dia>martes</dia><horaini>14</horaini><minutoini>0</minutoini><horafin>16</horafin><minutofin>0</minutofin><paralelo>2</paralelo><aula>24C-102</aula><profesor>Jorge Duque</profesor><ubicacion>BLOQUE 24C</ubicacion></horario><horario><codigoparalelo>2963</codigoparalelo><materia>TRANSFERENCIA DE CALOR II               </materia><dia>jueves</dia><horaini>14</horaini><minutoini>0</minutoini><horafin>16</horafin><minutofin>0</minutofin><paralelo>2</paralelo><aula>24C-102</aula><profesor>Jorge Duque</profesor><ubicacion>BLOQUE 24C</ubicacion></horario></horarios>");
   
   horario.set(6,"<horarios><horario><codigoparalelo>2195</codigoparalelo><materia>ELECTR�NICA M�DICA </materia><dia>miercoles</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>2</paralelo><aula>LABORATORIO DE ELECTRONICA MEDICA</aula><profesor>Miguel Yapur</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2195</codigoparalelo><materia>ELECTR�NICA M�DICA </materia><dia>viernes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>2</paralelo><aula>LABORATORIO DE ELECTRONICA MEDICA</aula><profesor>Miguel Yapur</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>3072</codigoparalelo><materia>ESTRUCTURAS DE DATOS</materia><dia>martes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Maria Macias</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>3072</codigoparalelo><materia>ESTRUCTURAS DE DATOS</materia><dia>jueves</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Maria Macias</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>2503</codigoparalelo><materia>LABORATORIO DE TELECOMUNICACIONES</materia><dia>jueves</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>12</horafin><minutofin>30</minutofin><paralelo>3</paralelo><aula>LABORATORIO DE TELECOMUNICACIONES</aula><profesor>Rebeca Estrada</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2882</codigoparalelo><materia>PROBLEMAS ESPECIALES DE INGENIER�A EN TELECOMUNICA</materia><dia>lunes</dia><horaini>11</horaini><minutoini>30</minutoini><horafin>13</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-05</aula><profesor>Boris Ramos</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>2882</codigoparalelo><materia>PROBLEMAS ESPECIALES DE INGENIER�A EN TELECOMUNICA</materia><dia>viernes</dia><horaini>14</horaini><minutoini>30</minutoini><horafin>16</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>15A-05</aula><profesor>Boris Ramos</profesor><ubicacion>BLOQUE 15A</ubicacion></horario></horarios>");
   
   horario.set(7,"<horarios><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>viernes</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-01</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3853</codigoparalelo><materia>EMPRENDIMIENTO E INNOVACI�N TECNOL�GICA</materia><dia>miercoles</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>48</paralelo><aula>15A-03</aula><profesor>Victor Bastidas</profesor><ubicacion>BLOQUE 15A</ubicacion></horario><horario><codigoparalelo>3072</codigoparalelo><materia>ESTRUCTURAS DE DATOS</materia><dia>martes</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>17</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Maria Macias</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>3072</codigoparalelo><materia>ESTRUCTURAS DE DATOS</materia><dia>jueves</dia><horaini>15</horaini><minutoini>30</minutoini><horafin>18</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>COM4</aula><profesor>Maria Macias</profesor><ubicacion>BLOQUE 16C</ubicacion></horario><horario><codigoparalelo>3411</codigoparalelo><materia>EXPRESI�N GR�FICA (B)</materia><dia>lunes</dia><horaini>17</horaini><minutoini>30</minutoini><horafin>19</horafin><minutofin>30</minutofin><paralelo>4</paralelo><aula>EB-21</aula><profesor>Robert Toledo</profesor><ubicacion>BLOQUE 32B</ubicacion></horario><horario><codigoparalelo>3411</codigoparalelo><materia>EXPRESI�N GR�FICA (B)</materia><dia>miercoles</dia><horaini>16</horaini><minutoini>30</minutoini><horafin>19</horafin><minutofin>30</minutofin><paralelo>4</paralelo><aula>EB-21</aula><profesor>Robert Toledo</profesor><ubicacion>BLOQUE 32B</ubicacion></horario><horario><codigoparalelo>3620</codigoparalelo><materia>MACROECONOM�A</materia><dia>martes</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>33</paralelo><aula>IB-28</aula><profesor>William Arias</profesor><ubicacion>BLOQUE 32B</ubicacion></horario><horario><codigoparalelo>3620</codigoparalelo><materia>MACROECONOM�A</materia><dia>jueves</dia><horaini>9</horaini><minutoini>30</minutoini><horafin>11</horafin><minutofin>30</minutofin><paralelo>33</paralelo><aula>IB-28</aula><profesor>William Arias</profesor><ubicacion>BLOQUE 32B</ubicacion></horario><horario><codigoparalelo>2341</codigoparalelo><materia>MAQUINARIA EL�CTRICA Y TRANSFORMADORES</materia><dia>jueves</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>9</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>A-113</aula><profesor>Juan Gallo</profesor><ubicacion>BLOQUE 24A</ubicacion></horario><horario><codigoparalelo>2341</codigoparalelo><materia>MAQUINARIA EL�CTRICA Y TRANSFORMADORES</materia><dia>lunes</dia><horaini>7</horaini><minutoini>30</minutoini><horafin>10</horafin><minutofin>30</minutofin><paralelo>1</paralelo><aula>A-113</aula><profesor>Juan Gallo</profesor><ubicacion>BLOQUE 24A</ubicacion></horario></horarios>");
   
   this.chargeSchedule(horario.get(counter));
   
   counter++;
   if(counter > 7)
    counter = 1;
   
  }
});