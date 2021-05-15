<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/EditalDAO.php");
	include("../model/dao/TipoRecursoDAO.php");
	include("../model/dao/RecursoCandidatoDAO.php");
 	include("../model/entidade/RecursoCandidato.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idrecursocandidato']) ? $idRecursoCandidato = $_GET['idrecursocandidato'] : $idRecursoCandidato = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;
	isset($_GET['idtiporecurso']) ? $idTipoRecurso = $_GET['idtiporecurso'] : $idTipoRecurso = 0;

	/*paginacao candidato*/
	//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
	
	//seta a quantidade de itens por página, neste caso, 10 itens
	$registros = 20;
	
	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($registros*$pagina)-$registros;
	/*fim paginacao candidato*/
	
	$recursoCandidato = new RecursoCandidato();
	/*dados do formulário*/

	isset($_POST['idrecursocandidato']) ? ($_POST['idrecursocandidato'] == "" ? $recursoCandidato->setidrecursocandidato(null) : $recursoCandidato->setidrecursocandidato($_POST['idrecursocandidato'])) : $recursoCandidato->setidrecursocandidato(null);
	isset($_POST['ideditalresposta']) ? $recursoCandidato->setideditalresposta($_POST['ideditalresposta']) : $recursoCandidato->setideditalresposta(null);
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$recursoCandidatoDAO = new RecursoCandidatoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Gerencia de Recursos de Candidatos";
					$conteudo = montaFormularioCadastroRecurso(null);
					
					//conta o total de itens
					$total = $recursoCandidatoDAO->getTotalRecursosPorIdConcurso($idConcurso, $idTipoRecurso);
						
					//calcula o número de páginas arredondando o resultado para cima
					$numPaginas = ceil($total/$registros);
					
					$resultadoPesquisa = $recursoCandidatoDAO->listarRecursosPorIdConcurso($idConcurso, $idTipoRecurso, $inicio, $registros, null);
					
					$conteudoPesquisa = montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso, $idTipoRecurso, montaPaginacao($numPaginas, $idConcurso, $idTipoRecurso, $pagina));
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_recurso_candidato" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Recurso Candidato";
					$recurso = $recursoCandidatoDAO->getRecursoPorID($idRecursoCandidato)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroRecurso($recurso);

					//conta o total de itens
					$total = $recursoCandidatoDAO->getTotalRecursosPorIdConcurso($idConcurso, $idTipoRecurso);
					
					//calcula o número de páginas arredondando o resultado para cima
					$numPaginas = ceil($total/$registros);
						
					$resultadoPesquisa = $recursoCandidatoDAO->listarRecursosPorIdConcurso($idConcurso, $idTipoRecurso, $inicio, $registros, null);
						
					$conteudoPesquisa = montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso, $idTipoRecurso, montaPaginacao($numPaginas, $idConcurso, $idTipoRecurso, $pagina));
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
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
				
				if($recursoCandidato->getidrecursocandidato() == null){//inserir
					//nunca inclui
				} else { //atualizar
					if($recursoCandidatoDAO->update($recursoCandidato)){
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
	
	function montaPaginacao($numPaginas, $idConcurso, $idTipoRecurso, $pagina){
		$str = "Páginas: ";
		if(isset($numPaginas)){
			for($i = 1; $i < $numPaginas + 1; $i++) {
				if($i != $pagina){
					$str .= "<a href='?nivel=painel_recurso_candidato&acao=exibir_cadastro&idconcurso=" . $idConcurso. "&idtiporecurso=" . $idTipoRecurso. "&pagina=$i'>[".$i."]</a> ";
				} else {
					$str .= "<b>[" . $i . "]</b> ";
				} 
			}
		}
		return $str;
	}
	
	function opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa){
		$str = "";
		if($resultadoPesquisa != null){
			while ($resultado = $resultadoPesquisa->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
						. "<td align=\"left\"><a href=\"?nivel=painel_recurso_candidato&acao=exibir_recurso_candidato&idrecursocandidato=" . $resultado->id_recurso_candidato . "\">" . $resultado->id_recurso_candidato . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_recurso_candidato&acao=exibir_recurso_candidato&idrecursocandidato=" . $resultado->id_recurso_candidato . "\">" . $resultado->tipos_recursos . "</a></td>"
						. "<td align=\"left\">" . formataData($resultado->data_hora_recurso, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\">" . $resultado->arquivo_anexo . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_edital&acao=exibir_edital&idedital=" . $resultado->id_edital_resposta . "\" title=\"" . $resultado->titulo_edital . "\">" . $resultado->id_edital_resposta . "</a></td>"
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
	
	function montaFormularioPesquisaRecurso($resultadoPesquisa, $idConcurso, $idTipoRecurso, $paginacao){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$opcoesTiposRecursos = opcoesTiposRecurso($idTipoRecurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Recursos Candidatos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				<tr>
					<td align="left">Concurso:</td>
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_recurso_candidato&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesConcursos
						</select>
					</td>
				</tr>
				<tr>
					<td align="left">Tipos Recursos:</td>
					<td align="left"><select name="idtiporecurso" id="validar" title="Tipos Recursos" required onchange="javascript: window.location.href = '?nivel=painel_recurso_candidato&acao=exibir_cadastro&idconcurso=$idConcurso&idtiporecurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesTiposRecursos
						</select>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Tipo Recurso</td>
					<td align="left">Data Recurso</td>
					<td align="center">Arquivo</td>
					<td align="center">Edital Resposta</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
			<br/>
			$paginacao
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
	
	function opcoesEditaisResposta($idConcurso, $idEdital){
		$editaisDAO = new EditalDAO();
		$listaEditais = $editaisDAO->getEditaisPorIdConcurso($idConcurso);
	
		$str = "";
	
		if($listaEditais != null){
			while($edital = $listaEditais->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $edital->id_edital . "\"" . ($edital->id_edital == $idEdital ? "selected=\"selected\"" : "") . ">" . $edital->titulo . "</option>";
			}
		}
		return $str;
	}

	function montaFormularioCadastroRecurso($recurso){
		$opcoesConcursos = opcoesConcursos($recurso->id_concurso);
		$opcoesTiposRecursos = opcoesTiposRecurso($recurso->id_tipos_recursos);
		$opcoesEditaisResposta = opcoesEditaisResposta($recurso->id_concurso, $recurso->id_edital_resposta);
		$data = formataData($recurso->data_hora_recurso, "d/m/Y H:i:s");
		
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_recurso_candidato&acao=salvar" >
		<input type="hidden" name="idrecursocandidato" value="$recurso->id_recurso_candidato">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$recurso->id_recurso_candidato" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">Candidato:</td>
				<td align="left"><input name="candidato" title="Candidato" type="text" size="55" value="$recurso->nome" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">Concurso:</td>
				<td align="left"><input name="concurso" title="Concurso" type="text" size="55" value="$recurso->titulo" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">Tipos Recursos:</td>
				<td align="left"><input name="tiporecurso" title="Tipo Recurso" type="text" size="55" value="$recurso->tipos_recursos" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">Data:</td>
				<td align="left"><input name="datahorarecurso" title="Data Recurso" type="text" size="20" value="$data" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">Recurso:</td>
				<td align="left"><textarea name="textorecurso" title="Texto Recurso" cols="55" rows="5">$recurso->texto_recurso</textarea></td>
			</tr>
			<tr>
				<td align="left">Arquivo Recurso:</td>
				<td align="left"><a href="../admin/arquivosrecurso/$recurso->arquivo_anexo">Download Arquivo Recurso</a></td>
			</tr>
			<tr>
				<td align="left">* Edital Resposta:</td>
				<td align="left"><select name="ideditalresposta" id="validar" title="Edital Resposta" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesEditaisResposta
					</select>
				</td>
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