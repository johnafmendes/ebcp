<?php
	include("../view/template.class.php");
	include("../model/dao/UsuarioInstituicaoDAO.php");
 	include("../model/dao/InstituicaoDAO.php");
 	include("../model/entidade/UsuarioInstituicao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idusuarioinstituicao']) ? $idUsuario = $_GET['idusuarioinstituicao'] : $idUsuario = 0;
	isset($_POST['nomecodigo']) ? $nomecodigo = $_POST['nomecodigo'] : $nomecodigo = "";
	isset($_POST['nomeoucodigo']) ? $nomeoucodigo = $_POST['nomeoucodigo'] : $nomeoucodigo = "";
	isset($_POST['mantersenha']) ? $manterSenha = $_POST['mantersenha'] : $manterSenha = "";
	

	$usuarioInstituicao = new UsuarioInstituicao();
	/*dados do formulário*/
	isset($_POST['idusuarioinstituicao']) ? ($_POST['idusuarioinstituicao'] == "" ? $usuarioInstituicao->setidusuarioinstituicao(null) : $usuarioInstituicao->setidusuarioinstituicao($_POST['idusuarioinstituicao'])) : $usuarioInstituicao->setidusuarioinstituicao(null);
	isset($_POST['nome']) ? $usuarioInstituicao->setnome($_POST['nome']) : $usuarioInstituicao->setnome(null);
	isset($_POST['email']) ? $usuarioInstituicao->setemail($_POST['email']) : $usuarioInstituicao->setemail(null);
	isset($_POST['login']) ? $usuarioInstituicao->setlogin($_POST['login']) : $usuarioInstituicao->setlogin(null);
	isset($_POST['senha']) ? $usuarioInstituicao->setsenha($_POST['senha']) : $usuarioInstituicao->setsenha(null);
	isset($_POST['ativo']) ? $usuarioInstituicao->setativo($_POST['ativo']) : $usuarioInstituicao->setativo(null);
	isset($_POST['idinstituicao']) ? $usuarioInstituicao->setidinstituicao($_POST['idinstituicao']) : $usuarioInstituicao->setidinstituicao(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$usuarioInstituicaoDAO = new UsuarioInstituicaoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Novo Usuario Instituição";
					$conteudo = montaFormularioCadastroUsuario(null);
					if($nomeoucodigo == 1){//por codigo
						$resultadoPesquisa = $usuarioInstituicaoDAO->getUsuarioPorID($nomecodigo);
					} else { //por titulo
						$resultadoPesquisa = $usuarioInstituicaoDAO->getUsuarioPorNome($nomecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaUsuario($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_usuario" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Usuario Instituição";
					$usuario = $usuarioInstituicaoDAO->getUsuarioPorID($idUsuario)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroUsuario($usuario);
					if($nomeoucodigo == 1){//por codigo
						$resultadoPesquisa = $usuarioInstituicaoDAO->getUsuarioPorID($nomecodigo);
					} else { //por titulo
						$resultadoPesquisa = $usuarioInstituicaoDAO->getUsuarioPorNome($nomecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaUsuario($resultadoPesquisa);
			
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
					if($usuarioInstituicaoDAO->excluirPorID($idUsuario)){
						$conteudo = "Usuário Exclui com Sucesso!";
					}
					
					$conteudoPesquisa = "";
						
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
				if($usuarioInstituicao->getidusuarioinstituicao() == null){//inserir
					$usuarioInstituicao->setsenha(md5($usuarioInstituicao->getsenha()));
					if($usuarioInstituicaoDAO->salvar($usuarioInstituicao)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					$manterSenha != 1 ? $usuarioInstituicao->setsenha(md5($usuarioInstituicao->getsenha())) : "";
					if($usuarioInstituicaoDAO->update($usuarioInstituicao)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				}
				$titulo = "Resultado do Salvamento";
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
						. "<td align=\"left\"><a href=\"?nivel=painel_usuario_instituicao&acao=exibir_usuario&idusuarioinstituicao=" . $resultado->id_usuario_instituicao . "\">" . $resultado->id_usuario_instituicao . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_usuario_instituicao&acao=exibir_usuario&idusuarioinstituicao=" . $resultado->id_usuario_instituicao . "\">" . $resultado->nome . "</a></td>"
						. "<td align=\"left\">" . $resultado->email . "</td>"
						. "<td align=\"center\">" . $resultado->login . "</td>"
						. "<td align=\"center\">" . $resultado->instituicao . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_usuario_instituicao&acao=excluir&idusuarioinstituicao=" . $resultado->id_usuario_instituicao . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
		
			<form name="formPesquisa" method="post" action="?nivel=painel_usuario_instituicao&acao=exibir_cadastro" >
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
					<td align="center">Instituição</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function opcoesInstituicao($idInstituicao){
		$str = "";
		$instituicaoDAO = new InstituicaoDAO();
		$listaInstituicoes = $instituicaoDAO->listarInstituicoes();
		if($listaInstituicoes != null){
			while($instituicao = $listaInstituicoes->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $instituicao->id_instituicao . "\"" . ($idInstituicao == $instituicao->id_instituicao ? "selected=\"selected\"" : "") . ">" . $instituicao->instituicao . "</option>";
			}
		}
		return $str;
	}
	
	
	function opcaoManterSenha($usuario){
		return $usuario != null ? "<tr>"
			. "<td align=\"left\">Manter Senha:</td>"
			. "<td align=\"left\"><input type=\"checkbox\" value=\"1\" name=\"mantersenha\" title=\"Manter Senha\"></td>"
			. "</tr>" : "";
	}
	
	function montaFormularioCadastroUsuario($usuario){
		$opcoesInstituicoes = opcoesInstituicao($usuario->id_instituicao);
		$opcaoManterSenha = opcaoManterSenha($usuario);
		$opcaoAtivo = $usuario->ativo == 1 ? "checked" : "";
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_usuario_instituicao&acao=salvar" >
		<input type="hidden" name="idusuarioinstituicao" value="$usuario->id_usuario_instituicao">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$usuario->id_usuario_instituicao" disabled="disabled"></td>
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
				<td width="150" align="left">* Instituição: </td>
				<td align="left"><select name="idinstituicao" required title="Instituição" id="validar" style="width:400px;" >
							$opcoesInstituicoes
						</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_usuario_instituicao&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroUsuario();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>