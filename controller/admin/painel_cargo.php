<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/CargoDAO.php");
	include("../model/dao/TurnoDAO.php");
 	include("../model/entidade/Cargo.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idcargo']) ? $idCargo = $_GET['idcargo'] : $idCargo = 0;
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;
// 	isset($_POST['nomecodigo']) ? $nomecodigo = $_POST['nomecodigo'] : $nomecodigo = "";
// 	isset($_POST['nomeoucodigo']) ? $nomeoucodigo = $_POST['nomeoucodigo'] : $nomeoucodigo = "";
	

	$cargo = new Cargo();
	/*dados do formulário*/
	isset($_POST['idcargo']) ? ($_POST['idcargo'] == "" ? $cargo->setidcargo(null) : $cargo->setidcargo($_POST['idcargo'])) : $cargo->setidcargo(null);
	isset($_POST['idconcurso']) ? $cargo->setidconcurso($_POST['idconcurso']) : $cargo->setidconcurso(null);
	isset($_POST['titulo']) ? $cargo->settitulo($_POST['titulo']) : $cargo->settitulo(null);
	isset($_POST['valorinscricao']) ? $cargo->setvalorinscricao($_POST['valorinscricao']) : $cargo->setvalorinscricao(null);
	isset($_POST['idturno']) ? $cargo->setidturno($_POST['idturno']) : $cargo->setidturno(null);
	isset($_POST['numerovagas']) ? $cargo->setnumerovagas($_POST['numerovagas']) : $cargo->setnumerovagas(null);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$cargoDAO = new CargoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Cargos";
					$conteudo = montaFormularioCadastroCargo(null);
					$resultadoPesquisa = $cargoDAO->getCargoPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaCargo($resultadoPesquisa, $idConcurso);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_cargo" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Cargo";
					$cargo = $cargoDAO->getCargoPorID($idCargo)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroCargo($cargo);
					$resultadoPesquisa = $cargoDAO->getCargoPorIdConcurso($idConcurso);
					$conteudoPesquisa = montaFormularioPesquisaCargo($resultadoPesquisa, $idConcurso);
			
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
					$titulo = "Resultado Exclusão Cargo";
					$conteudo = "Cargo NÃO foi Excluído!";
					if($cargoDAO->excluirPorID($idCargo)){
						$conteudo = "Cargo Excluído com Sucesso!";
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
				if($cargo->getidcargo() == null){//inserir
					if($cargoDAO->salvar($cargo)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($cargoDAO->update($cargo)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_cargo&acao=exibir_cargo&idcargo=" . $resultado->id_cargo . "\">" . $resultado->id_cargo . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_cargo&acao=exibir_cargo&idcargo=" . $resultado->id_cargo . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . $resultado->valor_inscricao . "</td>"
						. "<td align=\"center\">" . $resultado->numero_vagas . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_cargo&acao=excluir&idcargo=" . $resultado->id_cargo . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
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
	
	function montaFormularioPesquisaCargo($resultadoPesquisa, $idConcurso){
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
					<td align="left"><select name="idconcurso" id="validar" title="Concursos" required onchange="javascript: window.location.href = '?nivel=painel_cargo&acao=exibir_cadastro&idconcurso=' + this.value;" style="width: 400px;">
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
					<td align="left">Valor Inscrição</td>
					<td align="center">Número Vagas</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function opcoesTurnos($idTurno){
		$turnoDAO = new TurnoDAO();
		$listaTurnos = $turnoDAO->getListaTurnos();
		
		$str = "";
		
		if($listaTurnos != null){
			while($turno = $listaTurnos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $turno->id_turno . "\"" . ($turno->id_turno == $idTurno ? "selected=\"selected\"" : "") . ">" . $turno->turno . "</option>";
			}
		}
		return $str;
	}

	function montaFormularioCadastroCargo($cargo){
		$opcoesConcursos = opcoesConcursos($cargo->id_concurso);
		$opcoesTurnos = opcoesTurnos($cargo->id_turno);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_cargo&acao=salvar" >
		<input type="hidden" name="idcargo" value="$cargo->id_cargo">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$cargo->id_cargo" disabled="disabled"></td>
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
				<td align="left"><input name="titulo" required title="Título" type="text" size="55" value="$cargo->titulo" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Valor Incrição:</td>
				<td align="left"><input name="valorinscricao" required type="text" title="Login" size="20" value="$cargo->valor_inscricao" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Turno:</td>
				<td align="left"><select name="idturno" id="validar" title="Turnos" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesTurnos
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Número Vagas:</td>
				<td align="left"><input name="numerovagas" required type="text" title="Número de Vagas" size="20" value="$cargo->numero_vagas" id="validar"/></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_cargo&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>