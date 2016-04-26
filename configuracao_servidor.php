<?php
/**
 * Sistema desenvolvido por João Ricardo Alves de Paula
 * apresentado como trabalho de conclusão de curso à FAROL - Faculdade de Rolim de Moura
 * em dezembro de 2015
 * contato através do e-mail joaoricardo.rm@gmail.com
*/

////CONFIGURAÇÃO DO SERVIDOR////

$banco_de_dados = 'tcc'; //nome do banco de dados 
$usuario = 'USUARIO_BD'; //usuário do banco de dados
$senha = 'SENHA_BD'; //senha do banco de dados
$pasta_upload = '/home/public_html/tcc/'; //pasta do servidor para realização do upload (envio) dos arquivos de formulário

//configurações adicionais
$servidor_banco_de_dados = 'localhost:3306'; //servidor e porta do banco de dados, caso esteja em outro servidor
$codificacao_dos_caracteres = 'utf8'; //codificação dos caracteres do banco de dados, caso aconteça algum problema com a exibição do conteúdo
$fuso_horario = 'America/Sao_Paulo' //fuso horário do servidor
?>