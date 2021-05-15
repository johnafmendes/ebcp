<?php
	include("../view/template.class.php");
	include("../model/dao/LinguaConcursoDAO.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/LinguaDAO.php");
 	include("../model/entidade/LinguaEstrangeira.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idlinguaestrangeira']) ? $idLinguaEstrangeira = $_GET['idlinguaestrangeira'] : $idLinguaEstrangeira = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;

	$linguaEstrangeira = new LinguaEstrangeira();
	/*dados do formulário*/
	isset($_POST['idlinguaestrangeira']) ? ($_POST['idlinguaestrangeira'] == "" ? $linguaEstrangeira->setidlinguaestrangeira(null) : $linguaEstrangeira->setidlinguaestrangeira($_POST['idlinguaestrangeira'])) : $linguaEstrangeira->setidlinguaestrangeira(null);
	isset($_POST['idconcurso']) ? $linguaEstrangeira->setidconcurso($_POST['idconcurso']) : $linguaEstrangeira->setidconcurso(null);
	isset($_POST['idlingua']) ? $linguaEstrangeira->setidlingua($_POST['idlingua']) : $linguaEstrangeira->setidlingua(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$linguaEstrangeiraDAO = new LinguaConcursoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Relacionar Língua a Concurso";
					$conteudo = montaFormularioCadastroLingua(null);
					$resultadoPesquisa = $linguaEstrangeiraDAO->listarLinguasEstrangeirasPorConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaLingua($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_lingua_estrangeira" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Relacionamento Língua Estrangeira";
					$lingua = $linguaEstrangeiraDAO->getLinguaEstrangeiraPorID($idLinguaEstrangeira)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroLingua($lingua);
					$resultadoPesquisa = $linguaEstrangeiraDAO->listarLinguasEstrangeirasPorConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaLingua($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Relacionamento Língua Estrangeira";
					$conteudo = "Relacionamento Língua Estrangeira NÃO foi Excluído!";
					if($linguaEstrangeiraDAO->excluirPorID($idLinguaEstrangeira)){
						$conteudo = "Relacionamento Língua Estrangeira Excluído com Sucesso!";
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
				if($linguaEstrangeira->getidlinguaestrangeira() == null){//inserir
					if($linguaEstrangeiraDAO->salvar($linguaEstrangeira)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($linguaEstrangeiraDAO->update($linguaEstrangeira)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_lingua_concurso&acao=exibir_lingua_estrangeira&idlinguaestrangeira=" . $resultado->id_lingua_estrangeira . "\">" . $resultado->id_lingua_estrangeira . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_lingua_concurso&acao=exibir_lingua_estrangeira&idlinguaestrangeira=" . $resultado->id_lingua_estrangeira . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . $resultado->lingua . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_lingua_concurso&acao=excluir&idlinguaestrangeira=" . $resultado->id_lingua_estrangeira . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
	
	function montaFormularioPesquisaLingua($resultadoPesquisa, $idConcurso){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Cargos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				<tr>
					<td align="left">Concurso:</td>
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_lingua_concurso&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesConcursos
						</select>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Concurso</td>
					<td align="left">Língua</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function opcoesLinguas($idLingua){
		$linguaDAO = new LinguaDAO();
		$listaLinguas = $linguaDAO->listarLinguas();
		
		$str = "";
		
		if($listaLinguas != null){
			while($lingua = $listaLinguas->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $lingua->id_lingua . "\"" . ($lingua->id_lingua == $idLingua ? "selected=\"selected\"" : "") . ">" . $lingua->lingua . "</option>";
			}
		}
		return $str;
	}

	function montaFormularioCadastroLingua($lingua){
		$opcoesConcursos = opcoesConcursos($lingua->id_concurso);
		$opcoesLinguas = opcoesLinguas($lingua->id_lingua);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_lingua_concurso&acao=salvar" >
		<input type="hidden" name="idlinguaestrangeira" value="$lingua->id_lingua_estrangeira">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$lingua->id_lingua_estrangeira" disabled="disabled"></td>
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
				<td align="left">* Língua:</td>
				<td align="left"><select name="idlingua" id="validar" title="Língua" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesLinguas
					</select>
				</td>
			</tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_lingua_concurso&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>