<?php
	include("../view/template.class.php");
	include("../model/dao/UsuarioDAO.php");
 	include("../model/entidade/Usuario.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idusuario']) ? $idUsuario = $_GET['idusuario'] : $idUsuario = 0;
	isset($_POST['nomecodigo']) ? $nomecodigo = $_POST['nomecodigo'] : $nomecodigo = "";
	isset($_POST['nomeoucodigo']) ? $nomeoucodigo = $_POST['nomeoucodigo'] : $nomeoucodigo = "";
	isset($_POST['mantersenha']) ? $manterSenha = $_POST['mantersenha'] : $manterSenha = "";
	

	$usuario = new Usuario();
	/*dados do formulário*/
	isset($_POST['idusuario']) ? ($_POST['idusuario'] == "" ? $usuario->setidusuario(null) : $usuario->setidusuario($_POST['idusuario'])) : $usuario->setidusuario(null);
	isset($_POST['nome']) ? $usuario->setnome($_POST['nome']) : $usuario->setnome(null);
	isset($_POST['email']) ? $usuario->setemail($_POST['email']) : $usuario->setemail(null);
	isset($_POST['login']) ? $usuario->setlogin($_POST['login']) : $usuario->setlogin(null);
	isset($_POST['senha']) ? $usuario->setsenha($_POST['senha']) : $usuario->setsenha(null);
	isset($_POST['ativo']) ? $usuario->setativo($_POST['ativo']) : $usuario->setativo(null);
	isset($_POST['nivel']) ? $usuario->setnivelacesso($_POST['nivel']) : $usuario->setnivelacesso(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$usuarioDAO = new UsuarioDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Novo Usuario";
					$conteudo = montaFormularioCadastroUsuario(null);
					if($nomeoucodigo == 1){//por codigo
						$resultadoPesquisa = $usuarioDAO->getUsuarioPorID($nomecodigo);
					} else { //por titulo
						$resultadoPesquisa = $usuarioDAO->getUsuarioPorNome($nomecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaUsuario($resultadoPesquisa);
						
// 					$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 					$tituloLateral = "Ajuda";
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_usuario" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Usuario";
					$usuario = $usuarioDAO->getUsuarioPorID($idUsuario)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroUsuario($usuario);
					if($nomeoucodigo == 1){//por codigo
						$resultadoPesquisa = $usuarioDAO->getUsuarioPorID($nomecodigo);
					} else { //por titulo
						$resultadoPesquisa = $usuarioDAO->getUsuarioPorNome($nomecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaUsuario($resultadoPesquisa);
			
// 					$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 					$tituloLateral = "Ajuda";
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
				break;
				
			case "excluir" :
				if($_SESSION['nivel'] == 1){
					$titulo = $tituloNivelNegado;
					$conteudo = $conteudoNivelNegado;
					$conteudoLateral =  "";
					break;
				}
				
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Resultado Exclusão Usuário";
					$conteudo = "Usuário NÃO foi Excluído!";
					if($usuarioDAO->excluirPorID($idUsuario)){
						$conteudo = "Usuário Exclui com Sucesso!";
					}
					
					$conteudoPesquisa = "";
						
// 					$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 					$tituloLateral = "Ajuda";
					$conteudoLateral =  "";
				}
				break;
					
			case "salvar" :
				if($_SESSION['nivel'] == 1){
					$titulo = $tituloNivelNegado;
					$conteudo = $conteudoNivelNegado;
					$conteudoLateral =  "";
					break;
				}
				
				$conteudoSucesso = "Dados salvo com sucesso.";
				$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				if($usuario->getidusuario() == null){//inserir
					$usuario->setsenha(md5($usuario->getsenha()));
					if($usuarioDAO->salvar($usuario)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					$manterSenha != 1 ? $usuario->setsenha(md5($usuario->getsenha())) : "";
					if($usuarioDAO->update($usuario)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				}
				$titulo = "Resultado do Salvamento";
// 				$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 				$tituloLateral = "Ajuda";
				$conteudoLateral = "";
				$conteudoPesquisa = "";
			break;
	}
		
	isset($_SESSION['autenticadoAdmin']) ? $menu = new Template("../view/admin/menu_autenticado.tpl") : $menu = new Template("../view/comum/menu_sem_autenticacao.tpl"); 
	$menu->set("diversos", "active");
	
	if(isset($_SESSION['autenticadoAdmin'])){
		$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
	}
	
	isset($corpoTemplate) ? $corpo = $corpoTemplate : $corpo = new Template("../view/comum/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", $tituloLateral);
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("areaLogin", $areaLogin);
	$corpo->set("pesquisa", $conteudoPesquisa);
	
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
	
	function opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa){
		$str = "";
		if($resultadoPesquisa != null){
			while ($resultado = $resultadoPesquisa->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
						. "<td align=\"left\"><a href=\"?nivel=painel_usuario&acao=exibir_usuario&idusuario=" . $resultado->id_usuario . "\">" . $resultado->id_usuario . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_usuario&acao=exibir_usuario&idusuario=" . $resultado->id_usuario . "\">" . $resultado->nome . "</a></td>"
						. "<td align=\"left\">" . $resultado->email . "</td>"
						. "<td align=\"center\">" . $resultado->login . "</td>"
						. "<td align=\"center\">" . ($resultado->ativo == 1 ? "Sim" : "Não") . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_usuario&acao=excluir&idusuario=" . $resultado->id_usuario . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaUsuario($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Usuários</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_usuario&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="nomeoucodigo" value="0">Nome <input type="radio" name="nomeoucodigo" value="1">Código</td>
						<td align="left"><input name="nomecodigo" required title="Nome ou Código" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Nome</td>
					<td align="left">e-Mail</td>
					<td align="center">Login</td>
					<td align="center">Ativo</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function opcoesNivelAcesso($nivel){
		if($nivel == null){
			$nivel = 1;
		}
		return "<option value=\"1\"" . (1 == $nivel ? "selected=\"selected\"" : "") . ">1</option>"
			. "<option value=\"2\"" . (2 == $nivel ? "selected=\"selected\"" : "") . ">2</option>"
			. "<option value=\"3\"" . (3 == $nivel ? "selected=\"selected\"" : "") . ">3</option>";
	}
	
	function opcaoManterSenha($usuario){
		return $usuario != null ? "<tr>"
			. "<td align=\"left\">Manter Senha:</td>"
			. "<td align=\"left\"><input type=\"checkbox\" value=\"1\" name=\"mantersenha\" title=\"Manter Senha\"></td>"
			. "</tr>" : "";
	}
	
	function montaFormularioCadastroUsuario($usuario){
		$opcoesNivelAcesso = opcoesNivelAcesso($usuario->nivel_acesso);
		$opcaoManterSenha = opcaoManterSenha($usuario);
		$opcaoAtivo = $usuario->ativo == 1 ? "checked" : "";
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_usuario&acao=salvar" >
		<input type="hidden" name="idusuario" value="$usuario->id_usuario">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$usuario->id_usuario" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Nome:</td>
				<td align="left"><input name="nome" required title="Nome" type="text" size="55" value="$usuario->nome" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* e-Mail:</td>
				<td align="left"><input name="email" required type="text" title="E-Mail" size="55" value="$usuario->email" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Login:</td>
				<td align="left"><input name="login" required type="text" title="Login" size="55" value="$usuario->login" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Senha:</td>
				<td align="left"><input name="senha" type="text" title="Senha" size="20" value=""/></td>
			</tr>
			$opcaoManterSenha
			<tr>
				<td align="left">Ativo:</td>
				<td align="left"><input type="checkbox" value="1" $opcaoAtivo name="ativo" title="Ativo"></td>
			</tr>
			<tr>
				<td width="150" align="left">* Nível de Acesso: </td>
				<td align="left"><select name="nivel" required title="Nível" id="validar">
							$opcoesNivelAcesso
						</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_usuario&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroUsuario();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>