<?php
	include("../view/template.class.php");
	include("../model/dao/EscolaridadeDAO.php");
 	include("../model/entidade/Escolaridade.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idescolaridade']) ? $idEscolaridade = $_GET['idescolaridade'] : $idEscolaridade = 0;

	$escolaridade = new Escolaridade();
	/*dados do formulário*/
	isset($_POST['idescolaridade']) ? ($_POST['idescolaridade'] == "" ? $escolaridade->setidescolaridade(null) : $escolaridade->setidescolaridade($_POST['idescolaridade'])) : $escolaridade->setidescolaridade(null);
	isset($_POST['grauinstrucao']) ? $escolaridade->setgrauinstrucao($_POST['grauinstrucao']) : $escolaridade->setgrauinstrucao(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$escolaridadeDAO = new EscolaridadeDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Escolaridade";
					$conteudo = montaFormularioCadastroEscolaridade(null);
					$resultadoPesquisa = $escolaridadeDAO->litarEscolaridades();
					$conteudoPesquisa = montaFormularioPesquisaEscolaridade($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_escolaridade" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Escolaridade";
					$escolaridade = $escolaridadeDAO->getEscolaridadePorID($idEscolaridade)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroEscolaridade($escolaridade);
					$resultadoPesquisa = $escolaridadeDAO->litarEscolaridades();
					$conteudoPesquisa = montaFormularioPesquisaEscolaridade($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Escolaridade";
					$conteudo = "Escolaridade NÃO foi Excluído!";
					if($escolaridadeDAO->excluirPorID($idEscolaridade)){
						$conteudo = "Escolaridade Excluído com Sucesso!";
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
				if($escolaridade->getidescolaridade() == null){//inserir
					if($escolaridadeDAO->salvar($escolaridade)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($escolaridadeDAO->update($escolaridade)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_escolaridade&acao=exibir_escolaridade&idescolaridade=" . $resultado->id_escolaridade . "\">" . $resultado->id_escolaridade . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_escolaridade&acao=exibir_escolaridade&idescolaridade=" . $resultado->id_escolaridade . "\">" . $resultado->grau_instrucao . "</a></td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_escolaridade&acao=excluir&idescolaridade=" . $resultado->id_escolaridade . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaEscolaridade($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Escolaridade</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Escolaridade</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function montaFormularioCadastroEscolaridade($escolaridade){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_escolaridade&acao=salvar" >
		<input type="hidden" name="idescolaridade" value="$escolaridade->id_escolaridade">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$escolaridade->id_escolaridade" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Escolaridade:</td>
				<td align="left"><input name="grauinstrucao" required title="Escolaridade" type="text" size="55" value="$escolaridade->grau_instrucao" id="validar"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_escolaridade&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>