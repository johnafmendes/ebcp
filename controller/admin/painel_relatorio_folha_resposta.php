<?php
	include("../view/template.class.php");
	include("../model/dao/ConfiguracaoDAO.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/EscolaridadeDAO.php");
	include("../model/entidade/Configuracao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";

	isset($_POST['tiporelatorio']) ? $tipoRelatorio = $_POST['tiporelatorio'] : $tipoRelatorio = 0;	
	isset($_POST['idconcurso1']) ? $idConcurso1 = $_POST['idconcurso1'] : $idConcurso1 = 0;
	isset($_POST['idconcurso2']) ? $idConcurso2 = $_POST['idconcurso2'] : $idConcurso2 = 0;
	isset($_POST['idconcurso3']) ? $idConcurso3 = $_POST['idconcurso3'] : $idConcurso3 = 0;
	isset($_POST['idconcurso4']) ? $idConcurso4 = $_POST['idconcurso4'] : $idConcurso4 = 0;
	isset($_POST['idconcurso5']) ? $idConcurso5 = $_POST['idconcurso5'] : $idConcurso5 = 0;
	isset($_POST['idconcurso6']) ? $idConcurso6 = $_POST['idconcurso6'] : $idConcurso6 = 0;
	isset($_POST['idconcurso7']) ? $idConcurso7 = $_POST['idconcurso7'] : $idConcurso7 = 0;
	isset($_POST['idconcurso8']) ? $idConcurso8 = $_POST['idconcurso8'] : $idConcurso8 = 0;
	isset($_POST['idconcurso9']) ? $idConcurso9 = $_POST['idconcurso9'] : $idConcurso9 = 0;
	
	isset($_POST['idconcurso10']) ? $idConcurso10 = $_POST['idconcurso10'] : $idConcurso10 = 0;
	isset($_POST['idconcurso11']) ? $idConcurso11 = $_POST['idconcurso11'] : $idConcurso11 = 0;
	isset($_POST['idconcurso12']) ? $idConcurso12 = $_POST['idconcurso12'] : $idConcurso12 = 0;
	isset($_POST['idconcurso13']) ? $idConcurso13 = $_POST['idconcurso13'] : $idConcurso13 = 0;
	isset($_POST['idconcurso14']) ? $idConcurso14 = $_POST['idconcurso14'] : $idConcurso14 = 0;
	isset($_POST['idconcurso15']) ? $idConcurso15 = $_POST['idconcurso15'] : $idConcurso15 = 0;
	isset($_POST['idconcurso16']) ? $idConcurso16 = $_POST['idconcurso16'] : $idConcurso16 = 0;
	isset($_POST['idconcurso17']) ? $idConcurso17 = $_POST['idconcurso17'] : $idConcurso17 = 0;
	isset($_POST['idconcurso18']) ? $idConcurso18 = $_POST['idconcurso18'] : $idConcurso18 = 0;
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$configuracaoDAO = new ConfiguracaoDAO();
	
	switch ($acao){
		case "exibir_relatorios" :
			if (isset($_SESSION['autenticadoAdmin'])){
				$titulo = "Relatórios Folhas Respostas";
				$conteudo = montaFormularioRelatorios();
				
				$conteudoLateral =  "";
			}
			
			break;
			
		case "abrir_relatorio" :
			if($_SESSION['nivel'] < 3){
				$titulo = $tituloNivelNegado;
				$conteudo = $conteudoNivelNegado;
				$conteudoLateral =  "";
				break;
			}
				
			if (isset($_SESSION['autenticadoAdmin'])){
				$conteudoSucesso = "Relatório Aberto em Nova Guia.";
				$conteudoErro = "Não foi possível abrir o relatório. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";

				$configuracao = $configuracaoDAO->getConfiguracao()->fetch(PDO::FETCH_OBJ);

				switch ($tipoRelatorio){
					case "1" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta20&idconcurso=" . $idConcurso1 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "2" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta25&idconcurso=" . $idConcurso2 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "3" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta30&idconcurso=" . $idConcurso3 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "4" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta40&idconcurso=" . $idConcurso4 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "5" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta50&idconcurso=" . $idConcurso5 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "6" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta60&idconcurso=" . $idConcurso6 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "7" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta80&idconcurso=" . $idConcurso7 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "8" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta100&idconcurso=" . $idConcurso8 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "9" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaResposta120&idconcurso=" . $idConcurso9 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;

					case "10" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional20&idconcurso=" . $idConcurso10 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "11" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional25&idconcurso=" . $idConcurso11 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "12" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional30&idconcurso=" . $idConcurso12 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "13" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional40&idconcurso=" . $idConcurso13 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "14" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional50&idconcurso=" . $idConcurso14 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "15" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional60&idconcurso=" . $idConcurso15 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "16" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional80&idconcurso=" . $idConcurso16 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "17" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional100&idconcurso=" . $idConcurso17 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					case "18" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFolhaRespostaCondicional120&idconcurso=" . $idConcurso18 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
							$conteudo = $conteudoSucesso;
							break;
					}
				
				$titulo = "Resultado do Relatório";
							
				$conteudoLateral =  "";
			} else {
				//falha na autenticacao
			}
			break;
	}
		
	isset($_SESSION['autenticadoAdmin']) ? $menu = new Template("../view/admin/menu_autenticado.tpl") : $menu = new Template("../view/comum/menu_sem_autenticacao.tpl"); 
	$menu->set("relatorios", "active");
	
	if(isset($_SESSION['autenticadoAdmin'])){
		$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
	}
	
	isset($corpoTemplate) ? $corpo = $corpoTemplate : $corpo = new Template("../view/comum/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", $tituloLateral);
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("areaLogin", $areaLogin);
	$corpo->set("pesquisa", "");
	
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

	function opcoesConcursos(){
		$concursoDAO = new ConcursoDAO();
		$listaConcursos = $concursoDAO->listarTodosConcursos();
		
		$str = "";

		if($listaConcursos != null){
			while($concurso = $listaConcursos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $concurso->id_concurso . "\"" . ">" . $concurso->titulo . "</option>";
			}
		}
		return $str;
	}
	
/*	function opcoesEscolaridades(){
		$escolaridadeDAO = new EscolaridadeDAO();
		$listaEscolaridades = $escolaridadeDAO->litarEscolaridades();
	
		$str = "";
	
		if($listaEscolaridades != null){
			while($escolaridade = $listaEscolaridades->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $escolaridade->id_escolaridade . "\"" . ">" . $escolaridade->grau_instrucao . "</option>";
			}
		}
		return $str;
	}*/
	
	function montaFormularioRelatorios(){
		$opcoesConcursos = opcoesConcursos();
// 		$opcoesEscolaridades = opcoesEscolaridades();
		$str = <<<FORM
			<form name="formRelatorio" method="post" action="?nivel=painel_relatorio_folha_resposta&acao=abrir_relatorio" >
				<table cellpadding="5">
					<tr>
						<td><input type="radio" name="tiporelatorio" value="1"></td>
						<td>Folhas Resposta 20<br/>
							Concurso:<br/>
							<select name="idconcurso1" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="2"></td>
						<td>Folhas Resposta 25<br/>
							Concurso:<br/>
							<select name="idconcurso2" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="3"></td>
						<td>Folhas Respostas 30<br/>
							Concurso:<br/>
							<select name="idconcurso3" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="4"></td>
						<td>Folhas Respostas 40<br/>
							Concurso:<br/>
							<select name="idconcurso4" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="5"></td>
						<td>Folhas Respostas 50<br/>
							Concurso:<br/>
							<select name="idconcurso5" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="6"></td>
						<td>Folhas Respostas 60<br/>
							Concurso:<br/>
							<select name="idconcurso6" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="7"></td>
						<td>Folhas Respostas 80<br/>
							Concurso:<br/>
							<select name="idconcurso7" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="8"></td>
						<td>Folhas Respostas 100<br/>
							Concurso:<br/>
							<select name="idconcurso8" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="9"></td>
						<td>Folhas Respostas 120<br/>
							Concurso:<br/>
							<select name="idconcurso9" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="10"></td>
						<td>Folhas Resposta Condicional 20<br/>
							Concurso:<br/>
							<select name="idconcurso10" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="11"></td>
						<td>Folhas Resposta Condicional 25<br/>
							Concurso:<br/>
							<select name="idconcurso11" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="12"></td>
						<td>Folhas Respostas Condicional 30<br/>
							Concurso:<br/>
							<select name="idconcurso12" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="13"></td>
						<td>Folhas Respostas Condicional 40<br/>
							Concurso:<br/>
							<select name="idconcurso13" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="14"></td>
						<td>Folhas Respostas Condicional 50<br/>
							Concurso:<br/>
							<select name="idconcurso14" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="15"></td>
						<td>Folhas Respostas Condicional 60<br/>
							Concurso:<br/>
							<select name="idconcurso15" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="16"></td>
						<td>Folhas Respostas Condicional 80<br/>
							Concurso:<br/>
							<select name="idconcurso16" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="17"></td>
						<td>Folhas Respostas Condicional 100<br/>
							Concurso:<br/>
							<select name="idconcurso17" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="radio" name="tiporelatorio" value="18"></td>
						<td>Folhas Respostas Condicional 120<br/>
							Concurso:<br/>
							<select name="idconcurso18" title="Concursos" style="width: 400px;">
								<option value="">selecione</option>
								$opcoesConcursos
							</select>
						</td>
					</tr>
				</table>
													
				<input type="submit" name="submit" value="Abrir" class="button">
			</form>
FORM;

	return $str;		
		
	}
	
?>