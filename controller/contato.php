<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("contato", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */

	$conteudo = <<<FORM
				<form name="login-form" class="login-form" action="?nivel=envia_contato" id="contato"
					method="post" enctype="multipart/form-data" onSubmit="return validaCamposContato()">  
					<table>
						<tr><td>*Nome:</td>
						<td><input required id="nomeContato" name="nomeContato" tabindex="1" type="text" placeholder="Nome Completo" style="width:400px;" />
						</td></tr>
					
						<tr><td>Concurso:</td>
						<td><input id="concurso" name="concurso" tabindex="2" type="text" placeholder="Concurso" style="width:400px;"/>
						</td></tr>
			
						<tr><td>*Telefone:</td>
						<td><input required id="telefone" name="telefone" tabindex="3" type="text" placeholder="Telefone com DDD" />
						</td></tr>

						<tr><td>*e-Mail:</td>
						<td><input required id="email" name="email" tabindex="4" type="text" placeholder="e-Mail" style="width:400px;"/>
						</td></tr>

						<tr><td>*Descrição:</td>
						<td><textarea required id="descricao" name="descricao" tabindex="5" type="text" placeholder="Descrição" cols="45" rows="5" style="width:400px;"></textarea>
						</td></tr>
					</table>
					<p>*Campos obrigatórios</p>
					<div class="rodapeBotoes">
						<input type="submit" name="submit" value="Enviar" class="button">
		            </div>
	 </form>
FORM;
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", "Entre em Contato com a EBCP");
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