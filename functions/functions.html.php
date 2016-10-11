<?php
/**
* 
*/
class MyHTML
{
	private static function _stringifyAttributes($attributes=array()){
		$attributes_string="";
		if(count($attributes)>0){
			foreach($attributes AS $attr => $val ) $attributes_string.=" $attr='$val' ";
		}  
		return $attributes_string;
	}
	
	public static function text($name='',$val='',$attributes=array()){
		$attributes_string=self::_stringifyAttributes($attributes);
		return "<input type='text' name='$name' value='$val' $attributes_string>";
	}

	public static function hidden($name='',$val='',$attributes=array()){
		$attributes_string=self::_stringifyAttributes($attributes);
		return "<input type='hidden' name='$name' value='$val' $attributes_string>";
	}

	public static function select($name='',$selected_val='',$items=array(),$label="select",$attributes=array()){
		$attributes_string=self::_stringifyAttributes($attributes);
		$select_str=" <select name='$name' $attributes_string>";
		if($label!='none') $select_str.="<option value=''>$label</option>";
		foreach ($items as $key => $val) {
			$selected=$selected_val==$key?"selected='true'":"";
			$select_str.="<option $selected value='$key'>$val</option>";
		}
		return $select_str."</select>";
	}

	public static function tabs($nr=0){
		$tabs="";
		while ($nr>0) {
			$tabs.="&nbsp;";
			$nr--;
		}
		return $tabs;
	}
}
?>