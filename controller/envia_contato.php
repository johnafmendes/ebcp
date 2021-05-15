<?php
include("view/template.class.php");
include("controller/coluna_lateral.php");

$concurso=$_POST['concurso'];
$contato=$_POST['nomeContato'];
$tel=$_POST['telefone'];
$email=$_POST['email'];
$desc=$_POST['descricao'];

$menu = new Template("view/menu.tpl");
$menu->set("contato", "active");

/**
 * Creates a new template for the user's profile.
 * Fills it with mockup data just for testing.
*/

$headers = 'From: envia@ebcpconcursos.com.br';
   
$men="Concurso: $concurso \n Contato: $contato \n Tel: $tel \n Email: $email \n Assunto: $desc \n";

if(mail("ebcpconcursos@gmail.com","Orçamento","$men",$headers)) {
	$conteudo = "Solicitação de contato enviado com sucesso.<br/><br/>Entraremos em contato o mais breve possível.<br/><br/>Obrigado.";
} else {
	$conteudo = "Falha ao enviar seu contato. Tente novamente ou ligue-nos.";
}

$corpo = new Template("view/paginaInterna.tpl");
$corpo->set("titulo", "Solicitação de Contato");
$corpo->set("conteudo", $conteudo);
$corpo->set("tituloLateral", "Últimas Notícias");
$corpo->set("conteudoLateral", $conteudoLateral);
$corpo->set("areaCandidato", $areaCandidato);

/**
 * Loads our layout template, settings its title and content.
*/
$layout = new Template("view/layout.tpl");
$layout->set("title", "EBCP Concursos");
$layout->set("menu", $menu->output());
$layout->set("content", $corpo->output());

/**
 * Outputs the page with the user's profile.
*/
echo $layout->output();
?>
