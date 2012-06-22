Object.extend(Grid,{
  select: function(){
  
    /*
      In order to select multiple cells, 
      the reference is passed as arguments
    */
    var ini = $A(arguments)[0];
    var fin = $A(arguments)[1];
    
    if(ini == null || fin  == null)
      throw $break;
    
    var iniROW =  parseInt(ini.getAttribute('row'),10);
    var finROW =  parseInt(fin.getAttribute('row'),10);
    
    var iniCOL = ini.getAttribute('column');
    var finCOL = fin.getAttribute('column');
	
	if(finROW > iniROW) {
		if(iniCOL == finCOL) {
		  
		  //Verify intermediate cells
		  if(this.hasSelected(iniROW,finROW,iniCOL)) {
			//Console.show();
			
			/*
			Display name of intermediate selected cells that has been selected
			$R(iniROW,finROW).each(function(row){
			  if($(this.col+row).hasClassName('hide'))
				Console.write($(this.col+row).getAttribute('id')+' has been selected before!');
			},{col:iniCOL});*/
			
			//Console.write('Some cells has been selected before between '+(iniCOL+iniROW)+' and '+(iniCOL+finROW)+'<br/>Check it!');
			
			return false;
		  }
		  
		  $R(iniROW+1,finROW).each(function(ROW){
			$(this.COLUMN+ROW).removeClassName('unselected');
                        $(this.COLUMN+ROW).removeClassName('today');
			$(this.COLUMN+ROW).addClassName('hide');
		  },{COLUMN: iniCOL});
		  
                  ini.writeAttribute('rowspan',finROW - iniROW + 1);
		  ini.removeClassName('unselected');
                  ini.removeClassName('today');
		  ini.addClassName('selected');
                  
                  ini.observe('mouseover', function(event){
                      event.element().fire("widget:lesson-over",{ id: ini.getAttribute('id')});
                  });
                  ini.observe('mouseout', function(event){
                      event.element().fire("widget:lesson-out",{ id: ini.getAttribute('id')});
                  });
                  
                  
		  //Edit in-place
		  var labelText = new Element('label',{id: ini.getAttribute('id')+'l',class: 'click-to-edit'});
		  var inputText = new Element('input',{id: ini.getAttribute('id')+'i', type:'text',size:"7",maxlength:"20", height: '50'});
		  
		  labelText.update(GO.options.empty);
		  
		  //This doesn't work at G-Chrome
		  labelText.show();
		  inputText.hide();
		  
		  
		  labelText.observe('click',function(event){
			 event.element().fire("widget:edit-text",{ id: ini.getAttribute('id')});
		  });
		  inputText.observe('keyup',function(event){
			 if(event.keyCode == Event.KEY_RETURN)
				event.element().fire("widget:release-text",{ id: ini.getAttribute('id')});
		  });
		  inputText.observe('blur',function(event){
			event.element().fire("widget:release-text",{ id: ini.getAttribute('id')});
		  });
		  
		  ini.insert(labelText,{position: 'bottom'});
		  ini.insert(inputText,{position: 'bottom'});
		
		  
		  return true;
		} else {
			//2010.23.4: Select time across
			
			$R(iniCOL,finCOL).each(function(COL){
			
				if(this.hasSelected(iniROW,finROW,COL)) {  return false; }
				
				$R(iniROW+1,finROW).each(function(ROW){
					$(this.COLUMN+ROW).removeClassName('unselected');
					$(this.COLUMN+ROW).addClassName('hide');
				},{COLUMN: COL});
				
				iniCellCross = $(COL+iniROW);
				iniCellCross.writeAttribute('rowspan',finROW - iniROW + 1);
				iniCellCross.removeClassName('unselected');
				iniCellCross.addClassName('selected');
				
			},{iniROW: iniROW, finROW: finROW});
			
		}
	}	
  },
  hasSelected: function(xI,xF,y){
    return $R(xI,xF).any(function(x){
      return $(this.y+x).hasClassName('selected') || $(this.y+x).hasClassName('hide');
    },{y: y});
  },
  restore: function(cell){
    if(!cell.hasClassName('unselected')){
      var COL = cell.getAttribute('column');
      var iniROW = parseInt(cell.getAttribute('row'),10);
      var finROW = parseInt(cell.getAttribute('rowspan'),10)-1;
      
      $R(iniROW,iniROW+finROW).each(function(x){
        
        //$(this.COL+x).removeClassName('selected');
        //$(this.COL+x).removeClassName('hide');
        var cell = $(this.COL+x);
        
        $w(cell.readAttribute('class')).each(function(className){
            $(this.COL+this.X).removeClassName(className);
        },{COL:this.COL, X: x});
        
        cell.update();
        cell.addClassName('unselected');
        cell.writeAttribute('rowspan',1);
        Event.stopObserving(cell, 'mouseover', null);
        
      },{COL: COL});
    }
  },
  restoreAll: function(){
    $$('td').each(function(cell){
        this.restore(cell);
    },{restore: this.restore});
  },
  toCells: function(obj){
    
    var day = Grid.prototype.getDayValue(obj);
    if(day != 'undefined') {
      
      $$('td.hours').each(function(cell){
        var hour       = cell.getAttribute('hour');
        var indexMinus =  hour.indexOf('-');
        var indexHour  = hour.indexOf(this.hourExt);
        if(indexHour != -1) {
           
           if(this.obj.isEnd) {
                if(indexHour > indexMinus) {
                  this.obj.result = day.column + cell.getAttribute('index');
                  throw $break;
                }
           } else {
               if(indexHour < indexMinus) {
                  this.obj.result = day.column + cell.getAttribute('index');
                  throw $break;
               }
           }
           
        } else this.obj.result = 'undefined';
      },{hourExt : obj.hour+':'+obj.minute, obj: obj});
    }
  },
  addContentCell: function(){
    var initCell = $($A(arguments)[0]);
    var endCell = $($A(arguments)[1]);
    var content = $A(arguments)[2];
 
    if(initCell == null || endCell == null)
      return false;
 
    if(this.select(initCell,endCell)) {
      initCell.update(content);
      initCell.removeClassName('selected');
      
      return true;
    } 
  },
  //Edit in-place
  editContentCell: function(){
	  
	  var theCell = $($A(arguments)[0]);
	  var labelText = $(theCell.getAttribute('id')+'l');
	  var inputText = $(theCell.getAttribute('id')+'i');

	  labelText.update('');
	  inputText.show();
	  
  },
  releaseContentCell: function(){
	  var theCell = $($A(arguments)[0]);
	  var labelText = $(theCell.getAttribute('id')+'l');
	  var inputText = $(theCell.getAttribute('id')+'i');
	  
	  labelText.update( ($F(inputText) == '')?GO.options.empty:$F(inputText) );
	  inputText.hide();
  },
  fromJson: function(list) {
      

      list.each(function(item) {

        var nstart =  $$('td[class="'+'hours'+'"]').findAll(function(td) {
            return(td.getAttribute("hour").split('-')[0].replace(/^\s*/, "").replace(/\s*$/, "") == item.start_time)
        })[0].getAttribute('index');
        
        var nend   =  $$('td[class="'+'hours'+'"]').findAll(function(td) {
            return(td.getAttribute("hour").split('-')[0].replace(/^\s*/, "").replace(/\s*$/, "") == item.end_time)
        })[0].getAttribute('index') -1;
        
        var column  = GO.days.findAll(function(i){
            return i.id == item.day;
        })[0].column;
        
        
        GO.ini = $$("td[column="+column+"]").findAll(function(td){
            return td.getAttribute('id') == column+nstart;
        })[0];
        GO.end = $$("td[column="+column+"]").findAll(function(td){ 
            return td.getAttribute('id') == column+nend
        })[0];

        Grid.select(GO.ini,GO.end);
        GO.ini.writeAttribute("dbid",item.id);
        
        GO.ini = 'undefined';
        GO.end = 'undefined';

      });

      GO.ini = 'undefined';
      GO.end = 'undefined';
      
  },
  toJson: function(idCounsel,cell) {
        var arreglo = new Array();
        
        var row = cell.getAttribute("row");
        var rowspan = parseInt(cell.getAttribute("rowspan"),10)+ parseInt(row,10)-1;
        var iniHour = $$('td[index="'+row+'"]')[0].getAttribute('hour').split('-')[0].replace(/^\s*/, "").replace(/\s*$/, "");
        var endHour = $$('td[index="'+(rowspan)+'"]')[0].getAttribute('hour').split('-')[1].replace(/^\s*/, "").replace(/\s*$/, "");
        var day = cell.getAttribute("column");
        
        GO.days.each(function(attr1){
            if(attr1.column == day)
                dayName = (attr1.id)
        },{day:day,dayName:null})

        arreglo[0]  =   {counsel_id: idCounsel,  start_time: iniHour, end_time: endHour,  day: dayName};

        return Object.toJSON(arreglo);
  },
  allToJson: function(id) {
        var arreglo = new Array();
        var i = 0;
        
        $$('.selected').each(function(cell){
            var row = cell.getAttribute("row");
            var rowspan = parseInt(cell.getAttribute("rowspan"),10)+ parseInt(row,10)-1;
            var iniHour = $$('td[index="'+row+'"]')[0].getAttribute('hour').split('-')[0].replace(/^\s*/, "").replace(/\s*$/, "");
            var endHour = $$('td[index="'+(rowspan)+'"]')[0].getAttribute('hour').split('-')[1].replace(/^\s*/, "").replace(/\s*$/, "");
            var day = cell.getAttribute("column")
            GO.days.each(function(attr1){
                if(attr1.column == day)
                    dayName = (attr1.id)
            },{day:day,dayName:null})
            arreglo[i]  =   {counsel_id: id,  end_time: endHour,   start_time: iniHour, day: dayName};
            i++;
        })

        return Object.toJSON(arreglo);
  }
});