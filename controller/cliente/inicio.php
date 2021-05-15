<?php
	include("../view/template.class.php");
	include("../controller/cliente/coluna_lateral.php");
	
	$menu = new Template("../view/comum/menu_sem_autenticacao.tpl");
	
	$conteudo = "Acesse nossa área administrativa."; 
	
	$corpo = new Template("../view/comum/inicio.tpl");
	$corpo->set("titulo", "Acesso Restrito");
	$corpo->set("conteudo", $conteudo);
	$corpo->set("areaLogin", $areaLogin);
	
	/**
	 * Loads our layout template, settings its title and content.
	 */
	$layout = new Template("../view/comum/layout.tpl");
	$layout->set("title", "EBCP Concursos");
	$layout->set("menu", $menu->output());
	$layout->set("content", $corpo->output());
	
	/**
	 * Outputs the page with the user's profile.
	 */
	echo $layout->output();
	
?>