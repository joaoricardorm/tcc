# tcc-joaoricardo
Trabalho de Conclusão de Curso de Sistemas de Informação na FAROL - Faculdade de Rolim de Moura

MANUAL DE INSTALAÇÃO
====================

Como o sistema é baseado em ambiente web, para realizar sua instalação é necessário um profissional qualificado com conhecimento em servidores e bancos de dados. Segue-se os passos do procedimento.

Requisitos mínimos do servidor web para instalação do sistema:

1. PHP 5.4 com as extensões DOM e GD ativadas;

2. MySQL 5.0 com suporte a InnoDB;

3. Apache 2.2.11 com o módulo mod\_rewrite ativado;

4. 500 MB de espaço em disco.

Para a instalação do sistema é necessário realizar o envios das pastas **/phreeze/** e **/tcc/** para a raiz da pasta web do servidor (normalmente são as pastas **/public\_html/**, **/htdocs/** ou **/www/**).

A pastas /**tcc/images/uploads/** e **/tcc/certificados-gerados/** e subpastas precisam ter permissão de leitura e escrita.

Deve-se criar um banco de dados no MySQL e executar dentro dele o comando SQL contido na pasta **/tcc/instalacao/banco\_de\_dados.sql** (é recomendável excluir a pasta **/tcc/instalacao/** do servidor após a instalação do sistema).

Após a criação do banco de dados é necessário alterar as configurações de conexão do banco de dados com o sistema em **/tcc/configuracao\_servidor.php**, esse arquivo também contém outras configurações importantes em relação ao servidor. Todas as configurações estão comentadas para facilitar o entendimento.

Por fim é possível acessar o sistema pela página inicial do site. Para acessar a área restrita o usuário e senha padrão é **admin** / **123**. Após a autenticação pela primeira vez deve-se acessar a página Configurações e inserir os dados da instituição que o utilizará.