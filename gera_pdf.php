<?php
$html = 'Nick';
 
$html = stripslashes($html); 
// Inclu�mos a biblioteca DOMPDF
require_once("./dompdf/dompdf_config.inc.php");
 
// Instanciamos a classe
$dompdf = new DOMPDF();
 
// Passamos o conte�do que ser� convertido para PDF
$dompdf->load_html('Naik');
 
// Definimos o tamanho do papel e
// sua orienta��o (retrato ou paisagem)
$dompdf->set_paper('A4','portrait');
 
// O arquivo � convertido
$dompdf->render();
 
// Salvo no diret�rio tempor�rio do sistema
// e exibido para o usu�rio
$dompdf->stream("nome-do-arquivo.pdf", array("Attachment" => false));
?>