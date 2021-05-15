<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/EditalDAO.php");
 	include("../model/entidade/Edital.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idedital']) ? $idEdital = $_GET['idedital'] : $idEdital = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;

	$edital = new Edital();
	/*dados do formulário*/
	isset($_POST['idedital']) ? ($_POST['idedital'] == "" ? $edital->setidedital(null) : $edital->setidedital($_POST['idedital'])) : $edital->setidedital(null);
	isset($_POST['idconcurso']) ? $edital->setidconcurso($_POST['idconcurso']) : $edital->setidconcurso(null);
	isset($_POST['titulo']) ? $edital->settitulo($_POST['titulo']) : $edital->settitulo(null);
	isset($_POST['data']) ? $edital->setdata($_POST['data']) : $edital->setdata(null);
	isset($_POST['caminhoarquivo']) ? $edital->setcaminhoarquivo($_POST['caminhoarquivo']) : $edital->setcaminhoarquivo(null);
	isset($_POST['atualizacao']) ? $edital->setatualizacao($_POST['atualizacao']) : $edital->setatualizacao(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$editalDAO = new EditalDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Editais";
					$conteudo = montaFormularioCadastroEdital(null, $idConcurso);
					$resultadoPesquisa = $editalDAO->getEditaisPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaEdital($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_edital" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Edital";
					$edital = $editalDAO->getEditalPorId($idEdital)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroEdital($edital, $idConcurso);
					$resultadoPesquisa = $editalDAO->getEditaisPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaEdital($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Edital";
					$conteudo = "Edital NÃO foi Excluído!";
					if($editalDAO->excluirPorID($idEdital)){
						ini_set('display_errors', 'On');
						unlink("../admin/arquivoseditais/" . $idEdital . ".pdf");
						$conteudo = "Edital Excluído com Sucesso!";
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
						. "Caso tenha enviado um novo Edital, somente ela foi enviada e substituída. <br/><br/>"
						. "Tente novamente e se o problema persistir, contate-nos.";

				$allowedExts = array("pdf");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$uploaded = false;
				$erroUpload = false;
				if($_FILES['file']['error'] === UPLOAD_ERR_OK){//checa se tem arquivo para upload
// 					echo "entrou";
					if ((($_FILES["file"]["type"] == "application/pdf"))
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
						$conteudo = "Arquivo Inválido. (Tipos de arquivos permitidos - pdf com no máximo 10mb)<br/><br/>"
							. "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
// 						echo "entrou2";
					}
				}
// 				echo $erroUpload;
				if(!$erroUpload){
					$edital->setdata(formataData($edital->getdata(), "Y-m-d"));
					if($edital->getidedital() == null){//inserir
						$idEdital = $editalDAO->salvar($edital);
						if($idEdital > 0){
							if($uploaded){
								$arquivo = $idEdital . "." . $extension;
								move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivoseditais/" . $arquivo);
								$editalDAO->updateArquivoEdital($idEdital, $arquivo);
							}
							$conteudo = $conteudoSucesso;
						} else {
							$conteudo = $conteudoErro;
						}
					} else { //atualizar
						if($uploaded){
							$arquivo = $edital->getidedital() . "." . $extension;
							move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivoseditais/" . $arquivo);
							$edital->setcaminhoarquivo($arquivo);
						}
						if($editalDAO->update($edital)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_edital&acao=exibir_edital&idedital=" . $resultado->id_edital . "\">" . $resultado->id_edital . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_edital&acao=exibir_edital&idedital=" . $resultado->id_edital . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . formataData($resultado->data, "d/m/Y") . "</td>"
						. "<td align=\"center\">" . $resultado->caminho_arquivo . "</td>"
						. "<td align=\"center\">" . $resultado->atualizacao . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_edital&acao=excluir&idedital=" . $resultado->id_edital . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
	
	function montaFormularioPesquisaEdital($resultadoPesquisa, $idConcurso){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$opcoesConcursos = opcoesConcursos($idConcurso);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Edital</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				<tr>
					<td align="left">Concurso:</td>
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_edital&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
							$opcoesConcursos
						</select>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Título</td>
					<td align="left">Data</td>
					<td align="center">Arquivo</td>
					<td align="center">Atualização</td>
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
	
	function montaFormularioCadastroEdital($edital, $idConcurso){
		$opcoesConcursos = opcoesConcursos($idConcurso == null ? $edital->id_concurso : $idConcurso);
		$data = formataData($edital->data, "d/m/Y"); 
		$arquivoObrigatorio = $edital == null ? "id=\"validar\" required " : "";
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_edital&acao=salvar" enctype="multipart/form-data">
		<input type="hidden" name="idedital" value="$edital->id_edital">
		<input type="hidden" name="caminhoarquivo" value="$edital->caminho_arquivo">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$edital->id_edital" disabled="disabled"></td>
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
				<td align="left">* Título:</td>
				<td align="left"><input type="text" name="titulo" required id="validar" size="55" value="$edital->titulo"></td>
			</tr>
			<tr>
				<td align="left">* Arquivo Edital:</td>
				<td align="left"><input type="text" name="arquivo" disabled="disabled" value="$edital->caminho_arquivo"></td>
			</tr>
			<tr>
				<td align="left">* Arquivo Edital:</td>
				<td align="left"><input type="file" name="file" $arquivoObrigatorio >(pdf com até 10mb)</td>
			</tr>
			<tr>
				<td align="left">* Data:</td>
				<td align="left"><input name="data" required type="text" size="20" value="$data" id="validar" maxlength="10" onKeyPress="MascaraData(formCadastro.data);"></td>
			</tr>
			<tr>
				<td align="left">* Atualização:</td>
				<td align="left"><textarea name="atualizacao" required type="text" title="Atualização" cols="55" rows="4" id="validar" >$edital->atualizacao</textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_edital&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>