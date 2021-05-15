<?php
	include("../view/template.class.php");
	include("../model/dao/InstituicaoDAO.php");
 	include("../model/entidade/Instituicao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idinstituicao']) ? $idInstituicao = $_GET['idinstituicao'] : $idInstituicao = 0;
	isset($_POST['instituicaocodigo']) ? $instituicaocodigo = $_POST['instituicaocodigo'] : $instituicaocodigo = "";
	isset($_POST['instituicaooucodigo']) ? $instituicaooucodigo = $_POST['instituicaooucodigo'] : $instituicaooucodigo = "";

	$instituicao = new Instituicao();
	/*dados do formulário*/
	isset($_POST['idinstituicao']) ? ($_POST['idinstituicao'] == "" ? $instituicao->setidinstituicao(null) : $instituicao->setidinstituicao($_POST['idinstituicao'])) : $instituicao->setidinstituicao(null);
	isset($_POST['instituicao']) ? $instituicao->setinstituicao($_POST['instituicao']) : $instituicao->setinstituicao(null);
	isset($_POST['logo']) ? $instituicao->setlogo($_POST['logo']) : $instituicao->setlogo(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	$conteudoPesquisa = "";
	
	$instituicaoDAO = new InstituicaoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Nova Instituição";
					$conteudo = montaFormularioCadastroInstituicao(null);
					if($instituicaooucodigo == 1){//por codigo
						$resultadoPesquisa = $instituicaoDAO->getInstituicaoPorID($instituicaocodigo);
					} else { //por titulo
						$resultadoPesquisa = $instituicaoDAO->getInstituicaoPorNome($instituicaocodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaInstituicao($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_instituicao" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Instituicao";
					$instituicao = $instituicaoDAO->getInstituicaoPorID($idInstituicao)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroInstituicao($instituicao);
					if($instituicaooucodigo == 1){//por codigo
						$resultadoPesquisa = $instituicaoDAO->getInstituicaoPorID($instituicaocodigo);
					} else { //por titulo
						$resultadoPesquisa = $instituicaoDAO->getInstituicaoPorNome($instituicaocodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaInstituicao($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Instituição";
					$conteudo = "Instituição NÃO foi Excluído!";
					if($instituicaoDAO->excluirPorID($idInstituicao)){
						$conteudo = "Instituição Excluído com Sucesso!";
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
						. "Caso tenha enviado uma nova Logo, somente ela foi enviada e substituída. <br/><br/>"
						. "Tente novamente e se o problema persistir, contate-nos.";

				$allowedExts = array("jpg", "gif");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$uploaded = false;
				$erroUpload = false;
				if($_FILES['file']['error'] === UPLOAD_ERR_OK){//checa se tem arquivo para upload
					if ((($_FILES["file"]["type"] == "image/gif") 
							|| ($_FILES["file"]["type"] == "image/jpeg"))
							&& ($_FILES["file"]["size"] < 512000)//500kb
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
								$conteudo = "Arquivo Inválido. (Tipos de arquivos permitidos - gif e jpg com no máximo 500kb)<br/><br/>"
										. "<br/><br/><a href=\"javascript: window.history.back();\">Voltar</a>";
							}
				}
// 				echo $erroUpload;
				if(!$erroUpload){
					if($instituicao->getidinstituicao() == null){//inserir
						$idInstituicao = $instituicaoDAO->salvar($instituicao);
						if($idInstituicao > 0){
							if($uploaded){
								$arquivo = $idInstituicao . "." . $extension;
								move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivoslogos/" . $arquivo);
								$instituicaoDAO->updateArquivoRecurso($idInstituicao, $arquivo);
							}
							$conteudo = $conteudoSucesso;
						} else {
							$conteudo = $conteudoErro;
						}
					} else { //atualizar
						if($uploaded){
							$arquivo = $instituicao->getidinstituicao() . "." . $extension;
							move_uploaded_file($_FILES["file"]["tmp_name"],	"../admin/arquivoslogos/" . $arquivo);
							$instituicao->setlogo($arquivo);
						}
						if($instituicaoDAO->update($instituicao)){
							$conteudo = $conteudoSucesso;
						} else {
							$conteudo = $conteudoErro;
						}
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
						. "<td align=\"left\"><a href=\"?nivel=painel_instituicao&acao=exibir_instituicao&idinstituicao=" . $resultado->id_instituicao . "\">" . $resultado->id_instituicao . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_instituicao&acao=exibir_instituicao&idinstituicao=" . $resultado->id_instituicao . "\">" . $resultado->instituicao . "</a></td>"
						. "<td align=\"left\">" . $resultado->logo . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_instituicao&acao=excluir&idinstituicao=" . $resultado->id_instituicao . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaInstituicao($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Instituições</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_instituicao&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="instituicaooucodigo" value="0">Nome <input type="radio" name="instituicaooucodigo" value="1">Código</td>
						<td align="left"><input name="instituicaocodigo" required title="Nome ou Código" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Instituição</td>
					<td align="left">Logo</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function montaFormularioCadastroInstituicao($instituicao){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_instituicao&acao=salvar" enctype="multipart/form-data">
		<input type="hidden" name="idinstituicao" value="$instituicao->id_instituicao">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$instituicao->id_instituicao" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Instituição:</td>
				<td align="left"><input name="instituicao" required title="Instituição" type="text" size="55" value="$instituicao->instituicao" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Logo:</td>
				<td align="left"><input type="file" name="file" id="file">(jpg ou gif com até 500kb)</td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<button onclick="location.href='?nivel=painel_instituicao&acao=exibir_cadastro'" class="button" type="button">Novo</button>
					<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>