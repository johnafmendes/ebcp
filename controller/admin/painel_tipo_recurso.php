<?php
	include("../view/template.class.php");
	include("../model/dao/TipoRecursoDAO.php");
 	include("../model/entidade/TipoRecurso.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idtiporecurso']) ? $idTipoRecurso = $_GET['idtiporecurso'] : $idTipoRecurso = 0;

	$tipoRecurso = new TipoRecurso();
	/*dados do formulário*/
	isset($_POST['idtiporecurso']) ? ($_POST['idtiporecurso'] == "" ? $tipoRecurso->setidtiporecurso(null) : $tipoRecurso->setidtiporecurso($_POST['idtiporecurso'])) : $tipoRecurso->setidtiporecurso(null);
	isset($_POST['tiporecurso']) ? $tipoRecurso->settiporecurso($_POST['tiporecurso']) : $tipoRecurso->settiporecurso(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$tipoRecursoDAO = new TipoRecursoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Tipos de Recursos";
					$conteudo = montaFormularioCadastroTipoRecurso(null);
					$resultadoPesquisa = $tipoRecursoDAO->listarTipoRecursos();
					$conteudoPesquisa = montaFormularioPesquisaTiposRecursos($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_tipo_recurso" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Tipo Recurso";
					$tipoRecurso = $tipoRecursoDAO->getTipoRecursoPorID($idTipoRecurso)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroTipoRecurso($tipoRecurso);
					$resultadoPesquisa = $tipoRecursoDAO->listarTipoRecursos();
					$conteudoPesquisa = montaFormularioPesquisaTiposRecursos($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Tipo Recurso";
					$conteudo = "Tipo Recurso NÃO foi Excluído!";
					if($tipoRecursoDAO->excluirPorID($idTipoRecurso)){
						$conteudo = "Tipo Recurso Excluído com Sucesso!";
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
				if($tipoRecurso->getidtiporecurso() == null){//inserir
					if($tipoRecursoDAO->salvar($tipoRecurso)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($tipoRecursoDAO->update($tipoRecurso)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_tipo_recurso&acao=exibir_tipo_recurso&idtiporecurso=" . $resultado->id_tipos_recursos . "\">" . $resultado->id_tipos_recursos . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_tipo_recurso&acao=exibir_tipo_recurso&idtiporecurso=" . $resultado->id_tipos_recursos . "\">" . $resultado->tipos_recursos . "</a></td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_tipo_recurso&acao=excluir&idtiporecurso=" . $resultado->id_tipos_recursos . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaTiposRecursos($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Tipos Recursos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Tipo Recurso</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function montaFormularioCadastroTipoRecurso($tipoRecurso){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_tipo_recurso&acao=salvar" >
		<input type="hidden" name="idtiporecurso" value="$tipoRecurso->id_tipos_recursos">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$tipoRecurso->id_tipos_recursos" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Tipo Recurso:</td>
				<td align="left"><input name="tiporecurso" required title="Tipo Recurso" type="text" size="55" value="$tipoRecurso->tipos_recursos" id="validar"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_tipo_recurso&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>