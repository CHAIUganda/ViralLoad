function m(obj) {
	for(var i = 0; i < obj.length; i++) {
		if(obj.substring(i, i + 1) == ":")
		break;
	}
	return(obj.substring(0, i));
}

function s(obj) {
	for(var i = 0; i < obj.length; i++) {
		if(obj.substring(i, i + 1) == ":")
		break;
	}
	return(obj.substring(i + 1, obj.length));
}

function dis(mins,secs) {
	var disp;
	if(mins <= 9) {
		disp = " 0";
	} else {
		disp = " ";
	}
	disp += mins + ":";
	if(secs <= 9) {
		disp += "0" + secs;
	} else {
		disp += secs;
	}
	return(disp);
}

function redo() {
	secs--;
	if(secs == -1) {
		secs = 59;
		mins--;
	}
	//document.cd.disp.value = dis(mins,secs); // setup additional displays here.
	document.getElementById("countdownID").innerHTML = dis(mins,secs);
	if((mins == 0) && (secs == 0)) {
		//window.alert("Session Timeout.");
		//window.location = "yourpage.htm";
		iDisplayMessage('/logout/instructions/');
	} else {
		cd = setTimeout("redo()",1000);
	}
}
