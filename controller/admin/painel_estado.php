<?php
	include("../view/template.class.php");
	include("../model/dao/EstadoDAO.php");
 	include("../model/entidade/Estado.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idestado']) ? $idEstado = $_GET['idestado'] : $idEstado = 0;

	$estado = new Estado();
	/*dados do formulário*/
	isset($_POST['idestado']) ? ($_POST['idestado'] == "" ? $estado->setidestado(null) : $estado->setidestado($_POST['idestado'])) : $estado->setidestado(null);
	isset($_POST['nome']) ? $estado->setnome($_POST['nome']) : $estado->setnome(null);
	isset($_POST['sigla']) ? $estado->setsigla($_POST['sigla']) : $estado->setsigla(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$estadoDAO = new EstadoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Estados";
					$conteudo = montaFormularioCadastroEstado(null);
					$resultadoPesquisa = $estadoDAO->listarEstados();
					$conteudoPesquisa = montaFormularioPesquisaEstado($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_estado" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Estado";
					$estado = $estadoDAO->getEstadoPorID($idEstado)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroEstado($estado);
					$resultadoPesquisa = $estadoDAO->listarEstados();
					$conteudoPesquisa = montaFormularioPesquisaEstado($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Estado";
					$conteudo = "Estado NÃO foi Excluído!";
					if($estadoDAO->excluirPorID($idEstado)){
						$conteudo = "Estado Excluído com Sucesso!";
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
				if($estado->getidestado() == null){//inserir
					if($estadoDAO->salvar($estado)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($estadoDAO->update($estado)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_estado&acao=exibir_estado&idestado=" . $resultado->id_estado . "\">" . $resultado->id_estado . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_estado&acao=exibir_estado&idestado=" . $resultado->id_estado . "\">" . $resultado->nome . "</a></td>"
						. "<td align=\"center\">" . $resultado->sigla . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_estado&acao=excluir&idestado=" . $resultado->id_estado . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaEstado($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Estados</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Nome</td>
					<td align="center">Sigla</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function montaFormularioCadastroEstado($estado){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_estado&acao=salvar" >
		<input type="hidden" name="idestado" value="$estado->id_estado">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$estado->id_estado" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Nome:</td>
				<td align="left"><input name="nome" required title="Nome" type="text" size="20" value="$estado->nome" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Sigla:</td>
				<td align="left"><input name="sigla" required title="Sigla" type="text" size="20" value="$estado->sigla" id="validar"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_estado&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>