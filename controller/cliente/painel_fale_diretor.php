<?php
	include("../view/template.class.php");
	include("../model/dao/ConfiguracaoDAO.php");
	include("../model/entidade/Configuracao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";

	isset($_POST['nome']) ? $nome = $_POST['nome'] : "";
	isset($_POST['assunto']) ? $assunto = $_POST['assunto']: "";
	isset($_POST['mensagem']) ? $mensagem = $_POST['mensagem'] : "";
	isset($_POST['emailde']) ? $emailde = $_POST['emailde'] : "";

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	switch ($acao){
		case "exibir_mensagem" :
			if (isset($_SESSION['autenticadoCliente'])){
				$titulo = "Envie Mensagem para o Diretor";
				$conteudo = montaFormularioMensagem();
				
				$conteudoLateral =  "";
			}
			
			break; //fim case exibir_configuracao
			
		case "enviar" :
			if (isset($_SESSION['autenticadoCliente'])){
				$conteudoSucesso = "Mensagem enviada com sucesso.";
				$conteudoErro = "Não foi possível enviar mensagem.<br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				if(enviaEmailMensagemDiretor($assunto, $mensagem, $emailde, $nome)){
					$conteudo = $conteudoSucesso;
				} else {
					$conteudo = $conteudoErro;
				}
				$titulo = "Resultado do Envio da Mensagem";
							
				$conteudoLateral =  "";
			} else {
				//falha na autenticacao
			}
			break;
	}
		
	isset($_SESSION['autenticadoCliente']) ? $menu = new Template("../view/cliente/menu_autenticado.tpl") : $menu = new Template("../view/comum/menu_sem_autenticacao.tpl"); 
	$menu->set("falediretor", "active");
	
	if(isset($_SESSION['autenticadoCliente'])){
		$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
	}
	
	isset($corpoTemplate) ? $corpo = $corpoTemplate : $corpo = new Template("../view/comum/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", $tituloLateral);
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("areaLogin", $areaLogin);
	$corpo->set("pesquisa", "");
	
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

	function enviaEmailMensagemDiretor($assunto, $mensagem, $emailde, $nome){
		$headers  = "MIME-Version: 1.0 \r\n"
				. "Content-type: text/html; charset=utf-8 \r\n"
				. "From: concurso@ebcpconcursos.com.br \r\n";
		$para = "programacao@noroesteconcursos.com.br";
		$mensagem = "e-mail Origem: " . $emailde . "<br/>Nome: "
				. $nome . "<br/><br/>" . $mensagem; 
		if(mail($para, $assunto, $mensagem, $headers)){
			return true;
		} else {
			return false;
		}	
	}
	
	function montaFormularioMensagem(){
		$str = <<<FORM
			<form name="formMensagem" method="post" action="?nivel=painel_fale_diretor&acao=enviar" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">Nome:</td>
						<td align="left"><input type="text" name="nome" value="" size="55"></td>
					</tr>
					<tr>
						<td align="left">e-Mail:</td>
						<td align="left"><input type="text" name="emailde" value="" size="55"></td>
					</tr>
					<tr>
						<td align="left">Assunto:</td>
						<td align="left"><input type="text" name="assunto" value="" size="55"></td>
					</tr>
					<tr>
						<td align="left">Mensagem:</td>
						<td align="left"><textarea name="mensagem" cols="55" rows="8"></textarea></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" class="button" name="submit" value="Confirmo e Envio"></td>
					</tr>
				</table>
			</form>
FORM;

	return $str;		
		
	}
	
?>