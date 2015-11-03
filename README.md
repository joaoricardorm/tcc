# tcc-joaoricardo
Trabalho de Conclusão de Curso de Sistemas de Informação na FAROL - Faculdade de Rolim de Moura

MANUAL DE INSTALAÇÃO
====================

Requisitos mínimos do servidor web para instalação do sistema:

1. PHP 5.4 com a biblioteca GD ativada;

2. MySQL 5.0 com suporte a InnoDB;

3. Apache 2.2.11 com o módulo mod\_rewrite ativado;

4. 500 MB de espaço em disco.

Para a instalação do sistema é necessário realizar o envios das pastas **/phreeze/** e **/tcc/** para a raiz da pasta web do servidor (normalmente são as pastas **/public\_html/**, **/htdocs/** ou **/www/**).

A pastas /**tcc/images/uploads/** e **/tcc/certificados-gerados/** e subpastas precisam ter permissão de leitura e escrita.

Deve-se criar um banco de dados no MySQL e executar dentro dele o comando SQL contido na pasta **/tcc/instalacao/bd.sql** (é recomendável excluir a pasta **/tcc/instalacao/** do servidor após a instalação do sistema).

Após a criação do banco de dados é necessário alterar as configurações de conexão com o banco de dados com o sistema em **/tcc/configuracao\_servidor.php**, esse arquivo também contém outras configurações importantes em relação ao servidor. Todas as configurações estão comentadas para facilitar o entendimento.
