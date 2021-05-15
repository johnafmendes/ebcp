<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("ebcp", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */

	$conteudo = "A EBCP é uma empresa ....";
	
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", "Empresa Brasileira de Concursos Públicos");
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