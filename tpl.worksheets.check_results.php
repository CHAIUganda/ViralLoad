<?php
//security check
//$GLOBALS['vlDC']=true;
//include "conf.php";

//print_r($_FILES);
$fileName = $_FILES['file']['name'];
$fileType = $_FILES['file']['type'];
$fileError = $_FILES['file']['error'];
//$fileContent = file_get_contents($_FILES['file']['tmp_name']);

if($fileError == UPLOAD_ERR_OK){
  $machine = $_POST['machine'];
  $file = fopen($_FILES['file']['tmp_name'], 'r') or die('cant read file');
  $sample_ids = array();
   if($machine=='roche'){
   	while(($line = fgetcsv($file,0,","))!==FALSE) {
      $sample_id = trim($line[4]);
   		if(preg_match("/(^[0-9]{4,})\/([0-9]{4,})$/",$sample_id)) $sample_ids[] = $sample_id;
      $count += 1;
   	}
   }else{
    $start = 78;
    while(($line = fgets($file))!==FALSE) {
       $data=preg_split("/[\t]+/", trim($line));
       $sample_id = trim($data[1]);
       if(preg_match("/(^[0-9]{4,})\/([0-9]{4,})$/",$sample_id )) $sample_ids[] = $sample_id;
    }

   }

   fclose($file);

   //print_r($sample_ids);
   echo is_unique($sample_ids);
}else{
   switch($fileError){
     case UPLOAD_ERR_INI_SIZE:   
          $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
          break;
     case UPLOAD_ERR_FORM_SIZE:  
          $message = 'Error al intentar subir un archivo que excede el tamaño permitido.';
          break;
     case UPLOAD_ERR_PARTIAL:    
          $message = 'Error: no terminó la acción de subir el archivo.';
          break;
     case UPLOAD_ERR_NO_FILE:    
          $message = 'Error: ningún archivo fue subido.';
          break;
     case UPLOAD_ERR_NO_TMP_DIR: 
          $message = 'Error: servidor no configurado para carga de archivos.';
          break;
     case UPLOAD_ERR_CANT_WRITE: 
          $message= 'Error: posible falla al grabar el archivo.';
          break;
     case  UPLOAD_ERR_EXTENSION: 
          $message = 'Error: carga de archivo no completada.';
          break;
     default: $message = 'Error: carga de archivo no completada.';
              break;
    }
      echo json_encode(array(
               'error' => true,
               'message' => $message
            ));
}


function is_unique( $a = array() ){ 
  $unik = array_unique( $a );
  if(count($a) == count( $unik)){
    return 1;
  }else{
    $r = array_diff_assoc($a, $unik);
    $str = implode("<br>", $r);
    return $str;
  }
}
?>