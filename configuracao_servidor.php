<?php
/**
 * Sistema desenvolvido por Jo�o Ricardo Alves de Paula
 * apresentado como trabalho de conclus�o de curso � FAROL - Faculdade de Rolim de Moura
 * em dezembro de 2015
 * contato atrav�s do e-mail joaoricardo.rm@gmail.com
*/

////CONFIGURA��O DO SERVIDOR////

$banco_de_dados = 'tcc'; //nome do banco de dados 
$usuario = 'USUARIO_BD'; //usu�rio do banco de dados
$senha = 'SENHA_BD'; //senha do banco de dados
$pasta_upload = '/home/public_html/tcc/'; //pasta do servidor para realiza��o do upload (envio) dos arquivos de formul�rio

//configura��es adicionais
$servidor_banco_de_dados = 'localhost:3306'; //servidor e porta do banco de dados, caso esteja em outro servidor
$codificacao_dos_caracteres = 'utf8'; //codifica��o dos caracteres do banco de dados, caso aconte�a algum problema com a exibi��o do conte�do
$fuso_horario = 'America/Sao_Paulo' //fuso hor�rio do servidor
?>