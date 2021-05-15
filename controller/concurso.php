<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$idConcurso = $_GET['idconcurso'];
	
	$menu = new Template("view/menu.tpl");
	$menu->set("concursos", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */
	
	$concursoDAO = new ConcursoDAO();
	
	$stmtConcurso = $concursoDAO->getConcursoPorID($idConcurso);
	
	if($stmtConcurso != null){
		$concurso = $stmtConcurso->fetch(PDO::FETCH_OBJ);
		
		$titulo = $concurso->titulo;
		
		$conteudo = "<table cellspacing=\"10\" border=\"0\" width=\"100%\">"
				. "<tr>"
					. "<td valign=\"top\">"
						. "<img src=\"admin/arquivoslogos/" . $concurso->logo . "\">"
					. "</td>"
					. "<td valign=\"top\">"
						. "<b>" . $concurso->instituicao . "</b><br/><br/>" 
						. $concurso->subtitulo 	. "<br/><br/>"
						. "Período de Inscrições: " . date('d/m/Y',strtotime($concurso->inicio_inscricao)) . " a " . date('d/m/Y',strtotime($concurso->final_inscricao)); 
						//verifica se esta no período de inscrição
						if(date('Y-m-d H:i:s') >= $concurso->inicio_inscricao && date('Y-m-d H:i:s') <= $concurso->final_inscricao){
							$conteudo .= "<br/><br/><b>Inscrições abertas</b> - Acessa a Área do Candidato na coluna a direita e faça sua inscrição.";
	// 							. "<form id=\"form2\" name=\"form2\" method=\"post\" action=\"?nivel=verifica&idconcurso=" . $idConcurso . "\">"
	// 								. "CPF:	<input name=\"cpf\" id=\"cpf\" type=\"text\"  maxlength=\"11\" onkeypress=\"if (event.keyCode &lt; 48 || event.keyCode &gt; 57  ) event.returnValue = false;\"  />"
	// 								. "<input name=\"submit\" type=\"submit\" value=\"Continuar\" class=\"button\" />"
	// 								. "<br /><br />"
	// 							. "</form>";						
						} else if (date('Y-m-d H:i:s') < $concurso->inicio_inscricao ) {
							$conteudo .= "<br/><br/>Inscrição em breve";
						} else {//inscrição encerrada
							$conteudo .= "<br/><br/>Inscrição encerrada";
						}
	
						if(date('Y-m-d H:i:s') <= $concurso->vencimento_boleto){
							$conteudo .= "<br/><br/>Faça a impressão da 2ª via do boleto bancário. Acesse a Área do Candidato na coluna a direita.";
						}
						
						if(date('Y-m-d H:i:s') > $concurso->final_inscricao){
							$conteudo .= "<br/><br/>Para recursos, acesse a Área do Candidato na coluna a direita.";
						}
				
					$conteudo .= "</td>"
				. "</tr>"
				. "<tr>"
					. "<td colspan=\"2\">"
	// 					. "<div class=\"espacoBranco\"></div>"
	// 					. "<div class=\"borda\">"
	        				. "<div class=\"titulo\"><b>Editais</b></div>"
	        				. "<div class=\"corpo\">";
								$editalDAO = new EditalDAO();
								$listaEditais = $editalDAO->getEditaisPorIdConcurso($idConcurso);
								
								if($listaEditais != null) {
									while ($edital = $listaEditais->fetch(PDO::FETCH_OBJ)){
										$conteudo .= "<div class=\"tituloConcurso\">
											<a href=\"admin/arquivoseditais/" . $edital->caminho_arquivo . "\" target=\"_blank\">" . $edital->titulo . "</a>"
											. "</div>";
									}
								} else {
									$conteudo .= "<div class=\"tituloConcurso\">Nenhum edital no momento</div>";
								}
	        				$conteudo .= "</div>"
	//       				. "</div>"
	      				. "<div class=\"espacoBranco\"></div>"
					. "</td>"
				. "</tr>"
				. "</table>";
	} else {
		$conteudo = "Concurso não encontrado.";
		$titulo = "Concurso";
	}
	
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