<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/CargoDAO.php");
	include("../model/dao/ProvaDAO.php");
 	include("../model/entidade/Prova.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idprova']) ? $idProva = $_GET['idprova'] : $idProva = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;

	$prova = new Prova();
	/*dados do formulário*/
	isset($_POST['idprova']) ? ($_POST['idprova'] == "" ? $prova->setidprova(null) : $prova->setidprova($_POST['idprova'])) : $prova->setidprova(null);
	isset($_POST['idcargo']) ? $prova->setidcargo($_POST['idcargo']) : $prova->setidcargo(null);
	isset($_POST['caminhoprova']) ? $prova->setcaminhoprova($_POST['caminhoprova']) : $prova->setcaminhoprova(null);
	isset($_POST['datainicio']) ? $prova->setdatainicio($_POST['datainicio']) : $prova->setdatainicio(null);
	isset($_POST['datafim']) ? $prova->setdatafim($_POST['datafim']) : $prova->setdatafim(null);
	isset($_POST['horainicio']) ? $prova->sethorainicio($_POST['horainicio']) : $prova->sethorainicio(null);
	isset($_POST['horafim']) ? $prova->sethorafim($_POST['horafim']) : $prova->sethorafim(null);
	

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$provaDAO = new ProvaDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Provas";
					$conteudo = montaFormularioCadastroProva(null, $idConcurso);
					$resultadoPesquisa = $provaDAO->listarProvasPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaProva($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_prova" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Prova";
					$prova = $provaDAO->getProvasPorId($idProva)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroProva($prova, $idConcurso);
					$resultadoPesquisa = $provaDAO->listarProvasPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaProva($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Prova";
					$conteudo = "Prova NÃO foi Excluído!";
					if($provaDAO->excluirPorID($idProva)){
						ini_set('display_errors', 'On');
						unlink("../admin/arquivosprovas/" . $idProva . ".pdf");
						unlink("../admin/arquivosprovas/" . $idProva . ".swf");
						$conteudo = "Prova Excluído com Sucesso!";
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
				$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>"
						. "Caso tenha enviado uma nova Prova, somente ela foi enviada e substituída. <br/><br/>"
						. "Tente novamente e se o problema persistir, contate-nos.";

				$allowedExts = array("pdf", "swf");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$uploaded = false;
				$erroUpload = false;
				if($_FILES['file']['error'] === UPLOAD_ERR_OK){//checa se tem arquivo para upload
// 					echo "entrou";
					if ((($_FILES["file"]["type"] == "application/pdf")
						|| ($_FILES["file"]["type"] == "application/x-shockwave-flash"))
						&& ($_FILES["file"]["size"] < 10240000)//10mb
						&& in_array($extension, $allowedExts)) {
							if ($_FILES["file"]["error"] > 0) {
								$erroUpload = true;
								$conteudo = "Erro ao enviar arquivo. <br/><br/>Return Code: " . $_FILES["file"]["error"] . "<br/><br/>"
									. "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
							} else {
								$uploaded = true;
							}
					} else {
						$erroUpload = true;
						$conteudo = "Arquivo Inválido. (Tipos de arquivos permitidos - pdf e swf com no máximo 10mb)<br/><br/>"
							. "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
// 						echo "entrou2";
					}
				}
// 				echo $erroUpload;
				if(!$erroUpload){
					$prova->setdatainicio(formataData($prova->getdatainicio(), "Y-m-d") . " " . $prova->gethorainicio());
					$prova->setdatafim(formataData($prova->getdatafim(), "Y-m-d") . " " . $prova->gethorafim());
					if($prova->getidprova() == null){//inserir
						$idProva = $provaDAO->salvar($prova);
						if($idProva > 0){
							if($uploaded){
								$arquivo = $idProva . "." . $extension;
								move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivosprovas/" . $arquivo);
								$provaDAO->updateArquivoProva($idProva, $arquivo);
							}
							$conteudo = $conteudoSucesso;
						} else {
							$conteudo = $conteudoErro;
						}
					} else { //atualizar
						if($uploaded){
							$arquivo = $prova->getidprova() . "." . $extension;
							move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivosprovas/" . $arquivo);
							$prova->setcaminhoprova($arquivo);
						}
						if($provaDAO->update($prova)){
							$conteudo = $conteudoSucesso;
						} else {
							$conteudo = $conteudoErro;
						}
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
						. "<td align=\"left\"><a href=\"?nivel=painel_prova&acao=exibir_prova&idprova=" . $resultado->id_prova . "\">" . $resultado->id_prova . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_prova&acao=exibir_prova&idprova=" . $resultado->id_prova . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . $resultado->caminho_prova . "</td>"
						. "<td align=\"center\">" . formataData($resultado->data_inicio, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\">" . formataData($resultado->data_fim, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_prova&acao=excluir&idprova=" . $resultado->id_prova . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
	
	function opcoesCargos($idConcurso, $idCargo){
		$cargoDAO = new CargoDAO();
		$listaCargos = $cargoDAO->getCargoPorIdConcurso($idConcurso);
	
		$str = "";
	
		if($listaCargos != null){
			while($cargo = $listaCargos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cargo->id_cargo . "\"" . ($cargo->id_cargo == $idCargo ? "selected=\"selected\"" : "") . ">" . $cargo->titulo . "</option>";
			}
		}
		return $str;
	}
	
	function montaFormularioPesquisaProva($resultadoPesquisa, $idConcurso){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Prova</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				<tr>
					<td align="left">Concurso:</td>
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_prova&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesConcursos
						</select>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Cargo</td>
					<td align="left">Arquivo</td>
					<td align="center">Data Início</td>
					<td align="center">Data Fim</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
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
	
	function montaFormularioCadastroProva($prova, $idConcurso){
		$opcoesConcursos = opcoesConcursos($idConcurso == null ? $prova->id_concurso : $idConcurso);
		$opcoesCargos = opcoesCargos($idConcurso == null ? $prova->id_concurso : $idConcurso, $prova->id_cargo);
		$dataInicio = formataData($prova->data_inicio, "d/m/Y"); 
		$dataFim = formataData($prova->data_fim, "d/m/Y");
		$horaInicio = formataData($prova->data_inicio, "H:i:s");
		$horaFim = formataData($prova->data_fim, "H:i:s");
		$arquivoObrigatorio = $prova == null ? "id=\"validar\" required " : "";
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_prova&acao=salvar" enctype="multipart/form-data">
		<input type="hidden" name="idprova" value="$prova->id_prova">
		<input type="hidden" name="caminhoprova" value="$prova->caminho_prova">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$prova->id_prova" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Concurso:</td>
				<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_prova&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
						$opcoesConcursos
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Cargo:</td>
				<td align="left"><select name="idcargo" id="validar" title="Cargos" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesCargos
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Arquivo Prova:</td>
				<td align="left"><input type="text" name="arquivo" disabled="disabled" value="$prova->caminho_prova"></td>
			</tr>
			<tr>
				<td align="left">* Arquivo Prova:</td>
				<td align="left"><input type="file" name="file" $arquivoObrigatorio >(pdf ou swf com até 10mb)</td>
			</tr>
			<tr>
				<td align="left">* Data Início:</td>
				<td align="left"><input name="datainicio" required type="text" size="20" value="$dataInicio" id="validar" maxlength="10" onKeyPress="MascaraData(formCadastro.datainicio);"><input name="horainicio" required type="text" size="10" value="$horaInicio" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horainicio);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">* Data Fim:</td>
				<td align="left"><input name="datafim" required type="text" title="Data Fim" size="20" value="$dataFim" id="validar" maxlength="10" onKeyPress="MascaraData(formCadastro.datafim);"/><input name="horafim" required type="text" size="10" value="$horaFim" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horafim);">00:00:00</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_prova&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>