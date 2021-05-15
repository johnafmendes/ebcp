<?php
	include("../view/template.class.php");
	include("../model/dao/CidadeDAO.php");
	include("../model/dao/EstadoDAO.php");
 	include("../model/entidade/Cidade.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idcidade']) ? $idCidade = $_GET['idcidade'] : $idCidade = 0;
	isset($_POST['cidadecodigo']) ? $cidadecodigo = $_POST['cidadecodigo'] : $cidadecodigo = "";
	isset($_POST['cidadeoucodigo']) ? $cidadeoucodigo = $_POST['cidadeoucodigo'] : $cidadeoucodigo = "";

	$cidade = new Cidade();
	/*dados do formulário*/
	isset($_POST['idcidade']) ? ($_POST['idcidade'] == "" ? $cidade->setidcidade(null) : $cidade->setidcidade($_POST['idcidade'])) : $cidade->setidcidade(null);
	isset($_POST['cidade']) ? $cidade->setcidade($_POST['cidade']) : $cidade->setcidade(null);
	isset($_POST['idestado']) ? $cidade->setidestado($_POST['idestado']) : $cidade->setidestado(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	$conteudoPesquisa = "";
	
	$cidadeDAO = new CidadeDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Cidade";
					$conteudo = montaFormularioCadastroCidade(null);
					if($cidadeoucodigo == 1){//por codigo
						$resultadoPesquisa = $cidadeDAO->getCidadePorID($cidadecodigo);
					} else { //por titulo
						$resultadoPesquisa = $cidadeDAO->getCidadePorTitulo($cidadecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaCidade($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_cidade" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Cidade";
					$cidade = $cidadeDAO->getCidadePorID($idCidade)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroCidade($cidade);
					if($cidadeoucodigo == 1){//por codigo
						$resultadoPesquisa = $cidadeDAO->getCidadePorID($cidadecodigo);
					} else { //por titulo
						$resultadoPesquisa = $cidadeDAO->getCidadePorTitulo($cidadecodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaCidade($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Cidade";
					$conteudo = "Cidade NÃO foi Excluído!";
					if($cidadeDAO->excluirPorID($idCidade)){
						$conteudo = "Cidade Excluído com Sucesso!";
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
				if($cidade->getidcidade() == null){//inserir
					if($cidadeDAO->salvar($cidade)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($cidadeDAO->update($cidade)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				}
				$titulo = "Resultado do Salvamento";
				$tituloLateral = "Ajuda";
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
						. "<td align=\"left\"><a href=\"?nivel=painel_cidade&acao=exibir_cidade&idcidade=" . $resultado->id_cidade . "\">" . $resultado->id_cidade . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_cidade&acao=exibir_cidade&idcidade=" . $resultado->id_cidade . "\">" . $resultado->cidade . "</a></td>"
						. "<td align=\"left\">" . $resultado->nome . ", " . $resultado->sigla . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_cidade&acao=excluir&idcidade=" . $resultado->id_cidade . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaCidade($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Cidades</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_cidade&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="cidadeoucodigo" value="0">Cidade <input type="radio" name="cidadeoucodigo" value="1">Código</td>
						<td align="left"><input name="cidadecodigo" required title="Cidade ou Código" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Cidade</td>
					<td align="left">Estado</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function opcoesEstado($idEstado){
		$str = "";
		$estadoDAO = new EstadoDAO();
		$listaEstados = $estadoDAO->listarEstados();
		if($listaEstados != null){
			while($estado = $listaEstados->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $estado->id_estado . "\"" . ($idEstado == $estado->id_estado ? "selected=\"selected\"" : "") . ">" . $estado->nome . ", " . $estado->sigla . "</option>";
			}
		}
		return $str;
	}
	
	function montaFormularioCadastroCidade($cidade){
		$opcoesEstado = opcoesEstado($cidade->id_estado);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_cidade&acao=salvar" >
		<input type="hidden" name="idcidade" value="$cidade->id_cidade">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$cidade->id_cidade" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Cidade:</td>
				<td align="left"><input name="cidade" required title="Cidade" type="text" size="55" value="$cidade->cidade" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Estado:</td>
				<td align="left"><select name="idestado" title="Estado" required style="width:400px;" id="validar">
						<option value="">Selecione</option>
						$opcoesEstado
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<button onclick="location.href='?nivel=painel_cidade&acao=exibir_cadastro'" class="button" type="button">Novo</button>
					<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>