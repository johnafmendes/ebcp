<?php
	include("view/template.class.php");
	include("controller/coluna_lateral.php");
	include("model/dao/CandidatoDAO.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['cpf']) ? $cpf = $_GET['cpf'] : (isset($_POST['cpf']) ? $cpf = $_POST['cpf'] : $cpf = "");
	isset($_GET['id']) ? $senhaAntiga = $_GET['id'] : $senhaAntiga = "";
	isset($_POST['senha']) ? $senha = $_POST['senha'] : $senha = "";
	isset($_POST['id']) ? $senhaAtual = $_POST['id'] : $senhaAtual = "";
	
	switch ($acao){
		case "salva_nova_senha" : 
			$candidatoDAO = new CandidatoDAO();
			$titulo = "Resultado Alteração Senha";
			if($candidatoDAO->alterarSenha($cpf, $senhaAtual, md5($senha))){
				$conteudo = "Senha alterada com sucesso.";
			} else {
				$conteudo = "Senha NÂO foi alterada.<br/><br/>Tente novamente e caso o erro persista, entre em contato conosco.";
			}
				
			break;
			
		case "form_nova_senha" :
			$titulo = "Solicitação de Criação de Nova Senha de Acesso";
			$conteudo = montaFormularioNovaSenha($cpf, $senhaAntiga);
			break;
			
		case "exibe_formulario" :
				$titulo = "Solicitação de Criação de Nova Senha de Acesso";
				$conteudo = montaFormularioCPF();				
			break;
				
		case "envia_link_nova_senha" :
				$titulo = "Solicitação de Nova Senha";
				
				$candidatoDAO = new CandidatoDAO();
				
				$candidato = $candidatoDAO->getCandidatoPorCPF($cpf)->fetch(PDO::FETCH_OBJ);
	
				if (enviaEmailComLink($candidato)) {
					$conteudo = "E-Mail enviado com sucesso.<br/><br/>Verifique sua caixa de entrada e clique no "
							. "link para criar sua nova senha.";
				} else {
					$conteudo = "Não foi possível enviar e-mail para criação de nova senha. <br/><br/>Por favor, "
							. "tente novamente ou entre em contato conosco.";
				}
			break;
	}
	
	$menu = new Template("view/menu.tpl");
	$menu->set("", "active");
	
	/**
	 * Creates a new template for the user's profile.
	 * Fills it with mockup data just for testing.
	 */
	
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
	
	function enviaEmailComLink($candidato){
		$para = $candidato->email;
		$assunto = "EBCP Concursos - Solicitação de Nova Senha";
		$body = "EBCP Concursos: <br/><br/>Você solicitou a criação de uma nova senha de acesso. Para isso, clique no "
				. "link a seguir e crie sua nova senha.<br/><br/>"
				. "<a href=\"www.ebcpconcursos.com.br?nivel=areacandidato/esqueci_senha&acao=form_nova_senha&cpf=" . $candidato->cpf 
				. "&id=" . $candidato->senha . "\">"
				. "Criar Nova Senha</a>";
		$headers  = "MIME-Version: 1.0 \r\n"
				. "Content-type: text/html; charset=utf-8 \r\n"
				. "From: concurso@ebcpconcursos.com.br \r\n";
		 
		if(mail($para, $assunto, $body, $headers)){
			return true;
		} else {
			return false;
		}
	}
	
	function montaFormularioCPF(){
		$conteudo = <<<FORM
	<form name="formCPF" class="login-form" method="post" action="?nivel=areacandidato/esqueci_senha&acao=envia_link_nova_senha" >
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
		return $conteudo;
	}
	
	function montaFormularioNovaSenha($cpf, $senhaAntiga){
		$conteudo = <<<FORM
	<form name="formSenha" class="login-form" method="post" action="?nivel=areacandidato/esqueci_senha&acao=salva_nova_senha" >
		<input name="cpf" id="cpf" type="hidden" value="$cpf">
		<input name="id" id="id" type="hidden" value="$senhaAntiga">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left"><strong>CPF:</strong></td>
				<td align="left"><input name="cpf1" id="cpf1" type="text" value="$cpf" disabled="disabled" size="20"></td>
			</tr>
			<tr>
				<td align="left"><strong>Senha:</strong></td>
				<td align="left"><input name="senha" id="senha" title="Senha" type="password" size="20"></td>
			</tr>
			<tr>
				<td align="left"></td>
				<td align="left"><input name="senha2" id="senha2" title="Senha" type="password" size="20"></td>
			</tr>
		</table>
		<div class="rodapeBotoes">
			<input type="submit" name="submit" value="Salvar" class="button" onclick="return validarSenha(formSenha.senha, formSenha.senha2);">
		</div>
	</form>
FORM;
		return $conteudo;
	}
?>