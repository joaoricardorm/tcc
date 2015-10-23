<?php 
  $someJSON = $_GET['dados']; 

  // Convert JSON string to Object
  $someObject = json_decode($someJSON);
  foreach ($someObject->data as $item){
	  echo 'Id '.$item->idParticipante . ' Nome:'.$item->nome.'<br>';
  }
  //echo $someObject->data[5]->nome; // Access Object data
?>