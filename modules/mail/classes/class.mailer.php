<?
//definition of line break/carriage return
define("LIBR", "\r\n");

class mailer {
	
	var $from_name;
	var $from_mail;
	var $mail_to;
	var $mail_to_array = array();
	var $mail_to_array_send = array();
	var $mail_cc;
	var $mail_bcc;
	
	var $mail_headers;
	var $mail_subject;
	var $mail_body = "";
	
	// boolean is true if all mail(to) adresses are valid
	var $valid_mail_adresses;
	// the unique value for the mail boundry
	var $uid;
	// 3 = normal, 2 = high, 4 = low
	var $mail_priority = 3;
	
	var $att_files = array();
	var $msg = array();
	
	//content type
	var $ContentType;
	
	//time
	var $icurDate;
	var $curTime;
	
	/**
	* functions inside this constructor
	* validation of e-mail adresses
	* setting mail variables
	* setting boolean $valid_mail_adresses
	*/
	function init_mailer($name,$from,$to,$cc="",$bcc="",$subject="",$body="") {
		$this->valid_mail_adresses = true;
		$this->icurDate = gmdate("Y-m-d");
		$this->curTime = gmdate("H:m:s");
		
		if (!$this->check_mail_address($to)) {
			$this->msg[] = "the address '$to' is invalid";
			$this->valid_mail_adresses = false;
		} 
		
		if (!$this->check_mail_address($from)) {
			$this->msg[] = "the address '$from' is invalid";
			$this->valid_mail_adresses = false;
		}
		
		if ($cc != "") {
			if (!$this->check_mail_address($cc)) {
				$this->msg[] = "the address '$cc' is invalid";
				$this->valid_mail_adresses = false;
			} 
		}
		
		if ($bcc != "") {
			if (!$this->check_mail_address($bcc)) {
				$this->msg[] = "the address '$bcc' is invalid";
				$this->valid_mail_adresses = false;
			}
		}
		
		if ($this->valid_mail_adresses) {
			$this->from_name = $this->strip_line_breaks($name);
			$this->from_mail = $this->strip_line_breaks($from);
			$this->mail_to = $this->strip_line_breaks($to);
			$this->mail_cc = $this->strip_line_breaks($cc);
			$this->mail_bcc = $this->strip_line_breaks($bcc);
			$this->mail_subject = $this->strip_line_breaks($subject);
			$this->create_mime_boundry();
			$this->mail_body = $this->create_msg_body($body);
			$this->mail_headers = $this->create_mail_headers();
		} else {
			return;
		}		
	}

	/**
	* validation of e-mail adresses
	* setting mail variables
	* setting boolean $valid_mail_adresses
	*/
	function init_mailer_array($name="",$from,$to=array(),$cc="",$bcc="",$subject="",$body="") {
		$this->valid_mail_adresses = true;
		
		$a=array();
		foreach($to as $a) {
			if (!$this->check_mail_address($a)) {
				$this->msg[] = "the address '$a' is invalid";
				$this->valid_mail_adresses = false;
			}
		}
		
		if (!$this->check_mail_address($from)) {
			$this->msg[] = "the address '$from' is invalid";
			$this->valid_mail_adresses = false;
		}
		
		if ($cc != "") {
			if (!$this->check_mail_address($cc)) {
				$this->msg[] = "the address '$cc' is invalid";
				$this->valid_mail_adresses = false;
			} 
		}
		
		if ($bcc != "") {
			if (!$this->check_mail_address($bcc)) {
				$this->msg[] = "the address '$bcc' is invalid";
				$this->valid_mail_adresses = false;
			}
		}
		
		if ($this->valid_mail_adresses) {
			$this->from_name = $this->strip_line_breaks($name);
			$this->from_mail = $this->strip_line_breaks($from);
			$t=array();
			foreach($to as $t) {
				$this->mail_to_array_send[] = $this->strip_line_breaks($t);
			}
			$this->mail_cc = $this->strip_line_breaks($cc);
			$this->mail_bcc = $this->strip_line_breaks($bcc);
			$this->mail_subject = $this->strip_line_breaks($subject);
			$this->create_mime_boundry();
			$this->mail_body = $this->create_msg_body($body);
			$this->mail_headers = $this->create_mail_headers();
		} else {
			return;
		}		
	}

    /**
     * Sets message type to HTML.  
     * @param bool $bool
     * @return void
     */
    function IsHTML($bool) {
        if($bool == true)
            $this->ContentType = "text/html";
        else
            $this->ContentType = "text/plain";
    }
	
	/**
	* adds addresses into an array
	*/
	function AddAddress($to) {
		$this->mail_to_array[]=$to;
	}

	/**
	* returns an array of addresses
	*/
	function GetAddress() {
		return $this->mail_to_array;
	}

	/**
	* clears the addresses array
	*/
	function ClearAddress() {
		$this->mail_to_array=array();
	}

	function get_msg_str() {
		$messages = "";
		foreach($this->msg as $val) {
			$messages .= $val."<br>\n";
		}
		return $messages;			
	}

	// use this to prent formmail spamming
	function strip_line_breaks($val) {
		$val = preg_replace("/([\r\n])/", "", $val);
		return $val;
	}

	function check_mail_address($mail_address) {
		return true;
		/*
		$pattern = "/^[\w-]+(\.[\w-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i";
		if (preg_match($pattern, $mail_address)) {
			if (function_exists("checkdnsrr")) {
				$parts = explode("@", $mail_address);
				if (checkdnsrr($parts[1], "MX")){
					return true;
				} else {
					return false;
				}
			} else {
				// on windows hosts is only a limited e-mail address validation possible
				return true;
			}
		} else {
			return false;
		}
		*/
	}

	function create_mime_boundry() {
		$this->uid = "_".md5(uniqid(time()));
	}

	function get_file_data($filepath) {
		if (file_exists($filepath)) {
			if (!$str = file_get_contents($filepath)) {
				$this->msg[] = "Error while opening attachment \"".basename($filepath)."\"";
			} else {
				return $str;
			}
		} else {
			$this->msg[] = "Error, the file \"".basename($filepath)."\" does not exist.";
			return;
		}
	}

	// remember "LIBR" is the line break defined in constact above
	function create_msg_body($mail_msg,$cont_tranf_enc="7bit",$enc="iso-8859-1") {
		$str = 0;
		//$str = "--".$this->uid.LIBR;
		//$str .= "Content-type: ".$this->ContentType."; charset=".$enc.LIBR;
		//$str .= "Content-Transfer-Encoding: ".$cont_tranf_enc.LIBR.LIBR;
		$str = trim($mail_msg).LIBR.LIBR;
		return $str;
	}

	function create_mail_headers() {
		if ($this->from_name != "") {
			$headers = "From: ".$this->from_name." <".$this->from_mail.">".LIBR;
			$headers .= "Reply-To: ".$this->from_name." <".$this->from_mail.">".LIBR;
		} else {
			$headers = "From: ".$this->from_mail.LIBR;
			$headers .= "Reply-To: ".$this->from_mail.LIBR;
		}
		if ($this->mail_cc != "") $headers .= "Cc: ".$this->mail_cc.LIBR;
		if ($this->mail_bcc != "") $headers .= "Bcc: ".$this->mail_bcc.LIBR;
		//$headers .= "Content-Type: text/html; charset=iso-8859-1".LIBR;
		$headers .= "MIME-Version: 1.0".LIBR;
		$headers .= "X-Mailer: Attachment Mailer ver. 1.0".LIBR;
		$headers .= "X-Priority: ".$this->mail_priority.LIBR;
		$headers .= "Content-Type: ".(count($this->att_files)>0?"multipart/mixed":$this->ContentType).";".LIBR.chr(9)." boundary=\"".$this->uid."\"".LIBR.LIBR;
		//$headers .= "This is a multi-part message in MIME format.".LIBR.LIBR;
		return $headers;
	}

	// use for $dispo "attachment" or "inline" (f.e. example images inside a html mail
	function create_attachment_part($file, $dispo = "attachment") {
		if (!$this->valid_mail_adresses) return;
		$file_str = $this->get_file_data($file);
		if ($file_str == "") {
			return;
		} else {
			$filename = basename($file);
			require_once("methods/conf_functions_mime.php");
			$file_type = mime_content_type_($file);
			$chunks = chunk_split(base64_encode($file_str));
			$mail_part = "--".$this->uid.LIBR;
			$mail_part .= "Content-type:".$file_type.";".LIBR.chr(9)." name=\"".$filename."\"".LIBR;
			$mail_part .= "Content-Transfer-Encoding: base64".LIBR;
			$mail_part .= "Content-Disposition: ".$dispo.";".chr(9)."filename=\"".$filename."\"".LIBR.LIBR;
			$mail_part .= $chunks;
			$mail_part .= LIBR.LIBR;
			$this->att_files[] = $mail_part;
		}			
	}

	/*
	function Send() {
		//if (!$this->valid_mail_adresses) {
			//return;
		//}
		
		$mail_message = $this->mail_body;

		if (count($this->att_files) > 0) {
			foreach ($this->att_files as $val) {
				$mail_message .= $val;
			}
			$mail_message .= "--".$this->uid."--";
		}
		
		if(mail($this->mail_to,$this->mail_subject,$mail_message,$this->mail_headers)) {
			$this->msg[] = "email succesfully sent to '".$this->mail_to."'";
		} else {
			$this->msg[] = "error in sending email to '".$this->mail_to."'";
		}
	}
	*/

	function Send() {
		/*
		if (!$this->valid_mail_adresses) {
			return;
		}
		*/
		
		$mail_message = $this->mail_body;

		if (count($this->att_files) > 0) {
			foreach ($this->att_files as $val) {
				$mail_message .= $val;
			}
			$mail_message .= "--".$this->uid."--";
		}
		
		if(mail($this->mail_to,$this->mail_subject,$mail_message,$this->mail_headers)) {
			$this->msg[] = "email succesfully sent to '".$this->mail_to."'";
		} else {
			$this->msg[] = "error in sending email to '".$this->mail_to."'";
		}
	}

	/**
	* function to send but with key parameters as arrays
	*/
	function SendArrays() {
		/*
		if (!$this->valid_mail_adresses) {
			return;
		}
		*/
		
		$mail_message = $this->mail_body;

		if (count($this->att_files) > 0) {
			foreach ($this->att_files as $val) {
				$mail_message .= $val;
			}
			$mail_message .= "--".$this->uid."--";
		}
		
		$s=array();
		foreach($this->mail_to_array_send as $s) {
			if(mail($s[$count],$this->mail_subject,$mail_message,$this->mail_headers)) {
				$this->msg[] = "email succesfully sent to '$s[$count]'";
			} else {
				$this->msg[] = "error in sending email to '$s[$count]'";
			}
		}
	}
}	
?>