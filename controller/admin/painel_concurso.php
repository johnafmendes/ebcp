<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
 	include("../model/dao/InstituicaoDAO.php");
 	include("../model/dao/BoletoDAO.php");
 	include("../model/dao/TipoConcursoDAO.php");
 	include("../model/entidade/Concurso.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['usuario']) ? $usuario = $_GET['usuario'] : $usuario = "";
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;
	isset($_POST['titulocodigo']) ? $titulocodigo = $_POST['titulocodigo'] : $titulocodigo = "";
	isset($_POST['titulooucodigo']) ? $titulooucodigo = $_POST['titulooucodigo'] : $titulooucodigo = "";

	$concurso = new Concurso();
	/*dados do formulário*/
	isset($_POST['idconcurso']) ? ($_POST['idconcurso'] == "" ? $concurso->setidconcurso(null) : $concurso->setidconcurso($_POST['idconcurso'])) : $concurso->setidconcurso(null);
	isset($_POST['titulo']) ? $concurso->settitulo($_POST['titulo']) : $concurso->settitulo(null);
	isset($_POST['subtitulo']) ? $concurso->setsubtitulo($_POST['subtitulo']) : $concurso->setsubtitulo(null);
	isset($_POST['idinstituicao']) ? $concurso->setidinstituicao($_POST['idinstituicao']) : $concurso->setidinstituicao(null);
	isset($_POST['inicioinscricao']) ? $concurso->setinicioinscricao($_POST['inicioinscricao']) : $concurso->setinicioinscricao(null);
	isset($_POST['finalinscricao']) ? $concurso->setfinalinscricao($_POST['finalinscricao']) : $concurso->setfinalinscricao(null);
	isset($_POST['horainicioinscricao']) ? $concurso->sethorainicioinscricao($_POST['horainicioinscricao']) : $concurso->sethorainicioinscricao(null);
	isset($_POST['horafinalinscricao']) ? $concurso->sethorafinalinscricao($_POST['horafinalinscricao']) : $concurso->sethorafinalinscricao(null);
	isset($_POST['vencimentoboleto']) ? $concurso->setvencimentoboleto($_POST['vencimentoboleto']) : $concurso->setvencimentoboleto(null);
	isset($_POST['idboleto']) ? $concurso->setidboleto($_POST['idboleto']) : $concurso->setidboleto(null);
	isset($_POST['homologado']) ? $concurso->sethomologado($_POST['homologado']) : $concurso->sethomologado(0);
	isset($_POST['ativo']) ? $concurso->setativo($_POST['ativo']) : $concurso->setativo(0);
	isset($_POST['destaque']) ? $concurso->setdestaque($_POST['destaque']) : $concurso->setdestaque(0);
	isset($_POST['idtipoconcurso']) ? $concurso->setidtipoconcurso($_POST['idtipoconcurso']) : $concurso->setidtipoconcurso(null);
	isset($_POST['isencaodoadorsangue']) ? $concurso->setisencaodoadorsangue($_POST['isencaodoadorsangue']) : $concurso->setisencaodoadorsangue(0);
	isset($_POST['datainiciodoadorsangue']) ? $concurso->setdoadorsanguedatainicio($_POST['datainiciodoadorsangue']) : $concurso->setdoadorsanguedatainicio(null);
	isset($_POST['datafimdoadorsangue']) ? $concurso->setdoadorsanguedatafim($_POST['datafimdoadorsangue']) : $concurso->setdoadorsanguedatafim(null);
	isset($_POST['horainiciodoadorsangue']) ? $concurso->sethorainiciodoadorsangue($_POST['horainiciodoadorsangue']) : $concurso->sethorainiciodoadorsangue(null);
	isset($_POST['horafinaldoadorsangue']) ? $concurso->sethorafinaldoadorsangue($_POST['horafinaldoadorsangue']) : $concurso->sethorafinaldoadorsangue(null);
	isset($_POST['isencaonis']) ? $concurso->setisencaonis($_POST['isencaonis']) : $concurso->setisencaonis(0);
	isset($_POST['datainicioisencaonis']) ? $concurso->setnisdatainicio($_POST['datainicioisencaonis']) : $concurso->setnisdatainicio(null);
	isset($_POST['datafimisencaonis']) ? $concurso->setnisdatafim($_POST['datafimisencaonis']) : $concurso->setnisdatafim(null);
	isset($_POST['horainicioisencaonis']) ? $concurso->sethorainicioisencaonis($_POST['horainicioisencaonis']) : $concurso->sethorainicioisencaonis(null);
	isset($_POST['horafinalisencaonis']) ? $concurso->sethorafinalisencaonis($_POST['horafinalisencaonis']) : $concurso->sethorafinalisencaonis(null);
	isset($_POST['cotaracial']) ? $concurso->setcotaracial($_POST['cotaracial']) : $concurso->setcotaracial(0);
	isset($_POST['multiplasinscricoes']) ? $concurso->setmultiplasinscricoes($_POST['multiplasinscricoes']) : $concurso->setmultiplasinscricoes(0);

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	$conteudoPesquisa = "";
	
	$concursoDAO = new ConcursoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Novo Concurso";
					$conteudo = montaFormularioCadastroConcurso(null);
					if($titulooucodigo == 1){//por codigo
						$resultadoPesquisa = $concursoDAO->getConcursoPorID($titulocodigo);
					} else { //por titulo
						$resultadoPesquisa = $concursoDAO->getConcursoPorTitulo($titulocodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaConcurso($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_concurso" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Concurso";
// 					echo $idConcurso;
					$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroConcurso($concurso);
					if($titulooucodigo == 1){//por codigo
						$resultadoPesquisa = $concursoDAO->getConcursoPorID($titulocodigo);
					} else { //por titulo
						$resultadoPesquisa = $concursoDAO->getConcursoPorTitulo($titulocodigo);
					}
					$conteudoPesquisa = montaFormularioPesquisaConcurso($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Concurso";
					$conteudo = "Concurso NÃO foi Excluído!";
					if($concursoDAO->excluirPorID($idConcurso)){
						$conteudo = "Concurso Excluído com Sucesso!";
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
				$concurso->setinicioinscricao(implode("-",array_reverse(explode("/",$concurso->getinicioinscricao()))) . " " . $concurso->gethorainicioinscricao());
				$concurso->setfinalinscricao(implode("-",array_reverse(explode("/",$concurso->getfinalinscricao()))) . " " . $concurso->gethorafinalinscricao());
				$concurso->setvencimentoboleto(implode("-",array_reverse(explode("/",$concurso->getvencimentoboleto()))));
				$concurso->setdoadorsanguedatainicio(implode("-",array_reverse(explode("/",$concurso->getdoadorsanguedatainicio()))) . " " . $concurso->gethorainiciodoadorsangue());
				$concurso->setdoadorsanguedatafim(implode("-",array_reverse(explode("/",$concurso->getdoadorsanguedatafim()))) . " " . $concurso->gethorafinaldoadorsangue());
				$concurso->setnisdatainicio(implode("-",array_reverse(explode("/",$concurso->getnisdatainicio()))) . " " . $concurso->gethorainicioisencaonis());
				$concurso->setnisdatafim(implode("-",array_reverse(explode("/",$concurso->getnisdatafim()))) . " " . $concurso->gethorafinalisencaonis());
// 				echo $concurso->getnisdatafim();
				$conteudoSucesso = "Dados salvo com sucesso.";
				$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				if($concurso->getidconcurso() == null){//inserir
					if($concursoDAO->salvar($concurso)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($concursoDAO->update($concurso)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				}
				$titulo = "Resultado do Salvamento";
				$tituloLateral = "Ajuda";
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
						. "<td align=\"left\"><a href=\"?nivel=painel_concurso&acao=exibir_concurso&idconcurso=" . $resultado->id_concurso . "\">" . $resultado->id_concurso . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_concurso&acao=exibir_concurso&idconcurso=" . $resultado->id_concurso . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . formataData($resultado->inicio_inscricao, "d/m/Y H:i:s") . " a " . formataData($resultado->final_inscricao, "d/m/Y H:i:s") . "</td>"
						. "<td align=\"center\">" . ($resultado->homologado == 1 ? "Sim" : "Não") . "</td>"
						. "<td align=\"center\">" . ($resultado->ativo == 1 ? "Sim" : "Não") . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_concurso&acao=excluir&idconcurso=" . $resultado->id_concurso . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaConcurso($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Concursos</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_concurso&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="titulooucodigo" value="0">Título <input type="radio" name="titulooucodigo" value="1">Código</td>
						<td align="left"><input name="titulocodigo" required title="Título ou Código" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar" onclick="return validaPesquisaConcurso();"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Título</td>
					<td align="left">Período Inscrição</td>
					<td align="center">Homologado</td>
					<td align="center">Ativo</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function opcoesInstituicao($idInstituicao){
		$str = "";
		$instituicaoDAO = new InstituicaoDAO();
		$listaInstituicoes = $instituicaoDAO->listarInstituicoes();
		if($listaInstituicoes != null){
			while($instituicao = $listaInstituicoes->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $instituicao->id_instituicao . "\"" . ($idInstituicao == $instituicao->id_instituicao ? "selected=\"selected\"" : "") . ">" . $instituicao->instituicao . "</option>";
			}
		}
		return $str;
	}
	
	function opcoesBoleto($idBoleto){
		$str = "";
		$boletoDAO = new BoletoDAO();
		$listaBoletos = $boletoDAO->listarBoletos();
		if($listaBoletos != null){
			while($boleto = $listaBoletos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $boleto->id_boleto . "\"" . ($idBoleto == $boleto->id_boleto ? "selected=\"selected\"" : "") . ">" . $boleto->descricao . "</option>";
			}
		}
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
	
	function opcoesTipoConcurso($idTipoConcurso){
		$str = "";
		$tipoConcursoDAO = new TipoConcursoDAO();
		$listaTipos = $tipoConcursoDAO->listarTiposConcursos();
		if($listaTipos != null){
			while($tipo = $listaTipos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $tipo->id_tipo_concurso . "\"" . ($idTipoConcurso == $tipo->id_tipo_concurso ? "selected=\"selected\"" : "") . ">" . $tipo->tipo . "</option>";
			}
		}
		return $str;
	}
	
	function montaFormularioCadastroConcurso($concurso){
		$opcoesInstituicao = opcoesInstituicao($concurso->id_instituicao);
		$dataInicioInscricao = formataData(isset($concurso->inicio_inscricao) ? $concurso->inicio_inscricao : "", "d/m/Y");
		$dataFinalInscricao = formataData(isset($concurso->final_inscricao) ? $concurso->final_inscricao : "", "d/m/Y");
		$horaInicioInscricao = formataData(isset($concurso->inicio_inscricao) ? $concurso->inicio_inscricao : "", "H:i:s");
		$horaFinalInscricao = formataData(isset($concurso->final_inscricao) ? $concurso->final_inscricao : "", "H:i:s");
		$dataVencimentoBoleto = formataData(isset($concurso->vencimento_boleto) ? $concurso->vencimento_boleto : "", "d/m/Y");
		$opcoesBoleto = opcoesBoleto($concurso->id_boleto);
		$opcoesTipoConcurso = opcoesTipoConcurso($concurso->id_tipo_concurso);
		$dataInicioDoadorSangue = formataData(isset($concurso->doador_sangue_data_inicio) ? $concurso->doador_sangue_data_inicio : "", "d/m/Y");
		$dataFinalDoadorSangue = formataData(isset($concurso->doador_sangue_data_fim) ? $concurso->doador_sangue_data_fim : "", "d/m/Y");
		$horaInicioDoadorSangue = formataData(isset($concurso->doador_sangue_data_inicio) ? $concurso->doador_sangue_data_inicio : "", "H:i:s");
		$horaFinalDoadorSangue = formataData(isset($concurso->doador_sangue_data_fim) ? $concurso->doador_sangue_data_fim : "", "H:i:s");
		$dataInicioNIS = formataData(isset($concurso->nis_data_inicio) ? $concurso->nis_data_inicio : "", "d/m/Y");
		$dataFinalNIS = formataData(isset($concurso->nis_data_fim) ? $concurso->nis_data_fim : "", "d/m/Y");
		$horaInicioIsencaoNIS = formataData(isset($concurso->nis_data_inicio) ? $concurso->nis_data_inicio : "", "H:i:s");
		$horaFinalIsencaoNIS = formataData(isset($concurso->nis_data_fim) ? $concurso->nis_data_fim : "", "H:i:s"); 
		$opcaoHomologado = $concurso->homologado == 1 ? "checked" : "";
		$opcaoAtivo = $concurso->ativo == 1 ? "checked" : "";
		$opcaoDestaque = $concurso->destaque == 1 ? "checked" : "";
		$opcaoIsencaoDoadorSangue = $concurso->isencao_doador_sangue == 1 ? "checked" : "";
		$opcaoIsencaoNIS = $concurso->isencao_nis == 1 ? "checked" : "";
		$opcaoCotaRacial = $concurso->cota_racial == 1 ? "checked" : "";
		$opcaoMultiplaInscricoes = $concurso->multiplas_inscricoes == 1 ? "checked" : "";
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_concurso&acao=salvar" >
		<input type="hidden" name="idconcurso" value="$concurso->id_concurso">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$concurso->id_concurso" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Título:</td>
				<td align="left"><input name="titulo" required title="Título" type="text" size="55" value="$concurso->titulo" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Sub-título:</td>
				<td align="left"><input name="subtitulo" required type="text" title="Sub-Título" size="55" value="$concurso->subtitulo" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Instituição:</td>
				<td align="left"><select name="idinstituicao" title="Instituição" required style="width:400px;" id="validar">
						<option value="">Selecione</option>
						$opcoesInstituicao
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Data Início Inscrições:</td>
				<td align="left"><input name="inicioinscricao" required type="text" title="Data Início Inscrições" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.inicioinscricao);" value="$dataInicioInscricao" id="validar"/><input name="horainicioinscricao" required type="text" size="10" value="$horaInicioInscricao" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horainicioinscricao);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">* Data Final Inscrições:</td>
				<td align="left"><input name="finalinscricao" required type="text" title="Data Final Inscrições" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.finalinscricao);" value="$dataFinalInscricao" id="validar"/><input name="horafinalinscricao" required type="text" size="10" value="$horaFinalInscricao" id="validar" maxlength="8" onKeyPress="MascaraHora(formCadastro.horafinalinscricao);">00:00:00</td>
			</tr>
			<tr>
				<td width="150" align="left">* Cobrança: </td>
				<td align="left"><select name="idboleto" required title="Cobrança" style="width:400px;" id="validar">
						<option value="">Selecione</option>
						$opcoesBoleto
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Vencimento Boleto:</td>
				<td align="left"><input name="vencimentoboleto" required type="text" title="Vencimento Boleto" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.vencimentoboleto);" value="$dataVencimentoBoleto" id="validar"/>(DD/MM/AAAA)</td>
			</tr>
			<tr>
				<td align="left">* Tipo Concurso:</td>
				<td align="left"><select name="idtipoconcurso" title="Tipo Concurso" required style="width:400px;" id="validar">
						<option value="">Selecione</option>
						$opcoesTipoConcurso
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Homologado: </td>
				<td align="left">
					<input type="checkbox" value="1" $opcaoHomologado name="homologado" title="Homologado">
				</td>
			</tr>
			<tr>
				<td align="left">Ativo: </td>
				<td align="left">
					<input type="checkbox" value="1" $opcaoAtivo name="ativo" title="Ativo">
				</td>
			</tr>
			<tr>
				<td align="left">Destaque: </td>
				<td align="left">
					<input type="checkbox" value="1" $opcaoDestaque name="destaque" title="Destaque">
				</td>
			</tr>			
			<tr>
				<td align="left">Isenção Doador de Sangue:</td>
				<td align="left">
					<input type="checkbox" value="1" $opcaoIsencaoDoadorSangue name="isencaodoadorsangue" title="Isenção Doador de Sangue">
				</td>
			</tr>
			<tr>
				<td align="left">Data Início Isenção Doador de Sangue:</td>
				<td align="left"><input name="datainiciodoadorsangue" type="text" title="Data Início Isenção Doador de Sangue" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.datainiciodoadorsangue);" value="$dataInicioDoadorSangue"/><input name="horainiciodoadorsangue" type="text" size="10" value="$horaInicioDoadorSangue" maxlength="8" onKeyPress="MascaraHora(formCadastro.horainiciodoadorsangue);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">Data Final Isenção Doador de Sangue:</td>
				<td align="left"><input name="datafimdoadorsangue" type="text" title="Data Final Isenção Doador de Sangue" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.datafimdoadorsangue);" value="$dataFinalDoadorSangue"/><input name="horafinaldoadorsangue" type="text" size="10" value="$horaFinalDoadorSangue" maxlength="8" onKeyPress="MascaraHora(formCadastro.horafinaldoadorsangue);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">Isenção NIS:</td>
				<td align="left"><input type="checkbox" value="1" $opcaoIsencaoNIS name="isencaonis" title="Isenção NIS"></td>
			</tr>
			<tr>
				<td align="left">Data Início Isenção NIS:</td>
				<td align="left"><input name="datainicioisencaonis" type="text" title="Data Início Isenção NIS" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.datainicioisencaonis);" value="$dataInicioNIS"/><input name="horainicioisencaonis" type="text" size="10" value="$horaInicioIsencaoNIS" maxlength="8" onKeyPress="MascaraHora(formCadastro.horainicioisencaonis);">00:00:00</td>
			</tr>
			<tr>
				<td align="left">Data Final Isenção NIS:</td>
				<td align="left"><input name="datafimisencaonis" type="text" title="Data Final Isenção NIS" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.datafimisencaonis);" value="$dataFinalNIS"/><input name="horafinalisencaonis" type="text" size="10" value="$horaFinalIsencaoNIS" maxlength="8" onKeyPress="MascaraHora(formCadastro.horafinalisencaonis);">00:00:00</td>
			</tr>
			
			<tr>
				<td align="left">Cota Racial:</td>
				<td align="left"><input type="checkbox" value="1" $opcaoCotaRacial name="cotaracial" title="Cota Racial"></td>
			</tr>
			<tr>
				<td align="left">Multiplas Inscrições:</td>
				<td align="left"><input type="checkbox" value="1" $opcaoMultiplaInscricoes name="multiplasinscricoes" title="Multiplas Inscrições"></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<button onclick="location.href='?nivel=painel_concurso&acao=exibir_cadastro'" class="button" type="button">Novo</button>
					<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroConcurso();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>