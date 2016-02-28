/***********************************************
* Textarea Maxlength script- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

function ismaxlength(obj){
	var mlength=obj.getAttribute?parseInt(obj.getAttribute("maxlength")):""
	if (obj.getAttribute && obj.value.length>mlength)
		obj.value=obj.value.substring(0,mlength)
}

function isNumber(field,decimal){
	//ensure only numbers are accepted
	var check=true;
	var value = field.value;
	var valueLength = field.value.length;
	for(var i=0;i<valueLength;++i) {
		var new_key=value.charAt(i);
		if(decimal==1) {
			if(((new_key<"0") || (new_key>"9")) && !(new_key=="") && !(new_key==".")) {
				check=false;
				break;
			}
		} else if(decimal==0){
			if(((new_key<"0") || (new_key>"9")) && !(new_key=="")) {
				check=false;
				break;
			}
		}
	}
	if(!check) {
		//field.style.backgroundColor="red";
		//alert('Numbers Only!');
		//remove the last submitted character
		var submission = value.substring(0,(valueLength-1));
		field.value=submission;
	} else {
		//field.style.backgroundColor="white";
	}
}

function validateEnvelopeNumber4(field,tableID) {
	//ensure the 9th and 11th characters are /
	var check=true;
	var clear=true;
	var val = field.value;
	var valueLength = val.length;
	for(var i=0;i<valueLength;++i) {
		var new_key=val.charAt(i);
		if(((new_key<"0") || (new_key>"9")) && !(new_key=="/")) {
			clear=false;
			break;
		}
	}

	if(valueLength>8) {
		var key9=val.charAt(8);
		if(key9!="/") {
			check=false;
		}
	}
	
	if(valueLength>10) {
		var key11=val.charAt(10);
		if(key11!="/") {
			check=false;
		}
	}

	if(!check) {
		field.style.backgroundColor="red";
		field.style.color="white";
		document.getElementById(tableID).innerHTML="Incorrect Permit Number. '/' is expected at positions 9 and 11";
	} else {
		field.style.backgroundColor="white";
		field.style.color="#666666";
		document.getElementById(tableID).innerHTML="";
	}

	if(!clear) {
		var submission = val.substring(0,(valueLength-1));
		field.value=submission;
	}
}