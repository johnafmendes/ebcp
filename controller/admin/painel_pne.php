<?php
	include("../view/template.class.php");
	include("../model/dao/PneDAO.php");
 	include("../model/entidade/Pne.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idpne']) ? $idPne = $_GET['idpne'] : $idPne = 0;

	$pne = new Pne();
	/*dados do formulário*/
	isset($_POST['idpne']) ? ($_POST['idpne'] == "" ? $pne->setidpne(null) : $pne->setidpne($_POST['idpne'])) : $pne->setidpne(null);
	isset($_POST['tipo']) ? $pne->settipo($_POST['tipo']) : $pne->settipo(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$pneDAO = new PneDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de PNE";
					$conteudo = montaFormularioCadastroPNE(null);
					$resultadoPesquisa = $pneDAO->listarPNE();
					$conteudoPesquisa = montaFormularioPesquisaPNE($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_pne" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar PNE";
					$pne = $pneDAO->getPNEPorID($idPne)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroPNE($pne);
					$resultadoPesquisa = $pneDAO->listarPNE();
					$conteudoPesquisa = montaFormularioPesquisaPNE($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão PNE";
					$conteudo = "PNE NÃO foi Excluído!";
					if($pneDAO->excluirPorID($idPne)){
						$conteudo = "PNE Excluído com Sucesso!";
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
				if($pne->getidpne() == null){//inserir
					if($pneDAO->salvar($pne)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($pneDAO->update($pne)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_pne&acao=exibir_pne&idpne=" . $resultado->id_pne . "\">" . $resultado->id_pne . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_pne&acao=exibir_pne&idpne=" . $resultado->id_pne . "\">" . $resultado->tipo . "</a></td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_pne&acao=excluir&idpne=" . $resultado->id_pne . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaPNE($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa PNEs</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Tipo</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function montaFormularioCadastroPNE($pne){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_pne&acao=salvar" >
		<input type="hidden" name="idpne" value="$pne->id_pne">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$pne->id_pne" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Tipo:</td>
				<td align="left"><input name="tipo" required title="Tipo" type="text" size="55" value="$pne->tipo" id="validar"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_pne&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>