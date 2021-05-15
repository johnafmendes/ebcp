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
	isset($_POST['idescolaridade10']) ? $idEscolaridade10 = $_POST['idescolaridade10'] : $idEscolaridade10 = 0;
	isset($_POST['idconcurso11']) ? $idConcurso11 = $_POST['idconcurso11'] : $idConcurso11 = 0;
	isset($_POST['idescolaridade11']) ? $idEscolaridade11 = $_POST['idescolaridade11'] : $idEscolaridade11 = 0;
	isset($_POST['idconcurso12']) ? $idConcurso12 = $_POST['idconcurso12'] : $idConcurso12 = 0;
	isset($_POST['idconcurso13']) ? $idConcurso13 = $_POST['idconcurso13'] : $idConcurso13 = 0;
	isset($_POST['idconcurso14']) ? $idConcurso14 = $_POST['idconcurso14'] : $idConcurso14 = 0;
	isset($_POST['idconcurso15']) ? $idConcurso15 = $_POST['idconcurso15'] : $idConcurso15 = 0;
	isset($_POST['idconcurso16']) ? $idConcurso16 = $_POST['idconcurso16'] : $idConcurso16 = 0;
	isset($_POST['idconcurso17']) ? $idConcurso17 = $_POST['idconcurso17'] : $idConcurso17 = 0;
	
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
				$titulo = "Relatórios Candidatos";
				$conteudo = montaFormularioRelatorios();
				
// 				$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 				$tituloLateral = "Ajuda";
				$conteudoLateral =  "";
			}
			
			break; //fim case exibir_configuracao
			
		case "abrir_relatorio" :
			if($_SESSION['nivel'] == 1){
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
					case "0" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatos&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "1" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosPorConcurso&idconcurso=" . $idConcurso1 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "2" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosOuNao&idconcurso=" . $idConcurso2 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "3" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosOuNao&idconcurso=" . $idConcurso3 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "4" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosIsentosOuNao&idconcurso=" . $idConcurso4 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "5" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosIsentosOuNao&idconcurso=" . $idConcurso5 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "6" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaTotaisCandidatosInscritosPorConcurso&idconcurso=" . $idConcurso6 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "7" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaTotaisCandidatosInscritosHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso7 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "8" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaTotaisCandidatosInscritosHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso8 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "9" :
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaTotaisFinanceirosCandidatosHomologadosPorConcurso&idconcurso=" . $idConcurso9 . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "10" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosOuNaoPorEscolaridadePorConcurso&idconcurso=" . $idConcurso10 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "11" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/ListaCandidatosHomologadosOuNaoPorEscolaridadePorConcurso&idconcurso=" . $idConcurso11 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "12" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioEstatisticoCandidatosHomologadosOuNaoPorEscolaridadePorConcurso&idconcurso=" . $idConcurso12 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "13" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioEstatisticoCandidatosHomologadosOuNaoPorEscolaridadePorConcurso&idconcurso=" . $idConcurso13 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "14" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFaixaEtariaHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso14 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "15" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioFaixaEtariaHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso15 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "16" :
						$homologado = 1;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioEstatisticoFaixaEtariaCandidatosHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso16 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
							. "</script>";
						$conteudo = $conteudoSucesso;
						break;
					case "17" :
						$homologado = 0;
						echo "<script>"
							. "window.open('" . $configuracao->ireport_url . "&reportUnit=/reports/ebcp/RelatorioEstatisticoFaixaEtariaCandidatosHomologadosOuNaoPorConcurso&idconcurso=" . $idConcurso17 . "&homologado=" . $homologado . "&j_username=" . $configuracao->ireport_usuario . "&j_password=" . $configuracao->ireport_password . "&output=pdf', '_blank');"
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
			<form name="formRelatorio" method="post" action="?nivel=painel_relatorio_candidatos&acao=abrir_relatorio" >
				<table cellpadding="5">
								<tr>
									<td><input type="radio" name="tiporelatorio" value="0"></td>
									<td>Relatório Geral Simples</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="1"></td>
									<td>Relatório Candidatos por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso1" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="2"></td>
									<td>Relatório Candidatos Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso2" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="3"></td>
									<td>Relatório Candidatos Não Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso3" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="4"></td>
									<td>Relatório Candidatos Isenção Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso4" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="5"></td>
									<td>Relatório Candidatos Isenção Não Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso5" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="6"></td>
									<td>Relatório Totais de Candidatos Inscritos por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso6" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="7"></td>
									<td>Relatório Totais de Candidatos Inscritos Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso7" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="8"></td>
									<td>Relatório Totais de Candidatos Inscritos Não Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso8" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="9"></td>
									<td>Relatório Total Financeiro Homologados por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso9" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>					
								<tr>
									<td><input type="radio" name="tiporelatorio" value="10"></td>
									<td>Relatório Candidatos Homologados Por Escolaridade e Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso10" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="11"></td>
									<td>Relatório Candidatos Não Homologados Por Escolaridade e Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso11" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="12"></td>
									<td>Relatório Estatístico de Candidatos Homologados Por Escolaridade e Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso12" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="13"></td>
									<td>Relatório Estatístico de Candidatos Não Homologados Por Escolaridade e Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso13" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>	
								<tr>
									<td><input type="radio" name="tiporelatorio" value="14"></td>
									<td>Relatório Faixa Etária de Candidatos Homologados Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso14" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="15"></td>
									<td>Relatório Faixa Etária de Candidatos Não Homologados Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso15" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>	
								<tr>
									<td><input type="radio" name="tiporelatorio" value="16"></td>
									<td>Relatório Estatístico de Faixa Etária de Candidatos Homologados Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso16" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="tiporelatorio" value="17"></td>
									<td>Relatório Estatístico de Faixa Etária de Candidatos Não Homologados Por Concurso<br/>
										Concurso:<br/>
										<select name="idconcurso17" title="Concursos" style="width: 400px;">
											<option value="">selecione</option>
											$opcoesConcursos
										</select>
									</td>
								</tr>			
							</table>
													
				<!--<div class="tabs">
				    <ul class="tab-links">
				        <li class="active"><a href="#tab1">Relatório Simples</a></li>
				        <li><a href="#tab2">Filtros Básicos</a></li>
				        <li><a href="#tab3">Tab #3</a></li>
				        <li><a href="#tab4">Tab #4</a></li>
				    </ul>
				 
				    <div class="tab-content">
				        <div id="tab1" class="tab active">
				            
				        </div>
				 
				        <div id="tab2" class="tab">
				            <p>Tab #2 content goes here!</p>
				            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
				        </div>
				 
				        <div id="tab3" class="tab">
				            <p>Tab #3 content goes here!</p>
				            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum ri.</p>
				        </div>
				 
				        <div id="tab4" class="tab">
				            <p>Tab #4 content goes here!</p>
				            <p>Donec pulvinar neque sed semper lacinia. Curabitur lacinia ullamcorper nibh; quis imperdiet velit eleifend ac. Donec blandit mauris eget aliquet lacinia! Donec pulvinar massa interdum risus ornare mollis. In hac habitasse platea dictumst. Ut euismod tempus hendrerit. Morbi ut adipiscing nisi. Etiam rutrum sodales gravida! Aliquam tellus orci, iaculis vel.</p>
				        </div>
				    </div>
				</div>-->
				<input type="submit" name="submit" value="Abrir" class="button">
			</form>
FORM;

	return $str;		
		
	}
	
?>