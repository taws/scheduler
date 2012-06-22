var GO = {
  ini: 'undefined',
  end: 'undefined',
  creator: 'Allan Avendano',
  days: [
    {id: 1, value: 'lunes'     ,day: 'lunes',            column: 'A'},
    {id: 2, value: 'martes'    ,day: 'martes',           column: 'B'},
    {id: 3, value: 'miercoles' ,day: 'mi&eacute;rcoles', column: 'C'},
    {id: 4, value: 'jueves'    ,day: 'jueves',           column: 'D'},
    {id: 5, value: 'viernes'   ,day: 'viernes',          column: 'E'},
    {id: 6, value: 'sabado'    ,day: 's&aacute;bado',    column: 'F'},
    {id: 7, value: 'domingo'   ,day: 'domingo',          column: 'G'}
  ],
  scripts: [
    {src: '/js/operations.js'},
    {src: '/js/subject.js'},
    {src: '/js/schedule.js'}
  ],
  options: {
      id: 'grid-div',
      initHour:    7, 
      initMinute: 30,
      endHour:    22, 
      endMinute:   0,
      initDay:     1, 
      endDay:      6,
      editable:     true,
      width:  '700px',
      height: '575px',
      empty: ''
  }
};

var Console = {
  activated: false,
  show: function(){
    var console = $('console');
    
    if(console == null)
      console = new Element('div',{id: 'console'});
      
    if(!this.activated) {
      console.addClassName('show');
      
      if(console.hasClassName('hide'))
        console.removeClassName('hide');
      
      var arr  = $(GO.options.id).ancestors();
      arr[arr.length-2].insert(console);
      this.activated = true;
    } 
  },
  hide: function(){
    if(this.activated) {
      var console = $('console');
      console.removeClassName('show');
      console.addClassName('hide');
      this.activated = false;
    }
  },
  clear: function(){
    var console = $('console');
    if(console != null) {
      console.update();
    }
  },
  write: function(text){
    if(this.activated) {
      var label = new Element('label').update(text+'<br>');
      $('console').insert(label);
    }
  }
};

var Grid = Class.create({
  initialize: function(options){
      
    this.options = GO.options
    Object.extend(this.options, options);
    GO.options = this.options;
		
    this.evaluate();
  },
  evaluate: function(){
    
    if(GO.options.initHour > GO.options.endHour) {
      Console.show();
      Console.write('Can\'t construct your widget!');
      Console.write('Please, Modify your options');
      throw $break;
    }
    
    
    if((GO.options.initMinute != 30 && GO.options.initMinute != 0) ||
       (GO.options.endMinute  != 30  &&  GO.options.endMinute != 0)) {
      Console.show();
      Console.write('Can\'t construct your widget!');
      Console.write('Please, Modify your options');
      
      throw $break;
    }
  
    this.construct(this.options);
  },
  senseHour: function(y) {
      
      new PeriodicalExecuter(function(pe) {
        var day = new Date();
        var nowHour = day.getHours();   
        
        $$('.hours').each(function(y){
            var hour = y.readAttribute('label');
            
            if(hour != nowHour)  y.removeClassName('nowHour');
            else                 y.addClassName('nowHour');
            
        },{hour: nowHour});
        
        if(y.init) {
            pe.stop();
        } 
        
    }, y.period);
    
  },
  timeFormatHour: function(y) {
    var time = parseInt(y,10);
    if(time < 12) 
        return y+" AM";
    else if (time > 12)
        return (time - 12)+" PM";
    return y+" PM"
  }, 
  formatHour: function(y) {
      var iniHour = y.split(" - ")[0];
      iniHour = iniHour.split(":")[0];
      return iniHour;
  },
  getHour: function(y){
  
    var initHour = GO.options.initHour;
    var initMinute = GO.options.initMinute;
    var endHour;
    var endMinute;
    
    if(initMinute == 30) {
      endHour =  initHour + 1;
      
      endMinute  = '00';
    } else {
      endHour = initHour;
      
      initMinute += '0';
      endMinute  = 30;
    }
        
    var hourInitTrue = (y+initHour-(y/2) - 0.5);
    var hourEndTrue  = (y+endHour-(y/2) - 0.5);
    
    //Add zeros to hour before 10
    if(hourInitTrue < 10) hourInitTrue = '0'+hourInitTrue;
    if(hourEndTrue < 10) hourEndTrue   = '0'+hourEndTrue;
    
    var byTrue  = hourInitTrue +':'+initMinute+' - '+ hourEndTrue +':'+endMinute ;
    
    var hourInitFalse = (y+(endHour-1)-(y/2));
    var hourEndFalse  = (y+initHour-(y/2));
    
    if(hourInitFalse < 10) hourInitFalse = '0'+hourInitFalse;
    if(hourEndFalse  < 10) hourEndFalse  = '0'+hourEndFalse;
    
    var byFalse = hourInitFalse +':'+endMinute +' - '+ hourEndFalse +':'+initMinute;
    
    return (y%2==1)? byTrue : byFalse;
  },
  getDayValue: function(obj){
  
    var result = {
      id:    obj.id,
      value: obj.value, 
      day:   'undefined'
    };
    
    GO.days.find(function(obj){
        if(!Object.isUndefined(obj)){
          if(obj.id == this.result.id || obj.value == this.result.value) {
            this.result.day = obj;
            throw $break;
          }
        }
    },{result:result});
    
    return result.day;
  },
  construct: function(options) {
    
    //Adding extra-scripts
    GO.scripts.each(function(s){
      var script = new Element('script',{src: s.src});
      //$(this.id).insert(script);
	  $('container-div').insert(script);
    },{id: options.id});
	
	var main_div = new Element('div',{id: options.id});
	$('container-div').insert(main_div);
  
    var table = new Element('table',{id:'grid-table', cellspacing: '1'});
    //table.setStyle({tableLayout:'fixed'});
    
    $(options.id).insert(table);
    $(options.id).setStyle({width: options.width});
    $(options.id).setStyle({height: options.height});
    
    //THEAD
    var thead = new Element('thead',{id: 'grid-table-head'});
    table.insert(thead);
    
    //TD INITIAL
    var trHead = new Element('tr');
    var tdIni = new Element('td',{id:'tdIni'});
    var labelIni = new Element('label').update('\t\t\t\t');
    tdIni.insert(labelIni);
    trHead.insert(tdIni);
    thead.insert(trHead);
    
    tdIni.observe('click',function(event){
      //Grid.restoreAll();
      //Subject.prueba();
    });
    
    //TD DAYS
    GO.days.each(function(obj){
      if(obj.id <= this.endDay) {
          
        var tdDay = new Element('td');
        tdDay.addClassName('title');
        
        var labelDay = new Element('label');
        
        labelDay.update(" "+obj.day.capitalize());
        
        var today = new Date();
        var dd = today.getDate();
        var nd = today.getDay();
        
        var nRealDay = dd+(obj.id-nd);
        var labelNDay = new Element('label');
        
        labelNDay.update(nRealDay);
        labelNDay.addClassName('number-day');
        
        
        tdDay.insert(labelNDay);
        tdDay.insert(labelDay);
        
        if(obj.id == nd)
            tdDay.addClassName('today');
        
        trHead.insert(tdDay);
      }
    },{endDay: this.options.endDay});
    
    //TBODY
    var tbody = new Element('tbody',{id:'grid-table-body'});
    table.insert(tbody);
  
    //TR's BODY
    var extraROWS = (GO.options.initMinute == GO.options.endMinute)?0:1;
    
    var rows = 2 * (GO.options.endHour - GO.options.initHour) + extraROWS;
    
    $R(1,rows).each(function(y){
      var trBody = new Element('tr');
      
      trBody.addClassName((y%2 == 0)?'odd':'even');
      
      var hour = this.getHour(y);
      
      
            
      var tdHour = new Element('td',{index:y, hour: hour});
      tdHour.addClassName('hours');
      var labelHour = new Element('label');
      var textLabelHour = this.formatHour(hour);
      labelHour.update( this.timeFormatHour(textLabelHour) );
      tdHour.insert(labelHour);
      
      
      if(y%2 != 0) {
          tdHour.writeAttribute('rowspan',2);
          tdHour.writeAttribute('label',parseInt(textLabelHour,10));
      }
      else         tdHour.addClassName('hide');
      
      trBody.insert(tdHour);
      
      GO.days.each(function(obj){
        if(obj.id <= this.endDay) {
          
          var tdCell = new Element('td',{id: obj.column+y,rowspan: '1',row: y, column: obj.column});
          tdCell.addClassName('unselected');
		  tdCell.setStyle({overflow:'hidden'});
          tdCell.observe('click',function(event){
            event.element().fire("widget:click", {column: obj.column, row: y});
          });
          
          tdCell.observe('dblclick',function(event){
            event.element().fire("widget:dblclick", {column: obj.column, row: y});
          });
      
          var today = new Date();
          var nd = today.getDay();
          if(obj.id == nd)
            tdCell.addClassName('today');
          
          trBody.insert(tdCell);
        }
      },{y:y, days: this.days, endDay: this.endDay});
      
      tbody.insert(trBody);
    
    },{days: GO.days, endDay: this.options.endDay, 
        getHour: this.getHour, formatHour: this.formatHour, timeFormatHour: this.timeFormatHour});

    
    this.senseHour({period:1, init: true});
    this.senseHour({period:10, init: false});
    
    
  }  
});

//Process for select multiple cells
document.observe("widget:click", function(event) {
  
  var cell = $(event.memo.column+event.memo.row);
  
  if(cell.hasClassName('selected')) {
    
	if(GO.ini != 'undefined')
      GO.ini.removeClassName('initial');
      
    GO.ini = 'undefined';
    GO.end = 'undefined';
    
    return;
  } 

  if(GO.options.editable){
    if(cell.hasClassName('unselected')) {

        if(GO.ini == 'undefined') {

          GO.ini = cell;
          GO.ini.addClassName('initial');

        } else {

          GO.end = cell;
          GO.ini.removeClassName('initial');
          Grid.select(GO.ini,GO.end);
          saveFreeTime(GO.ini);

          GO.ini = 'undefined';
          GO.end = 'undefined';

        }
      }
  }
  
  
});

//Restore content for a specific cell
document.observe("widget:dblclick", function(event) {
  var cell = $(event.memo.column+event.memo.row);
  
  if(!cell.hasClassName('selected')){
    var className = cell.readAttribute('class');

    /*
    Disable remove subjects
    $A($$('.'+className)).each(function(cell){
        Grid.restore(cell);
    });*/
    
  } else {
	//Xallam.5/5: Just TD that fire dblclick event is attended
	if(event.target.nodeName == 'TD') {
                deleteFreeTime(cell);
                cell.writeAttribute('dbid',0);
                Grid.restore(cell);
                //sent a "delete msg"
        }
  }
});

//Edit in-place
document.observe("widget:edit-text", function(event) {
	
	//Edit content for assistant-classes
	Grid.editContentCell(event.memo.id);
});

document.observe("widget:release-text",function(event){
	
	//Release content for assistant-classes
	Grid.releaseContentCell(event.memo.id);
});

document.observe("widget:lesson-over", function(event) {
    
    var lesson = $(event.memo.id).readAttribute('class');
    
    $$('.'+lesson).each(function(y){
         $(y.id).toggleClassName('highlighted');
         
//         new Effect.Highlight($(y.id), {
//            startcolor: '#ff9900',
//            duration: 2,
//            afterFinish: function() { 
//                    $(y.id).setStyle({ backgroundColor: null, backgroundImage: null }) 
//                }
//         });
         
    });
    
});

document.observe("widget:lesson-out", function(event) {
    
    $(event.memo.id).toggleClassName('highlighted');
    
    var lesson = $(event.memo.id).readAttribute('class');
    
    $$('.'+lesson).each(function(y){
        if($(y.id).hasClassName('highlighted'))
         $(y.id).toggleClassName('highlighted');
    });
});
