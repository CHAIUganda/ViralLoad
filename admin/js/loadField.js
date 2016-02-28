//load text area
function loadTextarea(fieldName,fieldID,info) {
	document.getElementById(fieldID).innerHTML="<textarea name=\""+fieldName+"\" id=\""+fieldName+"\" rows=\"4\" cols=\"35\" class=\"searchLarge\">"+info+"</textarea>";
}

//load input
function loadInput(fieldName,fieldID,info) {
	document.getElementById(fieldID).innerHTML="<input name=\""+fieldName+"\" type=\"text\" id=\""+fieldName+"\" size=\"25\" maxlength=\"200\" class=\"search\" value=\""+info+"\">";
}

//clear input
function clearInput(fieldID) {
	document.getElementById(fieldID).innerHTML="";
}