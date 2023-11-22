function AddToSecondList(){
     var fl = document.getElementById('Scentros');
     var sl = document.getElementById('Sasignados');    
     for (i = 0; i < fl.options.length; i++){
       if(fl.options[i].selected){
         sl.add(fl.options[i],null);
       }
     }
     return true;
   }
   
   function DeleteSecondListItem(){
     var fl = document.getElementById('Scentros');
     var sl = document.getElementById('Sasignados');    
     for (i = 0; i < sl.options.length; i++){
       if(sl.options[i].selected){
         fl.add(sl.options[i],null);
         // O... 
         // sl.remove(sl.options[i]);
       }
     }
     return true;
   }

