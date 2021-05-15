<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("concursos", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */

	$concursoDAO = new ConcursoDAO();
	
	$tipo = (isset($_GET['tipo']))? $_GET['tipo'] : "inscricoes-abertas";
	
	switch($tipo){
		case "inscricoes-abertas":
			$titulo = "Concursos - Inscrições Abertas";
			$listaConcursos = $concursoDAO->listarConcursosAbertos(); 
			break;
		case "em-andamento":
			$titulo = "Concursos - Em Andamento";
			$listaConcursos = $concursoDAO->listarConcursosAndamento();
			break;
		case "encerrados":
			$titulo = "Concursos - Encerrados";
			$listaConcursos = $concursoDAO->listarConcursosEncerrados();
			break;
		case "proximos-concursos":
			$titulo = "Próximos Concursos";
			$listaConcursos = $concursoDAO->listarConcursosFuturos();
			break;
		case "vestibulares":
			$titulo = "Vestibulares";
			$listaConcursos = $concursoDAO->listarConcursosVestibulares();
			break;
		case "residencias-medicas":
			$titulo = "Residências Médicas";
			$listaConcursos = $concursoDAO->listarConcursosResMedica();
			break;
		case "avaliacoes-educacionais":
			$titulo = "Avaliações Educacionais";
			$listaConcursos = $concursoDAO->listarConcursosAvalEducacional();
			break;
		case "selecoes-privadas":
			$titulo = "Seleções Privadas";
			$listaConcursos = $concursoDAO->listarConcursosSelPrivada();
			break;
		default:
			$titulo = "Concursos - Inscrições Abertas";
			$listaConcursos = $concursoDAO->listarConcursosAbertos();
			break;
	}
		
	$conteudo = "<table>";
	if($listaConcursos != null) {
		while ($concurso = $listaConcursos->fetch(PDO::FETCH_OBJ)){
			$conteudo .= "<tr><td><div class=\"tituloConcurso\">
					<a href=\"?nivel=concurso&idconcurso=" . $concurso->id_concurso . "\">" 
					. $concurso->titulo . "</a><br/><div class=\"subtituloConcurso\">" 
					. $concurso->subtitulo . "</div></div></td></tr>"; 
		}
	} else {
		$conteudo .= "<tr><td><div class=\"tituloConcurso\">Nenhum Concurso</div></td></tr>";
	}
	
	$conteudo .= "</table>";
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
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