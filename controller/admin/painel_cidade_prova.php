<?php
	include("../view/template.class.php");
	include("../model/dao/CidadeProvaDAO.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/EstadoDAO.php");
	include("../model/dao/CidadeDAO.php");
 	include("../model/entidade/CidadeProva.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idcidadeprova']) ? $idCidadeProva = $_GET['idcidadeprova'] : $idCidadeProva = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;
	isset($_GET['idestado']) ? $idEstado = $_GET['idestado'] : $idEstado = 0;
	isset($_POST['cidadecodigo']) ? $cidadecodigo = $_POST['cidadecodigo'] : $cidadecodigo = "";
	isset($_POST['cidadeoucodigo']) ? $cidadeoucodigo = $_POST['cidadeoucodigo'] : $cidadeoucodigo = "";

	$cidadeProva = new CidadeProva();
	/*dados do formulário*/
	isset($_POST['idcidadeprova']) ? ($_POST['idcidadeprova'] == "" ? $cidadeProva->setidcidadeprova(null) : $cidadeProva->setidcidadeprova($_POST['idcidadeprova'])) : $cidadeProva->setidcidadeprova(null);
	isset($_POST['idconcurso']) ? $cidadeProva->setidconcurso($_POST['idconcurso']) : $cidadeProva->setidconcurso(null);
	isset($_POST['idcidade']) ? $cidadeProva->setidcidade($_POST['idcidade']) : $cidadeProva->setidcidade(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	$conteudoPesquisa = "";
	
	$cidadeProvaDAO = new CidadeProvaDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Cidade das Provas";
					$conteudo = montaFormularioCadastroCidade(null, $idEstado);

					$resultadoPesquisa = $cidadeProvaDAO->getCidadePorConcurso($idConcurso);

					$conteudoPesquisa = montaFormularioPesquisaCidade($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_cidade" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Cidade da Prova";
					$cidade = $cidadeProvaDAO->getCidadePorID($idCidadeProva)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroCidade($cidade, $idEstado);

					$resultadoPesquisa = $cidadeProvaDAO->getCidadePorID($idCidadeProva);
					
					$conteudoPesquisa = montaFormularioPesquisaCidade($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Cidade da Prova";
					$conteudo = "Cidade da Prova NÃO foi Excluído!";
					if($cidadeProvaDAO->excluirPorID($idCidadeProva)){
						$conteudo = "Cidade da Prova Excluído com Sucesso!";
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
				if($cidadeProva->getidcidadeprova() == null){//inserir
					if($cidadeProvaDAO->salvar($cidadeProva)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($cidadeProvaDAO->update($cidadeProva)){
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
	$menu->set("concursos", "active");
	
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
						. "<td align=\"left\"><a href=\"?nivel=painel_cidade_prova&acao=exibir_cidade&idcidadeprova=" . $resultado->id_cidade_prova . "\">" . $resultado->id_cidade_prova . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_cidade_prova&acao=exibir_cidade&idcidadeprova=" . $resultado->id_cidade_prova . "\">" . $resultado->cidade . "</a></td>"
						. "<td align=\"left\">" . $resultado->titulo . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_cidade_prova&acao=excluir&idcidadeprova=" . $resultado->id_cidade_prova . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function opcoesConcursos($idConcurso){
		$concursoDAO = new ConcursoDAO();
		$listaConcursos = $concursoDAO->listarTodosConcursos();
	
		$str = "";
	
		if($listaConcursos != null){
			while($concurso = $listaConcursos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $concurso->id_concurso . "\"" . ($concurso->id_concurso == $idConcurso ? "selected=\"selected\"" : "") . ">" . $concurso->titulo . "</option>";
			}
		}
		return $str;
	}
	
	function montaFormularioPesquisaCidade($resultadoPesquisa, $idConcurso){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Cidades da Prova</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_cidade&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">Concurso</td>
						<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_cidade_prova&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
							<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Cidade</td>
					<td align="left">Concurso</td>
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
	
	function opcoesCidades($idCidade, $idEstado){
		$str = "";
		$cidadeDAO = new CidadeDAO();
		$listaCidades = $cidadeDAO->listarCidadesPorIdEstado($idEstado);
		if($listaCidades != null){
			while($cidade = $listaCidades->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cidade->id_cidade . "\"" . ($idCidade == $cidade->id_cidade ? "selected=\"selected\"" : "") . ">" . $cidade->cidade . "</option>";
			}
		}
		return $str;
	}
	
	
	function selectNovoAlterar($cidade){
		if($cidade == null){
			$str = "<select name=\"idestado\" title=\"Estado\" required style=\"width:400px;\" id=\"validar\" onchange=\"javascript: window.location.href = '?nivel=painel_cidade_prova&acao=exibir_cadastro&idestado=' + this.value;\" >";
		} else {
			$str = "<select name=\"idestado\" title=\"Estado\" required style=\"width:400px;\" id=\"validar\" onchange=\"javascript: window.location.href = '?nivel=painel_cidade_prova&acao=exibir_cidade&idestado=' + this.value + '&idcidadeprova=$cidade->id_cidade_prova';\" >";
		}
		
		return $str;
	}
	
	function montaFormularioCadastroCidade($cidade, $idEstado){
		$opcoesEstado = opcoesEstado($idEstado == null ? $cidade->id_estado : $idEstado);
		$opcoesConcursos = opcoesConcursos($cidade->id_concurso);
		$opcoesCidades = opcoesCidades($cidade->id_cidade, $idEstado == null ? $cidade->id_estado : $idEstado);
		$selectNovoAlterar = selectNovoAlterar($cidade);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_cidade_prova&acao=salvar" >
		<input type="hidden" name="idcidadeprova" value="$cidade->id_cidade_prova">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$cidade->id_cidade_prova" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Estado:</td>
				<td align="left">$selectNovoAlterar
						<option value="">Selecione</option>
						$opcoesEstado
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Cidade:</td>
				<td align="left"><select name="idcidade" id="validar" title="Cidade" required style="width: 400px;">
							<option value="">selecione</option>
								$opcoesCidades
							</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Concurso:</td>
				<td align="left"><select name="idconcurso" id="validar" title="Concursos" required style="width: 400px;">
							<option value="">selecione</option>
								$opcoesConcursos
							</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<button onclick="location.href='?nivel=painel_cidade_prova&acao=exibir_cadastro'" class="button" type="button">Novo</button>
					<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>