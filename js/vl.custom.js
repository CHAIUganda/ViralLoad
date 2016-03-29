 function removeItem(that){
    var r=confirm("Are you sure?");
    if(r){
      that.parentNode.parentNode.removeChild(that.parentNode);
    }
  }

  function removeItemHTML(){
    return "<label class='rm_item' onClick='removeItem(this)'>X</label>";
  }