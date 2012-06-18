/*
  Proto!MultiSelect 0.2
  - Prototype version required: 6.0
  
  Credits:
  - Idea: Facebook
  - Guillermo Rauch: Original MooTools script
  - Ran Grushkowsky/InteRiders Inc. : Porting into Prototype and further development
  
  Changelog:
  - 0.1: translation of MooTools script
  - 0.2: renamed from Proto!TextboxList to Proto!MultiSelect, added new features/bug fixes
        added feature: support to fetch list on-the-fly using AJAX    Credit: Cheeseroll
        added feature: support for value/caption
        added feature: maximum results to display, when greater displays a scrollbar   Credit: Marcel
        added feature: filter by the beginning of word only or everywhere in the word   Credit: Kiliman
        added feature: shows hand cursor when going over options
        bug fix: the click event stopped working
        bug fix: the cursor does not 'travel' when going up/down the list   Credit: Marcel
*/

/* Copyright: InteRiders <http://interiders.com/> - Distributed under MIT - Keep this message! */
var flag=false;
var sharerequest;
var timer;
var input2;
var FacebookList = Class.create(TextboxList, { 
  
  loptions: $H({    
    autocomplete: {
      'opacity': 1,
      'maxresults': 5,
      'minchars': 3,
      'filter_selected': true
    }
  }),
  
  initialize: function($super,element, autoholder, options, func) {
    $super(element, options);
    this.data = [];
    this.autoholder = $(autoholder).setOpacity(this.loptions.get('autocomplete').opacity); 
    this.autoholder.observe('mouseover',function() {this.curOn = true;}.bind(this)).observe('mouseout',function() {this.curOn = false;}.bind(this));
    this.autoresults = this.autoholder.select('ul').first();
    var children = this.autoresults.select('li');
    children.each(function(el) {this.add({value:el.readAttribute('value'),caption:el.innerHTML});}, this); 
  },
  
  
  autoShow: function(search) {
    
    this.autoholder.setStyle({'display': 'block'});
    this.autoholder.descendants().each(function(e) {e.hide()});
    if(! search || ! search.strip() || (! search.length || search.length < this.loptions.get('autocomplete').minchars)) 
    {
      this.autoholder.select('.default').first().setStyle({'display': 'block'});
      this.resultsshown = false;
    } else {
      this.resultsshown = true;
      this.autoresults.setStyle({'display': 'block'}).update('');
      if (this.options.get('wordMatch'))
        var regexp = new RegExp("(^|\\s)"+search,'i')
      else
        var regexp = new RegExp(search,'i')
      var count = 0;
      
      this.data.filter(function(str) {return str ? regexp.test(str.evalJSON(true).caption) : false;}).each(function(result, ti) {
          
        tlist2.update();
          
	if(this.loptions.get('autocomplete').filter_selected && $F(this.element).include(result.evalJSON(true).value)){
            //nothing here...  
        }else{
            
            if(ti >= this.loptions.get('autocomplete').maxresults) return;
            count++; //this counter must be after the if
            var that = this;
            var el = new Element('li');
            
            el.observe('click',function(e) { 
                e.stop();
                that.autoAdd(this); 
            }).observe('mouseover',function() { 
                that.autoFocus(this);
            }).update('<img src="http://www.academico.espol.edu.ec/imgEstudiante/'+result.evalJSON(true).matricula+'.jpg" style="width:20px; height:20px; padding-right:2px;" alt="" />'+this.autoHighlight(result.evalJSON(true).caption, search));
                    this.autoresults.insert(el);
            el.cacheData('result', result.evalJSON(true));
            if(ti == 0) this.autoFocus(el);
//            if(result.evalJSON(true).caption.include(search))
//                flag=true;
        }
      }, this);
    }
    if (count > this.options.get('results'))
        this.autoresults.setStyle({'height': (this.options.get('results')*33)+'px'});
    else
        this.autoresults.setStyle({'height': (count?(count*33):0)+'px'});
    this.autoresults.setStyle({'height': 'auto'});
    return this;
  },
  
  autoHighlight: function(html, highlight) {
    return html.gsub(new RegExp(highlight,'i'), function(match) {
      return '<em>' + match[0] + '</em>';
    });
  },
  
  autoHide: function() {    
    this.resultsshown = false;
    this.autoholder.hide();    
    return this;
  },
  
  autoFocus: function(el) {
    if(! el) return;
    if(this.autocurrent) this.autocurrent.removeClassName('auto-focus');
    this.autocurrent = el.addClassName('auto-focus');
    return this;
  },
  
  autoMove: function(direction) {    
    if(!this.resultsshown) return;
    this.autoFocus(this.autocurrent[(direction == 'up' ? 'previous' : 'next')]());
    this.autoresults.scrollTop = this.autocurrent.positionedOffset()[1]-this.autocurrent.getHeight();         
    return this;
  },
  
  autoFeed: function(text) {
      
    if (this.data.indexOf(Object.toJSON(text)) == -1)
        this.data.push(Object.toJSON(text));
        
    return this;
  },
  
  autoAdd: function(el) {
    if(!el || ! el.retrieveData('result')) return;
    this.add(el.retrieveData('result'));
    delete this.data[this.data.indexOf(Object.toJSON(el.retrieveData('result')))];
    this.autoHide();
    var input = this.lastinput || this.current.retrieveData('input');
    input.clear().focus();
    return this;
  },
  
  
  createInput: function($super,options) {
    var li = $super(options);
    var input = li.retrieveData('input');
    input.observe('keydown', function(e) {
        this.dosearch = false;
        switch(e.keyCode) {
          case Event.KEY_UP:e.stop();return this.autoMove('up');
          case Event.KEY_DOWN:e.stop();return this.autoMove('down');        
          case Event.KEY_RETURN:
            e.stop();
            if(! this.autocurrent) break;
            this.autoAdd(this.autocurrent);
            this.autocurrent = false;
            this.autoenter = true;
            break;
          case Event.KEY_ESC:
            this.autoHide();
            if(this.current && this.current.retrieveData('input'))
              this.current.retrieveData('input').clear();
            break;
          default:this.dosearch = true;
        }
    }.bind(this));
    input.observe('keyup',function(e) {
        input2=input;
        
        switch(e.keyCode) {
          case Event.KEY_UP: 
          case Event.KEY_DOWN: 
          case Event.KEY_RETURN:
          case Event.KEY_ESC:
            break;              
          default:
                if(!Object.isUndefined(timer)){
                    clearTimeout(timer);
                }
                timer=setTimeout("startRequest(input2)",500);                
        }        
    }.bind(this));
    input.observe(Prototype.Browser.IE ? 'keydown' : 'keypress', function(e) { 
      if(this.autoenter) e.stop();
      this.autoenter = false;
    }.bind(this));
    return li;
  },
  
  createBox: function($super,text, options) {
    var li = $super(text, options);
    li.observe('mouseover',function() { 
        this.addClassName('bit-hover');
    }).observe('mouseout',function() { 
        this.removeClassName('bit-hover') 
    });
    var a = new Element('a', {
      'href': '#',
      'class': 'closebutton'
      }
    );
    a.observe('click',function(e) {
          e.stop();
          if(! this.current) this.focus(this.maininput);
          this.dispose(li);
    }.bind(this));
    li.insert(a).cacheData('text', Object.toJSON(text));
    return li;
  }
  
});

Element.addMethods({
    onBoxDispose: function(item,obj) {obj.autoFeed(item.retrieveData('text').evalJSON(true));},
    onInputFocus: function(el,obj) {obj.autoShow();},    
    onInputBlur: function(el,obj) { 
        obj.lastinput = el;
        if(!obj.curOn) {
            obj.blurhide = obj.autoHide.bind(obj).delay(0.1);
        }
    },
    filter:function(D,E){var C=[];for(var B=0,A=this.length;B<A;B++){if(D.call(E,this[B],B,this)){C.push(this[B]);}}return C;}
});  

function startRequest(input){
    if (!Object.isUndefined(tlist2.options.get('fetchFile')) && input.value.length >= tlist2.loptions.get('autocomplete').minchars) {
        if(!Object.isUndefined(sharerequest)){
            sharerequest.abort();
        }
      sharerequest = new Ajax.Request(tlist2.options.get('fetchFile'), {
        method: "POST",
        parameters: {keyword: input.value},
        onSuccess: function(transport) {
            transport.responseText.evalJSON(true).each(function(t){tlist2.autoFeed(t);}.bind(tlist2));
            tlist2.autoShow(input.value);

            $('loader-search').hide();
        }.bind(tlist2),
        onLoading: function() {

            $('loader-search').show();
        }
      });        
    }
    else
        if(tlist2.dosearch) tlist2.autoShow(input.value);          
};