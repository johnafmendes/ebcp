<?php
	include("view/template.class.php");
	include("controller/coluna_lateral.php");
	
	$menu = new Template("view/menu.tpl");
	$menu->set("", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */

	$conteudo = <<<FORM
	<form name="formCPF" class="login-form" method="post" action="?nivel=areacandidato/painel_candidato&acao=verificar_cpf" >
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left"><strong>CPF:</strong></td>
				<td align="left"><input name="cpf" id="cpf" title="cpf" type="text" size="55" maxlength="11" onkeypress="somenteNumeros();"></td>
			</tr>
		</table>
		<div class="rodapeBotoes">
			<input type="submit" name="submit" value="verificar" class="button" onclick="return ValidarCPF(formCPF.cpf);">
		</div>
	</form>
FORM;
	
	$corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", "Cadastro Pessoal");
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