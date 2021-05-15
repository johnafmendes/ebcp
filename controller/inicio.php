<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("index", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */

	$concursoDAO = new ConcursoDAO();
	$listaConcursosAbertos = $concursoDAO->listarConcursosAbertos(); 
	
	$conteudo = "<table>";
	if($listaConcursosAbertos != null) {
		while ($concurso = $listaConcursosAbertos->fetch(PDO::FETCH_OBJ)){
			$conteudo .= "<tr><td><div class=\"tituloConcurso\">
					<a href=\"?nivel=concurso&idconcurso=" . $concurso->id_concurso . "\">" 
					. $concurso->titulo . "</a><br/><div class=\"subtituloConcurso\">" 
					. $concurso->subtitulo . "</div></div></td></tr>"; 
		}
	} else {
		$conteudo .= "<tr><td><div class=\"tituloConcurso\">Nenhum concurso com inscrições abertas</div></td></tr>";
	}
	
	$conteudo .= "</table>";
	
	$concursosDestaque = $concursoDAO->listarConcursosDestaque();
	
	$conteudoScroller = "<ul id=\"scroller\">";
	if($concursosDestaque != null) {
		while ($concurso = $concursosDestaque->fetch(PDO::FETCH_OBJ)){
			$conteudoScroller .= "<li><div><table>"
					. "<tr><td>" . ($concurso->logo != null ? "<img src=\"admin/arquivoslogos/" . $concurso->logo . "\" width=\"100\">" : "<div style=\"width:100px;height:120px;\"></div>") . "</td>"
					. "<td><b>" . $concurso->instituicao . "</b><br/>"
					. "<b><a href=\"?nivel=concurso&idconcurso=" . $concurso->id_concurso . "\">"
					. $concurso->titulo . "</b></a><div class=\"subtituloConcurso\">"
					. $concurso->subtitulo . "</div></td></tr></table></div></li>";
		}
	} else {
		$conteudoScroller .= "<li><div>Nenhum Concurso em Destaque no momento.</div></li>";
	}
	
	$conteudoScroller .= "</ul>";
	
	$corpo = new Template("view/inicio.tpl");
	$corpo->set("titulo", "Inscrições Abertas");
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", "Últimas Notícias");
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("scroller", $conteudoScroller);
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