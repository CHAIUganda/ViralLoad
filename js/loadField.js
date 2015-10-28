//load text area
function loadTextarea(fieldName,fieldID,info) {
	document.getElementById(fieldID).innerHTML="<textarea name=\""+fieldName+"\" id=\""+fieldName+"\" rows=\"4\" cols=\"35\" class=\"inputLarge\">"+info+"</textarea>";
}

//load input
function loadInput(fieldName,fieldID,info) {
	document.getElementById(fieldID).innerHTML="<input name=\""+fieldName+"\" type=\"text\" id=\""+fieldName+"\" size=\"25\" maxlength=\"200\" class=\"search_pre\" value=\""+info+"\">";
}

//load input with HiddenField
function loadInputWithHiddenField(fieldName,fieldID,info,hiddenFieldName,hiddenFieldID,hiddenFieldValue) {
	var output = "<input name=\""+fieldName+"\" type=\"text\" id=\""+fieldName+"\" size=\"25\" maxlength=\"200\" class=\"search_pre\" value=\""+info+"\">";
	output += "<input name=\""+hiddenFieldName+"\" id=\""+hiddenFieldID+"\" type=\"hidden\" value=\""+hiddenFieldValue+"\">";
	document.getElementById(fieldID).innerHTML=output;
}