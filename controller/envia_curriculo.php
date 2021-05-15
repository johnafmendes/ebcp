<?php
include("view/template.class.php");
include("model/dao/CurriculoDAO.php");
include("model/entidade/Curriculo.php");
include("controller/coluna_lateral.php");
	
	$curriculo = new Curriculo();
	
	$curriculo->setnome($_POST['nome']);
	$curriculo->setdatanascimento(implode("-",array_reverse(explode("/",$_POST['datanascimento']))));
	$curriculo->setcidade($_POST['cidade']);
	$curriculo->setestado($_POST['estado']);
	$curriculo->settelefone($_POST['telefone']);
	$curriculo->setemail($_POST['email']);
	$curriculo->setminicurriculo($_POST['curriculo']);
	$curriculo->setcargo($_POST['cargo']);
	
	$menu = new Template("view/menu.tpl");
	$menu->set("trabalhe", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */
	
	$curriculoDAO = new CurriculoDAO();
	
	$retorno = $curriculoDAO->salvarCurriculo($curriculo);
	
	if($retorno != null) {
		$conteudo = "Currículo enviado com sucesso.<br/><br/>Obrigado.";	
	} else {
		$conteudo = "Falha ao enviar seu currículo. Tente novamente.";
	}
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", "Envio do Currículo");
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