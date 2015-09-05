<?php
require('../../extras/Uploader.php');

// Directory where we're storing uploaded images
// Remember to set correct permissions or it won't work
//$upload_dir = dirname(__FILE__) . '/upload_files/';

$upload_dir = $_SERVER['DOCUMENT_ROOT'] . 'tcc/images/uploads/logos/';

$uploader = new FileUpload('uploadfile');

$novo_nome = md5(uniqid(rand(), true)).'.'.$uploader->getExtension();
$uploader->newFileName = $novo_nome;

// Handle the upload
$result = $uploader->handleUpload($upload_dir);

if (!$result) {
  exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
}

require_once($_SERVER['DOCUMENT_ROOT'] . 'tcc/libs/Model/Miniatura.php');		

$pasta = $upload_dir;
$img_name = $novo_nome;
		
//small
miniatura('' ,$img_name, $pasta, $pasta.'small/', 'esticar', 320, 'auto');
//medium
miniatura('' ,$img_name, $pasta, $pasta.'medium/', 'esticar', 720, 'auto');
//large
miniatura('' ,$img_name, $pasta, $pasta.'large/', 'esticar', 1000, 'auto');
		

echo json_encode(array('success' => true, 'img' => $uploader->newFileName));
