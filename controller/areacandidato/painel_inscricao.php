<?php
	include("view/template.class.php");
	include("model/dao/ConcursoDAO.php");
	include("model/dao/CandidatoDAO.php");
	include("model/dao/PneDAO.php");
	include("model/dao/CargoDAO.php");
	include("model/dao/CidadeProvaDAO.php");	
	include("model/dao/LinguaConcursoDAO.php");
	include("model/dao/InscricaoDAO.php");
	include("model/entidade/Inscricao.php");	
	include("controller/coluna_lateral.php");
	include("controller/areacandidato/coluna_lateral.php");
	include("model/dao/BoletoDAO.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = "";
	
 	$inscricao = new Inscricao();
	/*dados do formulário*/
	isset($_POST['idconcurso']) ? $inscricao->setidconcurso($_POST['idconcurso']) : $inscricao->setidconcurso(null);
	isset($_POST['idcargo']) ? $inscricao->setidcargo($_POST['idcargo']) : $inscricao->setidcargo(null);
	isset($_POST['idcidadeprova']) ? $inscricao->setidcidadeprova($_POST['idcidadeprova']) : $inscricao->setidcidadeprova(null);	
	isset($_POST['idlinguaestrangeira']) ? $inscricao->setidlinguaestrangeira($_POST['idlinguaestrangeira']) : $inscricao->setidlinguaestrangeira(null);
	isset($_POST['doadorsangue']) ? $inscricao->setdoadorsangue($_POST['doadorsangue']) : $inscricao->setdoadorsangue("");
	isset($_POST['isencaonis']) ? $inscricao->setisencao($_POST['isencaonis']) : $inscricao->setisencao("");
	isset($_POST['nis']) ? $inscricao->setnis($_POST['nis']) : $inscricao->setnis(null);
	isset($_POST['idpne']) ? $inscricao->setidpne($_POST['idpne']) : $inscricao->setidpne(null);
	isset($_POST['pneespecifico']) ? $inscricao->setpneespecifico($_POST['pneespecifico']) : $inscricao->setpneespecifico(null);
	isset($_POST['afrodescendente']) ? $inscricao->setafrodescendente($_POST['afrodescendente']) : $inscricao->setafrodescendente(null);
	isset($_POST['provaampliada']) ? $inscricao->setprovaampliada($_POST['provaampliada']) : $inscricao->setprovaampliada(null);
	isset($_POST['facilacesso']) ? $inscricao->setsalafacilacesso($_POST['facilacesso']) : $inscricao->setsalafacilacesso(null);
	isset($_POST['auxiliotranscricao']) ? $inscricao->setauxiliotranscricao($_POST['auxiliotranscricao']) : $inscricao->setauxiliotranscricao(null);
	isset($_POST['outrasolicitacao']) ? $inscricao->setoutrasolicitacao($_POST['outrasolicitacao']) : $inscricao->setoutrasolicitacao(null);	
	isset($_SESSION['idCandidato']) ? $inscricao->setidcandidato($_SESSION['idCandidato']) : $inscricao->setidcandidato(null);
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu CPF ou Senha estão errados. Por favor, digite novamente, ou solicite a recuperação da senha.";
	$tituloLateral = "";
	$conteudoLateral = "";
	
	$concursoDAO = new ConcursoDAO();
	$inscricaoDAO = new InscricaoDAO();
	
	switch ($acao){
		case "salvar" :
			if (isset($_SESSION['autenticado'])){
				$inscricao->setdatainscricao(date("Y-m-d"));
// 				$conteudoSucesso = "Inscrição realizada com sucesso. <br/><br/>Imprima o boleto a seguir.";
				$conteudoErro = "Sua inscrição NÂO foi realizada. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				$idInscricao = $inscricaoDAO->salvar($inscricao);
				if($idInscricao > 0){
					$inscricao->setidinscricao($idInscricao);
					enviaEmailConfirmacaoInscricao($inscricao);
// 					$conteudo = $conteudoSucesso;
					$conteudo = montaFormularioResumoInscricao($inscricao);
				} else {
					$conteudo = $conteudoErro;
				}
	
				$titulo = "Resultado da Inscrição";
// 				$corpoTemplate = new Template("view/areaCandidato.tpl");
				$tituloLateral = $tituloLateralAutenticado;
				$conteudoLateral = $conteudoLateralAutenticado;
			} else {
				//falha na autenticacao
			}
			break;
			
		case "exibir_inscricoes_abertas" :		
			if (isset($_SESSION['autenticado'])){
// 				$corpoTemplate = new Template("view/areaCandidato.tpl");
				$tituloLateral = $tituloLateralAutenticado;
				$conteudoLateral = $conteudoLateralAutenticado;
				$titulo = "Inscrição";
				
				$listaConcursos = $concursoDAO->listarConcursosAbertos();
				
				if($listaConcursos != null){
					$conteudo = montaFormulario($listaConcursos, $_SESSION['idCandidato'], $idConcurso);
				} else {
					$conteudo = "Não há inscrições abertas no momento.";
				}
			} else {
				//falha na autenticacao
			}				
			break;
	}
		
	$menu = new Template("view/menu.tpl");
	$menu->set("", "active");
	
	if(isset($_SESSION['autenticado'])){
		$corpoTemplate = new Template("view/areaCandidato.tpl");
	}
	
	isset($corpoTemplate) ? $corpo = $corpoTemplate : $corpo = new Template("view/paginaInterna.tpl");
	$corpo->set("titulo", $titulo);
	$corpo->set("conteudo", $conteudo);
	$corpo->set("tituloLateral", $tituloLateral);
	$corpo->set("conteudoLateral", $conteudoLateral);
	$corpo->set("areaCandidato", $areaCandidato);
	
	/**
	 * Loads our layout template, settings its title and content.
	 */
	$layout = new Template("view/layout.tpl");
	$layout->set("title", "EBCP Concursos");
	$layout->set("menu", $menu->output());
	$layout->set("content", $corpo->output());
	
	/**
	 * Outputs the page with the user's profile.
	 */
	echo $layout->output();

	function opcoesConcursosAbertos($listaConcursos, $idConcurso){
		$str = "";	
	
		while($concurso = $listaConcursos->fetch(PDO::FETCH_OBJ)){
			$str .= "<option value=\"" . $concurso->id_concurso . "\"" . ($concurso->id_concurso == $idConcurso ? "selected=\"selected\"" : "") . ">" . $concurso->titulo . "</option>";
		}
		
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
	
	function opcoesCargo($idConcurso){
		$str = "";
		$cargoDAO = new CargoDAO();
		$listaCargos = $cargoDAO->listarCargosPorConcurso($idConcurso);
		if($listaCargos != null){
			while($cargo = $listaCargos->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cargo->id_cargo . "\">" . $cargo->titulo . "</option>";
			}
		}
		return $str;
	}	
	
	function opcoesCidadeProva($idConcurso){
		$cidadeProvaDAO = new CidadeProvaDAO();
		
		$listaCidades = $cidadeProvaDAO->listarCidadesProvaPorConcurso($idConcurso);
		$str = "";
		$numCidades = 0;
		$idCidadeProva = 0;
		if($listaCidades != null){
			$str .= "<tr>"
				. "<td align=\"left\">* Cidade da Prova:</td>"
				. "<td align=\"left\"><select name=\"idcidadeprova\" title=\"Cidade da Prova\" id=\"validar\" required style=\"width: 400px;\">"
					. "<option value=\"\">selecione</option>";
			
			while($cidade = $listaCidades->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $cidade->id_cidade_prova . "\">" . $cidade->cidade . ", " . $cidade->sigla . "</option>";
				$numCidades++;
				if($idCidadeProva == 0){
					$idCidadeProva = $cidade->id_cidade_prova;
				}
			}
			$str .= "</select>"
				. "</td>"
				. "</tr>";						
		}
		return $numCidades > 1 ? $str : "<input type=\"hidden\" name=\"idcidadeprova\" value=\"$idCidadeProva\">";
	}
	
	function opcoesLinguaEstrangeira($idConcurso){	
		$linguaConcursoDAO = new LinguaConcursoDAO();
		$listaLinguas = $linguaConcursoDAO->listarLinguasEstrangeirasPorConcurso($idConcurso);
		$str = "";
		if($listaLinguas != null){
			$str .= "<tr>"
					. "<td align=\"left\">* Língua Estrangeira:</td>"
					. "<td align=\"left\"><select name=\"idlinguaestrangeira\" id=\"validar\" required style=\"width: 400px;\">"
					. "<option value=\"\">selecione</option>";
				
			while($lingua = $listaLinguas->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $lingua->id_lingua_estrangeira . "\">" . $lingua->lingua . "</option>";
			}
			
			$str .= "</select>"
				. "</td>"
				. "</tr>";
				
		}
		return $str;
	}
	
	function opcoesDoadorSangue($idConcurso){
		$str = "";

		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->isencao_doador_sangue == 1){
				if($concurso->doador_sangue_data_inicio <= date("Y-m-d 00:00:00") && $concurso->doador_sangue_data_fim >= date("Y-m-d 00:00:00")){
					$str .= "<tr>"
						. "<td align=\"left\">* Doador de Sangue?:</td>"
						. "<td align=\"left\"><select name=\"doadorsangue\" title=\"Doador de Sangue\" id=\"validar\" required style=\"width: 400px;\">"
								. "<option value=\"\">selecione</option>"
								. "<option value=\"1\">Sim</option>"
								. "<option value=\"0\">Não</option>"
							. "</select>"
						. "</td>"
						. "</tr>";
				}
			}
		}
		
		return $str;
	}
	
	function opcoesAfrodescendente($idConcurso){
		$str = "";
	
		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->cota_racial == 1){
// 				if($concurso->doador_sangue_data_inicio <= date("Y-m-d 00:00:00") && $concurso->doador_sangue_data_fim >= date("Y-m-d 00:00:00")){
					$str .= "<tr>"
						. "<td align=\"left\">* Afrodescendente?:</td>"
						. "<td align=\"left\"><select name=\"afrodescendente\" title=\"Afrodescendente\" id=\"validar\" required style=\"width: 400px;\">"
								. "<option value=\"\">selecione</option>"
								. "<option value=\"1\">Sim</option>"
								. "<option value=\"0\">Não</option>"
							. "</select>"
						. "</td>"
						. "</tr>";
// 				}
			}
		}
	
		return $str;
	}
	
	function opcoesIsencaoNis($idConcurso){
		$str = "";
		if($idConcurso != null){
			$concursoDAO = new ConcursoDAO();
			$concurso = $concursoDAO->getConcursoPorID($idConcurso)->fetch(PDO::FETCH_OBJ);
			if($concurso->isencao_nis == 1){
				if($concurso->nis_data_inicio <= date("Y-m-d 00:00:00") && $concurso->nis_data_fim >= date("Y-m-d 00:00:00")){
					$str .= "<tr>"
						. "<td align=\"left\">* Concorre a Isenção?:</td>"
						. "<td align=\"left\"><select name=\"isencaonis\" title=\"Concorre a Isenção\" id=\"validar\" required onchange=\"habilitaNIS(this.value);\" style=\"width: 400px;\">"
								. "<option value=\"\">selecione</option>"
								. "<option value=\"1\">Sim</option>"
								. "<option value=\"0\">Não</option>"
							. "</select>"
						. "</td>"
						. "</tr>"
						. "<tr>"		
						. "<td align=\"left\">* NIS:</td>"
						. "<td align=\"left\"><input type=\"text\" name=\"nis\" id=\"nis\" title=\"NIS\" size=\"55\" disabled=\"disabled\" required ></td>"
						. "</tr>";
								
				}
			}
		}
	
		return $str;
	}
	
	function montaFormulario($listaConcursos, $idCandidato, $idConcurso){
		$opcoesConcursosAbertos = opcoesConcursosAbertos($listaConcursos, $idConcurso);
		$opcoesPNE = opcoesPNE();
		$opcoesCargo = opcoesCargo($idConcurso);
		$opcoesCidadeProva = opcoesCidadeProva($idConcurso);
		$opcoesLinguaEstrangeira = opcoesLinguaEstrangeira($idConcurso);
		$opcoesDoadorSangue = opcoesDoadorSangue($idConcurso);
		$opcoesIsencaoNis = opcoesIsencaoNis($idConcurso);
		$opcoesAfrodescendente = opcoesAfrodescendente($idConcurso);
		$conteudo = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=areacandidato/painel_inscricao&acao=salvar" >
		<input type="hidden" name="idCandidato" value="$idCandidato">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* Concursos Abertos:</td>
				<td align="left"><select name="idconcurso" id="validar" title="Concursos Abertos" required onchange="javascript: window.location.href = '?nivel=areacandidato/painel_inscricao&acao=exibir_inscricoes_abertas&idconcurso=' + this.value;" style="width: 400px;">
						<option value="">selecione</option>
						$opcoesConcursosAbertos
					</select>
				</td>
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
				<td align="left">* Portador de Necessidades Especiais?</td>
				<td align="left"><select name="deficiente" title="Portador de Necessidades Especiais" required id="validar" onchange="habilitaPNE(this.value);" style="width: 400px;">
						<option value="" selected="selected">selecione</option>				
						<option value="nao" >Não</option>
						<option value="sim">Sim</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Tipo de Necessidade Especial?:</td>
				<td align="left"><select name="idpne" disabled="disabled" title="Tipo de Necessidade Especial" style="width: 400px;">
						<option value="">selecione</option>
						$opcoesPNE
					</select>
				</td>
			</tr>			
			<tr>
				<td align="left">Especifique:</td>
				<td align="left"><input name="pneespecifico" type="text" id="pneespecifico" size="55" disabled="disabled"/></td>
			</tr>
			<tr>
				<td align="center"><input name="provaampliada" type="checkbox" id="provaampliada" value="1" disabled="disabled"/></td>
				<td align="left">Deficiência Visual séria não corrigida pelo uso de óculos<br /><b>Necessito de Prova Ampliada</b></td>
			</tr>
			<tr>
				<td align="center"><input name="facilacesso" type="checkbox" id="facilacesso" value="1" disabled="disabled"/></td>
				<td align="left">Deficiência Física com séria dificuldade de locomoção<br /><b>Necessito de Sala de fácil Acesso</b></td>
			</tr>
			<tr>
				<td align="center"><input name="auxiliotranscricao" type="checkbox" id="auxiliotranscricao" value="1" disabled="disabled"/></td>
				<td align="left">Estado de saúde que impossibilite a marcação da Folha de Respostas<br /><b>Necessito de Auxílio para Transcrição</b></td>
			</tr>
			<tr>
				<td align="center"><input name="outrasolicitacao1" type="checkbox" id="outrasolicitacao1" value="1" disabled="disabled"/></td>
				<td align="left">Outras solicitações:<input name="outrasolicitacao" type="text" disabled="disabled" id="outrasolicitacao" size="55"/></td>
			</tr>
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
		return $conteudo;
	}
	
	function enviaEmailConfirmacaoInscricao($inscricao){
		$candidatoDAO = new CandidatoDAO();
		$candidato = $candidatoDAO->getCandidatoPorID($inscricao->getidcandidato())->fetch(PDO::FETCH_OBJ);

		$concursoDAO = new ConcursoDAO();
		$concurso = $concursoDAO->getConcursoPorID($inscricao->getidconcurso())->fetch(PDO::FETCH_OBJ);

		$cargoDAO = new CargoDAO();
		$cargo = $cargoDAO->getCargoPorID($inscricao->getidcargo())->fetch(PDO::FETCH_OBJ);
		
		$cidadeProvaDAO = new CidadeProvaDAO();
		$totalCidadesProva = $cidadeProvaDAO->listarCidadesProvaPorConcurso($inscricao->getidconcurso())->rowCount();
		$cidade = $cidadeProvaDAO->getCidadePorID($inscricao->getidcidadeprova())->fetch(PDO::FETCH_OBJ);
		
		$para = $candidato->email;
		$assunto = "EBCP Concursos - Confirmação de Inscrição";
		$body = "EBCP Concursos informa que sua inscrição foi realizada com sucesso: <br/><br/>"
				. "Sr(a) " . $candidato->nome . ", <br/><br/>"
				. "Sua inscrição no concurso: " . $concurso->titulo . " foi confirmado em nosso sistema com os seguintes dados:"
				. "<br/><br/>Inscrição Número: " . $inscricao->getidinscricao()
				. "<br/>CPF: " . $candidato->cpf
				. "<br/>Cargo: " . $cargo->titulo 
				. "<br/>Valor: R$ " . number_format($cargo->valor_inscricao, 2, ',', '.');
			$body .= ($totalCidadesProva > 1 ? "<br/>Cidade Prova: " . $cidade->cidade : "");
			$body .= "<br/><br/>Para dúvidas, <a href=\"mailto:concurso@ebcpconcursos.com.br\">concurso@ebcpconcursos.com.br</a> ou "
				. "ligue para (44) 3333-6666"
				. "<br/><br/>Acompanhe as nossas atualizações em nosso site."
				. "<br/><br/>EBCP Concursos";
		$headers  = "MIME-Version: 1.0 \r\n"
				. "Content-type: text/html; charset=utf-8 \r\n"
				. "From: concurso@ebcpconcursos.com.br \r\n";
			
		if(mail($para, $assunto, $body, $headers)){
			return true;
		} else {
			return false;
		}
	}
	
	function montaFormularioResumoInscricao($inscricao){
		$candidatoDAO = new CandidatoDAO();
		$candidato = $candidatoDAO->getCandidatoPorID($inscricao->getidcandidato())->fetch(PDO::FETCH_OBJ);
		
		$concursoDAO = new ConcursoDAO();
		$concurso = $concursoDAO->getConcursoPorID($inscricao->getidconcurso())->fetch(PDO::FETCH_OBJ);
		
		$cargoDAO = new CargoDAO();
		$cargo = $cargoDAO->getCargoPorID($inscricao->getidcargo())->fetch(PDO::FETCH_OBJ);

		$boletoDAO = new BoletoDAO();
		$boleto = $boletoDAO->getBoletoPorID($concurso->id_boleto)->fetch(PDO::FETCH_OBJ);

		$cidadeProvaDAO = new CidadeProvaDAO();
		$totalCidadesProva = $cidadeProvaDAO->listarCidadesProvaPorConcurso($inscricao->getidconcurso())->rowCount();
		$cidade = $cidadeProvaDAO->getCidadePorID($inscricao->getidcidadeprova())->fetch(PDO::FETCH_OBJ);
		
		$str = "EBCP Concursos informa que sua inscrição foi realizada com sucesso: <br/><br/>"
				. "Sr(a) " . $candidato->nome . ", <br/><br/>"
				. "Sua inscrição no concurso: " . $concurso->titulo . " foi confirmado em nosso sistema com os seguintes dados:"
				. "<br/><br/>Inscrição Número: " . $inscricao->getidinscricao()
				. "<br/>CPF: " . $candidato->cpf
				. "<br/>Cargo: " . $cargo->titulo
				. "<br/>Valor: R$ " . number_format($cargo->valor_inscricao, 2, ',', '.');
				$str .= ($totalCidadesProva > 1 ? "<br/>Cidade Prova: " . $cidade->cidade : "");
				$str .= "<p><center><form method=\"POST\" action=\"controller/boletos/" . $boleto->nome_arquivo . "\" target=\"_blank\" name=\"formBoleto".$inscricao->getidinscricao()."\" id=\"formBoleto\">"
					. "<input name=\"agencia\" type=\"hidden\" id=\"agencia\" value=\"" . $boleto->banco_agencia . "\">"
					. "<input name=\"conta\" type=\"hidden\" id=\"conta\" value=\"" . $boleto->conta_numero . "\">"
					. "<input name=\"conta_dv\" type=\"hidden\" id=\"conta_dv\" value=\"" . $boleto->conta_dv . "\">"
					. "<input name=\"cedente_codigo\" type=\"hidden\" id=\"cedente_codigo\" value=\"" . $boleto->cedente_codigo . "\">"
					. "<input name=\"cedente_identificacao\" type=\"hidden\" id=\"cedente_identificacao\" value=\"" . $boleto->cedente_identificacao . "\">"
					. "<input name=\"cedente_cpf_cnpj\" type=\"hidden\" id=\"cedente_cpf_cnpj\" value=\"" . $boleto->cedente_cnpj . "\">"
					. "<input name=\"cedente_endereco\" type=\"hidden\" id=\"cedente_endereco\" value=\"" . $boleto->cedente_endereco . "\">"
					. "<input name=\"cedente_cidade_uf\" type=\"hidden\" id=\"cedente_cidade_uf\" value=\"" . $boleto->cedente_cidade . ", " . $boleto->cedente_estado . "\">"
					. "<input name=\"cedente_razao_social\" type=\"hidden\" id=\"cedente_razao_social\" value=\"" . $boleto->cedente_razao_social . "\">"									
					. "<input name=\"demonstrativo1\" type=\"hidden\" id=\"demonstrativo1\" value=\"Concurso: " . $concurso->titulo . "\">"
					. "<input name=\"demonstrativo2\" type=\"hidden\" id=\"demonstrativo2\" value=\"Cargo: " . $cargo->titulo . "\">"
					. "<input name=\"demonstrativo3\" type=\"hidden\" id=\"demonstrativo3\" value=\"Inscrição: " . $inscricao->getidinscricao() . "\">"
					. "<input name=\"demonstrativo4\" type=\"hidden\" id=\"demonstrativo4\" value=\"\">"
					. "<input name=\"nossoNumero\" type=\"hidden\" id=\"nossoNumero\" value=\"" . $inscricao->getidinscricao() . "\">"
					. "<input name=\"numDocumento\" type=\"hidden\" id=\"numDocumento\" value=\"" . $inscricao->getidinscricao() . "\">"
					. "<input name=\"vencimento\" type=\"hidden\" id=\"vencimento\" value=\"" . date("d/m/Y", strtotime($concurso->vencimento_boleto)) . "\">"
					. "<input name=\"valor\" type=\"hidden\" id=\"valor\" value=\"" . number_format($cargo->valor_inscricao, 2, ",", ".") . "\">"
					. "<input name=\"sacado_nome\" type=\"hidden\" id=\"sacado_nome\" value=\"" . $candidato->nome . "\">"
					. "<input name=\"sacado_endereco\" type=\"hidden\" id=\"sacado_endereco\" value=\"" . $candidato->endereco . "\">"
					. "<input name=\"sacado_endereco1\" type=\"hidden\" id=\"sacado_endereco1\" value=\"" . $candidato->cidade . ", ". $candidato->sigla . " - " . $candidato->cep . "\">"
					. "<input onClick=\"document.formBoleto".$inscricao->getidinscricao().".submit()\" name=\"Button\" type=\"button\" value=\"Gerar Boleto\"></form></center></p>"
				. "<br/><br/>Para dúvidas, <a href=\"mailto:concurso@ebcpconcursos.com.br\">concurso@ebcpconcursos.com.br</a> ou "
				. "ligue para (44) 3333-6666"
				. "<br/><br/>Um e-mail com esses dados foram enviados para sua conta de e-mail: " . $candidato->email
				. "<br/><br/>Acompanhe as nossas atualizações em nosso site."
				. "<br/><br/>EBCP Concursos";
		
		return $str;
	}
?>