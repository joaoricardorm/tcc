<?php
$html = 'Nick';
 
$html = stripslashes($html); 
// Incluímos a biblioteca DOMPDF
require_once("./dompdf/dompdf_config.inc.php");
 
// Instanciamos a classe
$dompdf = new DOMPDF();
 
// Passamos o conteúdo que será convertido para PDF
$dompdf->load_html('Naik');
 
// Definimos o tamanho do papel e
// sua orientação (retrato ou paisagem)
$dompdf->set_paper('A4','portrait');
 
// O arquivo é convertido
$dompdf->render();
 
// Salvo no diretório temporário do sistema
// e exibido para o usuário
$dompdf->stream("nome-do-arquivo.pdf", array("Attachment" => false));
?>