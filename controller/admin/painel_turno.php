<?php
	include("../view/template.class.php");
	include("../model/dao/TurnoDAO.php");
 	include("../model/entidade/Turno.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idturno']) ? $idTurno = $_GET['idturno'] : $idTurno = 0;

	$turno = new Turno();
	/*dados do formulário*/
	isset($_POST['idturno']) ? ($_POST['idturno'] == "" ? $turno->setidturno(null) : $turno->setidturno($_POST['idturno'])) : $turno->setidturno(null);
	isset($_POST['turno']) ? $turno->setturno($_POST['turno']) : $turno->setturno(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$turnoDAO = new TurnoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Novo Turno";
					$conteudo = montaFormularioCadastroTurno(null);
					$resultadoPesquisa = $turnoDAO->getListaTurnos();
					$conteudoPesquisa = montaFormularioPesquisaTurno($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_turno" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Turno";
					$turno = $turnoDAO->getTurnoPorID($idTurno)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroTurno($turno);
					$resultadoPesquisa = $turnoDAO->getTurnoPorID($idTurno);
					$conteudoPesquisa = montaFormularioPesquisaTurno($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Turno";
					$conteudo = "Turno NÃO foi Excluído!";
					if($turnoDAO->excluirPorID($idTurno)){
						$conteudo = "Turno Excluído com Sucesso!";
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
				if($turno->getidturno() == null){//inserir
					if($turnoDAO->salvar($turno)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($turnoDAO->update($turno)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_turno&acao=exibir_turno&idturno=" . $resultado->id_turno . "\">" . $resultado->id_turno . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_turno&acao=exibir_turno&idturno=" . $resultado->id_turno . "\">" . $resultado->turno . "</a></td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_turno&acao=excluir&idturno=" . $resultado->id_turno . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaTurno($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Turnos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Turno</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function montaFormularioCadastroTurno($turno){
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_turno&acao=salvar" >
		<input type="hidden" name="idturno" value="$turno->id_turno">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$turno->id_turno" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Turno:</td>
				<td align="left"><input name="turno" required title="Turno" type="text" size="55" value="$turno->turno" id="validar"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_turno&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>