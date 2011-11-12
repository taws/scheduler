var Scheduler = {
  actual: 1,
  schedule: undefined,
  colors: undefined,
  addToSchedule: function(){
    
    if(Object.isUndefined(this.schedule)) {
      this.schedule = new Hash();
      
      this.colors = new Hash();
      $R(1,12).each(function(x){
        this.colors.set(x,'value'+x);
      },{colors:this.colors});
      
    }
    
    if($A(arguments)[0] == null || $A(arguments)[1] == null)
      throw $break;
    
    if(Object.isUndefined(this.schedule.get($A(arguments)[0]))) {
      
      var className = this.colors.get(this.actual);
      
      this.schedule.set($A(arguments)[0],className);
      
      $($A(arguments)[1]).addClassName(className);
      
      if(this.actual<12)
        this.actual++;
      else this.actual = 1;
    } else {
       var className = this.schedule.get($A(arguments)[0]);
        $($A(arguments)[1]).addClassName(className);
    }
    
  },
  viewAll: function(){
    return this.schedule.inspect();
  }
}