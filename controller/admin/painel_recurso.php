<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/TipoRecursoDAO.php");
	include("../model/dao/RecursoDAO.php");
 	include("../model/entidade/Recurso.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idrecurso']) ? $idRecurso = $_GET['idrecurso'] : $idRecurso = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;

	$recurso = new Recurso();
	/*dados do formulário*/
	isset($_POST['idrecurso']) ? ($_POST['idrecurso'] == "" ? $recurso->setidrecurso(null) : $recurso->setidrecurso($_POST['idrecurso'])) : $recurso->setidrecurso(null);
	isset($_POST['idconcurso']) ? $recurso->setidconcurso($_POST['idconcurso']) : $recurso->setidconcurso(null);
	isset($_POST['idtiporecurso']) ? $recurso->setidtiporecurso($_POST['idtiporecurso']) : $recurso->setidtiporecurso(null);
	isset($_POST['iniciorecurso']) ? $recurso->setiniciorecurso($_POST['iniciorecurso']) : $recurso->setiniciorecurso(null);
	isset($_POST['finalrecurso']) ? $recurso->setfinalrecurso($_POST['finalrecurso']) : $recurso->setfinalrecurso(null);
	isset($_POST['horainicio']) ? $recurso->sethorainicio($_POST['horainicio']) : $recurso->sethorainicio(null);
	isset($_POST['horafim']) ? $recurso->sethorafim($_POST['horafim']) : $recurso->sethorafim(null);
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$recursoDAO = new RecursoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Recursos";
					$conteudo = montaFormularioCadastroRecurso(null);
					$resultadoPesquisa = $recursoDAO->listarRecursosPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_recurso" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Recurso";
					$recurso = $recursoDAO->getRecursoPorID($idRecurso)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroRecurso($recurso);
					$resultadoPesquisa = $recursoDAO->listarRecursosPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Recurso";
					$conteudo = "Recurso NÃO foi Excluído!";
					if($recursoDAO->excluirPorID($idRecurso)){
						$conteudo = "Recurso Excluído com Sucesso!";
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
				$recurso->setiniciorecurso(formataData($recurso->getiniciorecurso(), "Y-m-d") . " " . $recurso->gethorainicio());
				$recurso->setfinalrecurso(formataData($recurso->getfinalrecurso(), "Y-m-d") . " " . $recurso->gethorafim());
				
				if($recurso->getidrecurso() == null){//inserir
					if($recursoDAO->salvar($recurso)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($recursoDAO->update($recurso)){
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
	
	function formataData($data, $formato){
		// $datetime is something like: 2014-01-31 13:05:59
		if($data != ""){
			$time = strtotime(str_replace('/', '-', $data));
			return date($formato, $time);
		} else {
			return "";
		}
		// $my_format is something like: 01/31/14 1:05 PM
	}
	
	function opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa){
		$str = "";
		if($resultadoPesquisa != null){
			while ($resultado = $resultadoPesquisa->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
						. "<td align=\"left\"><a href=\"?nivel=painel_recurso&acao=exibir_recurso&idrecurso=" . $resultado->id_recurso . "\">" . $resultado->id_recurso . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_recurso&acao=exibir_recurso&idrecurso=" . $resultado->id_recurso . "\">" . $resultado->tipos_recursos . "</a></td>"
						. "<td align=\"left\">" . formataData($resultado->inicio_recurso, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\">" . formataData($resultado->final_recurso, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_recurso&acao=excluir&idrecurso=" . $resultado->id_recurso . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
	
	function montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Recursos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				<tr>
					<td align="left">Concurso:</td>
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_recurso&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesConcursos
						</select>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Tipo Recurso</td>
					<td align="left">Início Recurso</td>
					<td align="center">Final Recurso</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function opcoesTiposRecurso($idTipo){
		$tipoRecursoDAO = new TipoRecursoDAO();
		$listaTipos = $tipoRecursoDAO->listarTipoRecursos();
		
		$str = "";
		
		if($listaTipos != null){
			while($tipo = $listaTipos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $tipo->id_tipos_recursos . "\"" . ($tipo->id_tipos_recursos == $idTipo ? "selected=\"selected\"" : "") . ">" . $tipo->tipos_recursos . "</option>";
			}
		}
		return $str;
	}

	function montaFormularioCadastroRecurso($recurso){
		$opcoesConcursos = opcoesConcursos($recurso->id_concurso);
		$opcoesTiposRecursos = opcoesTiposRecurso($recurso->id_tipos_recursos);
		$horaInicio = formataData($recurso->inicio_recurso, "H:i:s");
		$horaFim = formataData($recurso->final_recurso, "H:i:s");
		$inicioRecurso = formataData($recurso->inicio_recurso, "d/m/Y");
		$finalRecurso = formataData($recurso->final_recurso, "d/m/Y");
		
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_recurso&acao=salvar" >
		<input type="hidden" name="idrecurso" value="$recurso->id_recurso">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$recurso->id_recurso" disabled="disabled"></td>
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
				<td align="left">* Tipos Recursos:</td>
				<td align="left"><select name="idtiporecurso" id="validar" title="Tipo Recurso" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesTiposRecursos
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Data Início:</td>
				<td align="left"><input name="iniciorecurso" required title="Data Início" type="text" size="20" value="$inicioRecurso" id="validar" maxlength="10" onKeyPress="MascaraData(formCadastro.iniciorecurso);"><input name="horainicio" required type="text" size="10" value="$horaInicio" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horainicio);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">* Data Final:</td>
				<td align="left"><input name="finalrecurso" required title="Data Final" type="text" size="20" value="$finalRecurso" id="validar" maxlength="10" onKeyPress="MascaraData(formCadastro.finalrecurso);"><input name="horafim" required type="text" size="10" value="$horaFim" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horafim);">00:00:00</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_recurso&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>