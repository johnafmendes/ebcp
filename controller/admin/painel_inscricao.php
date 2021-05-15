<?php
	include("../view/template.class.php");
	include("../model/dao/ConcursoDAO.php");
	include("../model/dao/CandidatoDAO.php");
	include("../model/dao/PneDAO.php");
	include("../model/dao/CargoDAO.php");
	include("../model/dao/CidadeProvaDAO.php");
	include("../model/dao/LinguaConcursoDAO.php");
	include("../model/dao/InscricaoDAO.php");
	include("../model/dao/EstadoDAO.php");
	include("../model/dao/EscolaridadeDAO.php");
	include("../model/entidade/Inscricao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idinscricao']) ? $idInscricao = $_GET['idinscricao'] : $idInscricao = 0;
	isset($_POST['codigocpf']) ? $codigocpf = $_POST['codigocpf'] : (isset($_GET['codigocpf']) ? $codigocpf = $_GET['codigocpf'] : $codigocpf = "");
	isset($_POST['codigooucpf']) ? $codigooucpf = $_POST['codigooucpf'] : (isset($_GET['codigooucpf']) ? $codigooucpf = $_GET['codigooucpf'] : $codigooucpf = "");
	
	$inscricao = new Inscricao();
	
	/*dados do formulário*/
	isset($_POST['idinscricao']) ? ($_POST['idinscricao'] == "" ? $inscricao->setidinscricao(null) : $inscricao->setidinscricao($_POST['idinscricao'])) : $inscricao->setidinscricao(null);
	isset($_POST['idconcurso']) ? $inscricao->setidconcurso($_POST['idconcurso']) : $inscricao->setidconcurso(null);
	isset($_POST['idcargo']) ? $inscricao->setidcargo($_POST['idcargo']) : $inscricao->setidcargo(null);
	isset($_POST['idcidadeprova']) ? $inscricao->setidcidadeprova($_POST['idcidadeprova']) : $inscricao->setidcidadeprova(null);	
	isset($_POST['idlinguaestrangeira']) ? $inscricao->setidlinguaestrangeira($_POST['idlinguaestrangeira']) : $inscricao->setidlinguaestrangeira(null);
	isset($_POST['doadorsangue']) ? $inscricao->setdoadorsangue($_POST['doadorsangue']) : $inscricao->setdoadorsangue("");
	isset($_POST['isencaonis']) ? $inscricao->setisencao($_POST['isencaonis']) : $inscricao->setisencao("");
	isset($_POST['nis']) ? $inscricao->setnis($_POST['nis']) : $inscricao->setnis(null);
	isset($_POST['idpne']) ? ($_POST['idpne'] != "" ? $inscricao->setidpne($_POST['idpne']) : $inscricao->setidpne(null)) : $inscricao->setidpne(null);
	isset($_POST['pneespecifico']) ? $inscricao->setpneespecifico($_POST['pneespecifico']) : $inscricao->setpneespecifico(null);
	isset($_POST['afrodescendente']) ? $inscricao->setafrodescendente($_POST['afrodescendente']) : $inscricao->setafrodescendente(null);
	isset($_POST['provaampliada']) ? $inscricao->setprovaampliada($_POST['provaampliada']) : $inscricao->setprovaampliada(null);
	isset($_POST['facilacesso']) ? $inscricao->setsalafacilacesso($_POST['facilacesso']) : $inscricao->setsalafacilacesso(null);
	isset($_POST['auxiliotranscricao']) ? $inscricao->setauxiliotranscricao($_POST['auxiliotranscricao']) : $inscricao->setauxiliotranscricao(null);
	isset($_POST['outrasolicitacao']) ? $inscricao->setoutrasolicitacao($_POST['outrasolicitacao']) : $inscricao->setoutrasolicitacao(null);	
	isset($_POST['isencao']) ? $inscricao->setisencao($_POST['isencao']) : $inscricao->setisencao(null);
	isset($_POST['homologaisento']) ? $inscricao->sethomologaisento($_POST['homologaisento']) : $inscricao->sethomologaisento(null);
	isset($_POST['homologado']) ? $inscricao->sethomologado($_POST['homologado']) : $inscricao->sethomologado(null);
	isset($_SESSION['idCandidato']) ? $inscricao->setidcandidato($_SESSION['idCandidato']) : $inscricao->setidcandidato(null);
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	$conteudoPesquisa = "";
	
	$candidatoDAO = new CandidatoDAO();
	$inscricaoDAO = new InscricaoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Inscrição Candidato";
					$conteudo = montaFormularioCadastroInscricao(null);
					if($codigooucpf == 0){//por inscricao
						$resultadoPesquisa = $inscricaoDAO->getInscricaoPorId($codigocpf);
					} else { //por cpf
						$resultadoPesquisa = $inscricaoDAO->getInscricaoPorCPF($codigocpf);
					}
					
					$conteudoPesquisa = montaFormularioPesquisaInscricao($resultadoPesquisa);

					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_inscricao" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Inscrição";
					$inscricao = $inscricaoDAO->getInscricaoPorId($idInscricao)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroInscricao($inscricao);
					if($codigooucpf == 0){//por inscricao
						$resultadoPesquisa = $inscricaoDAO->getInscricaoPorId($codigocpf);
					} else { //por cpf
						$resultadoPesquisa = $inscricaoDAO->getInscricaoPorCPF($codigocpf);
					}
					
					$conteudoPesquisa = montaFormularioPesquisaInscricao($resultadoPesquisa);
										
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
				break;
				
			case "salvar" :
				if($_SESSION['nivel'] < 3){
					$titulo = $tituloNivelNegado;
					$conteudo = $conteudoNivelNegado;
					$conteudoLateral =  "";
					break;
				}
				$conteudoSucesso = "Dados salvo com sucesso.";
				$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				if($inscricao->getidinscricao() == null){//inserir
					$conteudo = $conteudoErro;
				} else { //atualizar
					if($inscricaoDAO->update($inscricao)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_inscricao&acao=exibir_inscricao&idinscricao=" . $resultado->id_inscricao . "\">" . $resultado->id_inscricao . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_inscricao&acao=exibir_inscricao&idinscricao=" . $resultado->id_inscricao . "\">" . $resultado->titulo . "</a></td>"
						. "<td align=\"left\">" . $resultado->cargo . "</td>"
						. "<td align=\"center\">" . ($resultado->homologado == 1 ? "Sim" : "Não") . "</td>"
						. "<td align=\"center\">" . $resultado->cidade . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_candidato&acao=exibir_candidato&idcandidato=" . $resultado->id_candidato . "\" title=\"" . $resultado->nome . "\">" . $resultado->id_candidato . "</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaInscricao($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Inscrição</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_inscricao&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="codigooucpf" value="0">Inscrição <input type="radio" name="codigooucpf" value="1">CPF</td>
						<td align="left"><input name="codigocpf" required title="Inscrição ou CPF" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Inscrição:</td>
					<td align="left">Concurso</td>
					<td align="left">Cargo</td>
					<td align="center">Homologado</td>
					<td align="center">Cidade Prova</td>
					<td align="center">ID Candidato</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
			<br/>
		</div>
	</div>
FORM;
		
		return $str;
	}

	function opcoesPNE(){
		$pneDAO = new PneDAO();
		$listaPNE = $pneDAO->listarPNE();
		
		$str = "";	

		if($listaPNE != null){
			while($pne = $listaPNE->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $pne->id_pne . "\">" . $pne->tipo . "</option>";
			}
		}
		
		return $str;
	}
	
	function opcoesCargo($idConcurso, $idCargo){
		$str = "";
		$cargoDAO = new CargoDAO();
		$listaCargos = $cargoDAO->listarCargosPorConcurso($idConcurso);
		if($listaCargos != null){
			while($cargo = $listaCargos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cargo->id_cargo . "\" " . ($idCargo == $cargo->id_cargo ? "selected=\"selected\"" : "") . ">" . $cargo->titulo . "</option>";
			}
		}
		return $str;
	}	
	
	function opcoesCidadeProva($idConcurso, $idCidade){
		$cidadeProvaDAO = new CidadeProvaDAO();
		
		$listaCidades = $cidadeProvaDAO->listarCidadesProvaPorConcurso($idConcurso);
		$str = "";
		$numCidades = 0;
		if($listaCidades != null){
			$str .= "<tr>"
				. "<td align=\"left\">* Cidade da Prova:</td>"
				. "<td align=\"left\"><select name=\"idcidadeprova\" title=\"Cidade da Prova\" id=\"validar\" required style=\"width: 400px;\">"
					. "<option value=\"\">selecione</option>";
			
			while($cidade = $listaCidades->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cidade->id_cidade_prova . "\"" . ($idCidade == $cidade->id_cidade_prova ? "selected=\"selected\"" : "") . ">" . $cidade->cidade . ", " . $cidade->sigla . "</option>";
				$numCidades++;
			}
			$str .= "</select>"
				. "</td>"
				. "</tr>";						
		}
		return $numCidades > 1 ? $str : "";
	}
	
	function opcoesLinguaEstrangeira($idConcurso, $idLingua){	
		$linguaConcursoDAO = new LinguaConcursoDAO();
		$listaLinguas = $linguaConcursoDAO->listarLinguasEstrangeirasPorConcurso($idConcurso);
		$str = "";
		if($listaLinguas != null){
			$str .= "<tr>"
					. "<td align=\"left\">* Língua Estrangeira:</td>"
					. "<td align=\"left\"><select name=\"idlinguaestrangeira\" id=\"validar\" required style=\"width: 400px;\">"
					. "<option value=\"\">selecione</option>";
				
			while($lingua = $listaLinguas->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $lingua->id_lingua_estrangeira . "\"" . ($idLingua == $lingua->id_lingua_estrangeira ? "selected=\"selected\"" : "") . ">" . $lingua->lingua . "</option>";
			}
			
			$str .= "</select>"
				. "</td>"
				. "</tr>";
				
		}
		return $str;
	}
	
	function opcoesDoadorSangue($idConcurso, $doador){
		$str = "";

		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->isencao_doador_sangue == 1){
				$str .= "<tr>"
					. "<td align=\"left\">* Doador de Sangue?:</td>"
					. "<td align=\"left\"><select name=\"doadorsangue\" title=\"Doador de Sangue\" id=\"validar\" required style=\"width: 400px;\">"
							. "<option value=\"\">selecione</option>"
							. "<option value=\"1\" " . ($doador == 1 ? "selected=\"selected\"" : "") . ">Sim</option>"
							. "<option value=\"0\" " . ($doador == 0 ? "selected=\"selected\"" : "") . ">Não</option>"
						. "</select>"
					. "</td>"
					. "</tr>";
			}
		}
		
		return $str;
	}
	
	function opcoesAfrodescendente($idConcurso, $afro){
		$str = "";
	
		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->cota_racial == 1){
				$str .= "<tr>"
					. "<td align=\"left\">* Afrodescendente?:</td>"
					. "<td align=\"left\"><select name=\"afrodescendente\" title=\"Afrodescendente\" id=\"validar\" required style=\"width: 400px;\">"
							. "<option value=\"\">selecione</option>"
							. "<option value=\"1\" " . ($afro == 1 ? "selected=\"selected\"" : "") . ">Sim</option>"
							. "<option value=\"0\" " . ($afro == 0 ? "selected=\"selected\"" : "") . ">Não</option>"
						. "</select>"
					. "</td>"
					. "</tr>";
			}
		}
	
		return $str;
	}
	
	function opcoesIsencaoNis($idConcurso, $isencao, $nis){
		$str = "";
		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->isencao_nis == 1){
				$str .= "<tr>"
					. "<td align=\"left\">* Concorre a Isenção?:</td>"
					. "<td align=\"left\"><select name=\"isencaonis\" title=\"Concorre a Isenção\" id=\"validar\" required onchange=\"habilitaNIS(this.value);\" style=\"width: 400px;\">"
							. "<option value=\"\">selecione</option>"
							. "<option value=\"1\" " . ($isencao == 1 ? "selected=\"selected\"" : "") . ">Sim</option>"
							. "<option value=\"0\" " . ($isencao == 0 ? "selected=\"selected\"" : "") . ">Não</option>"
						. "</select>"
					. "</td>"
					. "</tr>"
					. "<tr>"		
					. "<td align=\"left\">* NIS:</td>"
					. "<td align=\"left\"><input type=\"text\" name=\"nis\" id=\"nis\" title=\"NIS\" size=\"55\" " . ($isencao == 0 ? "disabled=\"disabled\"" : "") . " value=\"" . $nis . "\" required ></td>"
					. "</tr>";
			}
		}
	
		return $str;
	}
	
	function opcoesProvaAmpliada($provaAmpliada){
		return "<tr>"
			. "<td align=\"center\"><input name=\"provaampliada\" type=\"checkbox\" id=\"provaampliada\" value=\"1\"" . ($provaAmpliada == 1 ? "checked" : "") . "/></td>"
			. "<td align=\"left\">Deficiência Visual séria não corrigida pelo uso de óculos<br /><b>Necessito de Prova Ampliada</b></td>"
			. "</tr>";
	}
	
	function opcoesFacilAcesso($facilAcesso){
		return "<tr>"
			. "<td align=\"center\"><input name=\"facilacesso\" type=\"checkbox\" id=\"facilacesso\" value=\"1\" " . ($facilAcesso == 1 ? "checked" : "" ) . "/></td>"
			. "<td align=\"left\">Deficiência Física com séria dificuldade de locomoção<br /><b>Necessito de Sala de fácil Acesso</b></td>"
			. "</tr>";
	}
	
	function opcoesAuxilioTranscricao($auxilioTranscricao){
		return "<tr>"
			. "<td align=\"center\"><input name=\"auxiliotranscricao\" type=\"checkbox\" id=\"auxiliotranscricao\" value=\"1\" " . ($auxilioTranscricao == 1 ? "checked" : "") . "/></td>"
			. "<td align=\"left\">Estado de saúde que impossibilite a marcação da Folha de Respostas<br /><b>Necessito de Auxílio para Transcrição</b></td>"
			. "</tr>";
	}
	
	function opcoesHomologaIsento($homologaIsento){
		return "<tr>"
			. "<td align=\"left\">Homologa Isento:</td>"
			. "<td align=\"left\"><input name=\"homologaisencao\" type=\"checkbox\" id=\"homologaisencao\" value=\"1\" " . ($homologaIsento == 1 ? "checked" : "") . "/></td>"
			. "</tr>";
	}
	
	function opcoesHomologado($homologado){
		return "<tr>"
			. "<td align=\"left\">Homologado:</td>"
			. "<td align=\"left\"><input name=\"homologado\" type=\"checkbox\" id=\"homologado\" value=\"1\" " . ($homologado == 1 ? "checked" : "") . "/></td>"
			. "</tr>";
	}
	
	function montaFormularioCadastroInscricao($inscricao){
		$opcoesPNE = opcoesPNE($inscricao->id_pne);
		$opcoesCargo = opcoesCargo($inscricao->id_concurso, $inscricao->id_cargo);
		$opcoesCidadeProva = opcoesCidadeProva($inscricao->id_concurso, $inscricao->id_cidade_prova);
		$opcoesLinguaEstrangeira = opcoesLinguaEstrangeira($inscricao->id_concurso, $inscricao->id_lingua_estrangeira);
		$opcoesDoadorSangue = opcoesDoadorSangue($inscricao->id_concurso, $inscricao->doador_sangue);
		$opcoesIsencaoNis = opcoesIsencaoNis($inscricao->id_concurso, $inscricao->isencao, $inscricao->nis);
		$opcoesAfrodescendente = opcoesAfrodescendente($inscricao->id_concurso, $inscricao->afrodescendente);
		$opcoesProvaAmpliada = opcoesProvaAmpliada($inscricao->prova_ampliada);
		$opcoesFacilAcesso = opcoesFacilAcesso($inscricao->sala_facil_acesso);
		$opcoesAuxilioTranscricao = opcoesAuxilioTranscricao($inscricao->auxilio_transcricao);
		$opcoesHomologaIsento = opcoesHomologaIsento($inscricao->homologa_isento);
		$opcoesHomologado = opcoesHomologado($inscricao->homologado);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_inscricao&acao=salvar" >
		<input type="hidden" name="idinscricao" value="$inscricao->id_inscricao">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">Inscrição:</td>
				<td align="left"><input name="inscricao" type="text" id="inscricao" value="$inscricao->id_inscricao" size="20" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="left">Candidato:</td>
				<td align="left"><input name="candidato" type="text" id="candidato" value="$inscricao->nome" size="55" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="left">Concurso:</td>
				<td align="left"><input name="concurso" type="text" id="concurso" value="$inscricao->titulo" size="55" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="left">* Cargo:</td>
				<td align="left"><select name="idcargo" id="validar" title="Cargo" required style="width: 400px;">
						<option value="">selecione</option>
						$opcoesCargo
					</select>
				</td>
			</tr>
			$opcoesCidadeProva
			$opcoesLinguaEstrangeira
			$opcoesDoadorSangue
			$opcoesIsencaoNis
			$opcoesAfrodescendente
			<tr>
				<td align="left">* Tipo de Necessidade Especial?:</td>
				<td align="left"><select name="idpne" title="Tipo de Necessidade Especial" style="width: 400px;">
						<option value="">selecione</option>
						$opcoesPNE
					</select>
				</td>
			</tr>			
			<tr>
				<td align="left">Especifique:</td>
				<td align="left"><input name="pneespecifico" type="text" id="pneespecifico" size="55" value="$inscricao->pne_especifico"/></td>
			</tr>
			$opcoesProvaAmpliada
			$opcoesFacilAcesso
			$opcoesAuxilioTranscricao
			<tr>
				<td align="left">Outras solicitações:</td>
				<td align="left"><input name="outrasolicitacao" type="text" id="outrasolicitacao" size="55" value="$inscricao->outra_solicitacao"/></td>
			</tr>
			$opcoesHomologaIsento
			$opcoesHomologado
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<div class="rodapeBotoes">
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaInscricaoCandidato();">
					</div>
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>