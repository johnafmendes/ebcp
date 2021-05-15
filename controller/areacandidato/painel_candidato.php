<?php
	include("view/template.class.php");
	include("model/dao/CandidatoDAO.php");
	include("model/dao/EstadoDAO.php");
	include("model/dao/EscolaridadeDAO.php");
	include("model/entidade/Candidato.php");
	include("controller/coluna_lateral.php");
	include("controller/areacandidato/coluna_lateral.php");
	
	isset($_POST['cpf']) ? $cpf = $_POST['cpf'] : $cpf = "";	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_POST['senha']) ? $senha = $_POST['senha'] : $senha = "";
	
	$candidato = new Candidato();
	/*dados do formulário*/
	isset($_POST['idCandidato']) ? ($_POST['idCandidato'] == "" ? $candidato->setidcandidato(null) : $candidato->setidcandidato($_POST['idCandidato'])) : $candidato->setidcandidato(null);
	isset($_POST['nome']) ? $candidato->setnome($_POST['nome']) : $candidato->setnome(null);
	isset($_POST['rg']) ? $candidato->setrg($_POST['rg']) : $candidato->setrg(null);
	isset($_POST['orgaoemissorrg']) ? $candidato->setorgaoemissorrg($_POST['orgaoemissorrg']) : $candidato->setorgaoemissorrg(null);
	isset($_POST['dataemissaorg']) ? $candidato->setdataemissaorg($_POST['dataemissaorg']) : $candidato->setdataemissaorg(null);
	isset($_POST['cpf']) ? $candidato->setcpf($_POST['cpf']) : $candidato->setcpf(null);
	isset($_POST['estadocivil']) ? $candidato->setestadocivil($_POST['estadocivil']) : $candidato->setestadocivil(null);
	isset($_POST['sexo']) ? $candidato->setsexo($_POST['sexo']) : $candidato->setsexo(null);
	isset($_POST['datanascimento']) ? $candidato->setdatanascimento($_POST['datanascimento']) : $candidato->setdatanascimento(null);
	isset($_POST['nomepai']) ? $candidato->setnomepai($_POST['nomepai']) : $candidato->setnomepai(null);
	isset($_POST['nomemae']) ? $candidato->setnomemae($_POST['nomemae']) : $candidato->setnomemae(null);
	isset($_POST['endereco']) ? $candidato->setendereco($_POST['endereco']) : $candidato->setendereco(null);
	isset($_POST['numeroendereco']) ? $candidato->setnumeroendereco($_POST['numeroendereco']) : $candidato->setnumeroendereco(null);
	isset($_POST['complementoendereco']) ? $candidato->setcomplementoendereco($_POST['complementoendereco']) : $candidato->setcomplementoendereco(null);
	isset($_POST['bairro']) ? $candidato->setbairro($_POST['bairro']) : $candidato->setbairro(null);
	isset($_POST['cidade']) ? $candidato->setcidade($_POST['cidade']) : $candidato->setcidade(null);
	isset($_POST['idestado']) ? $candidato->setidestado($_POST['idestado']) : $candidato->setidestado(null);
	isset($_POST['cep']) ? $candidato->setcep($_POST['cep']) : $candidato->setcep(null);
	isset($_POST['telefone']) ? $candidato->settelefone($_POST['telefone']) : $candidato->settelefone(null);
	isset($_POST['email']) ? $candidato->setemail($_POST['email']) : $candidato->setemail(null);
	isset($_POST['senha']) ? $candidato->setsenha($_POST['senha']) : $candidato->setsenha(null);
	isset($_POST['senha2']) ? $candidato->setsenha2($_POST['senha2']) : $candidato->setsenha2(null);
	isset($_POST['idescolaridade']) ? $candidato->setidescolaridade($_POST['idescolaridade']) : $candidato->setidescolaridade(null);
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu CPF ou Senha estão errados. Por favor, digite novamente, ou solicite a recuperação da senha.";
	$tituloLateral = "";
	$conteudoLateral = "";
	
	$candidatoDAO = new CandidatoDAO();
	
	switch ($acao){
		case "verificar_cpf" :
				if($candidatoDAO->verificaCPF($cpf)){//tem cadastro
					$titulo = "Cadastro Verificado";
					$conteudo = "Seu CPF já consta cadastrado em nosso sistema.<br/><br/>Por favor, acesse sua Área do Candidato.";
				} else {
					$titulo = "Dados do Candidato";
					$conteudo = montaFormulario(null, $cpf);
					$tituloLateral = "Orientações";
					$conteudoLateral = "Preencha todos os campos e clique em Confirmar e Salvar";
					$areaCandidato = "";
				}
			break;
			
		case "salvar" :
			$candidato->setcep(str_replace(array('.', '-'), "", $candidato->getcep()));
			$candidato->settelefone(str_replace(array('(', ')', '-'), "", $candidato->gettelefone()));
			$candidato->setdataemissaorg(implode("-",array_reverse(explode("/",$candidato->getdataemissaorg()))));
			$candidato->setdatanascimento(implode("-",array_reverse(explode("/",$candidato->getdatanascimento()))));
			$candidato->setsenha(md5($candidato->getsenha()));
			$conteudoSucesso = "Dados salvo com sucesso. <br/><br/>Faça sua inscrição, imprima o boleto, entre com recursos "
							. "e acesse suas provas clicando no menu ao lado.";
			$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
			if($candidato->getidcandidato() == null){//inserir
				if($candidatoDAO->salvar($candidato)){
					$conteudo = $conteudoSucesso;
				} else {
					$conteudo = $conteudoErro;
				}
			} else { //atualizar
				if($candidatoDAO->update($candidato)){
					$conteudo = $conteudoSucesso;
				} else {
					$conteudo = $conteudoErro;
				}
			}
			$titulo = "Resultado do Salvamento";
// 			$corpoTemplate = new Template("view/areaCandidato.tpl");
			$tituloLateral = $tituloLateralAutenticado;
			$conteudoLateral = $conteudoLateralAutenticado;
			
			$_SESSION['autenticado'] = true;
			$dadosCandidato = $candidatoDAO->autentica($candidato->getcpf(), $candidato->getsenha())->fetch(PDO::FETCH_OBJ);
			$_SESSION['idCandidato'] = $dadosCandidato->id_candidato;
			break;
			
		case "logoff" :
			session_unset();
			session_destroy();
			header('Location: ?nivel=inicio');
			break;
			
		case "exibir_cadastro" :		
			if (isset($_SESSION['autenticado'])){
				$dadosCandidato = $candidatoDAO->getCandidatoPorID($_SESSION['idCandidato']);
				if($dadosCandidato != null){	
					$candidato = $dadosCandidato->fetch(PDO::FETCH_OBJ);
					$titulo = "Dados do Candidato";
					$conteudo = montaFormulario($candidato, $candidato->cpf);
					
// 					$corpoTemplate = new Template("view/areaCandidato.tpl");
					$tituloLateral = $tituloLateralAutenticado;
					$conteudoLateral = $conteudoLateralAutenticado;
				} else {
					//falha na autenticacao
				}
			} else {
				//falha na autenticacao
			}				
			break;

		case "login" :
			$dadosCandidato = $candidatoDAO->autentica($cpf, md5($senha));
			if($dadosCandidato != null){
				$candidato = $dadosCandidato->fetch(PDO::FETCH_OBJ);
				$_SESSION['autenticado'] = true;
				$_SESSION['idCandidato'] = $candidato->id_candidato;
				header('Location: ?nivel=areacandidato/painel_candidato&acao=exibir_cadastro');
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

// 	function anos(){
// 		$inicio = date("Y");
// 		$fim = $inicio - 75;
// 		$anos = "";
// 		for($a = $fim; $a <= $inicio; $a++){
// 			$anos .= "<option value='$a'>$a</option>";
// 		}
// 		return $anos;
// 	}
	
	function opcoesSexo($sexo){
		return  "<option value=\"M\"" . ($sexo == "M" ? "selected=\"selected\"" : "") . ">Masculino</option>"
			. "<option value=\"F\"" . ($sexo == "F" ? "selected=\"selected\"" : "") . ">Feminino</option>";
	}
	
	function formataData($data){
		// $datetime is something like: 2014-01-31 13:05:59
		if($data != ""){
			$time = strtotime($data);
			return date("d/m/Y", $time);
		} else {
			return "";
		}
		// $my_format is something like: 01/31/14 1:05 PM
	}
	
	function opcoesEstado($idEstado){
		$str = "";
		$estadoDAO = new EstadoDAO();
		$listaEstados = $estadoDAO->listarEstados();
		if($listaEstados != null){
			while($uf = $listaEstados->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $uf->id_estado . "\"" . ($idEstado == $uf->id_estado ? "selected=\"selected\"" : "") . ">" . $uf->sigla . "</option>";
			}
		}
		return $str;
	}	
	
	function opcoesEstadoCivil($estadoCivil){
		return "<option value=\"Casado\"" . ($estadoCivil == "Casado" ? "selected=\"selected\"" : "") . ">Casado</option>"
				. "<option value=\"Solteiro\"" . ($estadoCivil == "Solteiro" ? "selected=\"selected\"" : "") . ">Solteiro</option>"
				. "<option value=\"Divorciado\"" . ($estadoCivil == "Divorciado" ? "selected=\"selected\"" : "") . ">Divorciado</option>"
				. "<option value=\"Viúvo\"" . ($estadoCivil == "Viúvo" ? "selected=\"selected\"" : "") . ">Viúvo</option>"
				. "<option value=\"Outro\"" . ($estadoCivil == "Outro" ? "selected=\"selected\"" : "") . ">Outro</option>";
	}
	
	function opcoesEscolaridade($idEscolaridade){
		$str = "";
		$escolaridadeDAO = new EscolaridadeDAO();
		$listaEscolaridade = $escolaridadeDAO->litarEscolaridades();
		if($listaEscolaridade != null){
			while($escolaridade = $listaEscolaridade->fetch(PDO::FETCH_OBJ)){
				$str .= "<option value=\"" . $escolaridade->id_escolaridade . "\"" . ($idEscolaridade == $escolaridade->id_escolaridade ? "selected=\"selected\"" : "") . ">" . $escolaridade->grau_instrucao . "</option>";
			}
		}
		return $str;
	}
	
	function montaFormulario($candidato, $cpf){
// 		$anos = anos();
		$opcoesSexo = opcoesSexo(isset($candidato->sexo) ? $candidato->sexo : "");
		$dataNascimento = formataData(isset($candidato->data_nascimento) ? $candidato->data_nascimento : "");
		$dataEmissaoRg = formataData(isset($candidato->data_emissao_rg) ? $candidato->data_emissao_rg : "");
		$opcoesEstado = opcoesEstado(isset($candidato->id_estado) ? $candidato->id_estado : "");
		$opcoesEstadoCivil = opcoesEstadoCivil($candidato->estado_civil);
		$opcoesEscolaridade = opcoesEscolaridade($candidato->id_escolaridade);
// 		$if = function ($condicao, $sim, $nao){return $condicao ? $sim : $nao;};//{$if(isset($candidato->nome), $candidato->nome, "")}
		$conteudo = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=areacandidato/painel_candidato&acao=salvar" >
		<input type="hidden" name="idCandidato" value="$candidato->id_candidato">
		<input type="hidden" name="cpf" value="$cpf">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* Nome completo:</td>
				<td align="left"><input name="nome" id="validar" required title="NOME" type="text" size="55" value="$candidato->nome"></td>
			</tr>
			<tr>
				<td align="left">* RG:</td>
				<td align="left"><input name="rg" id="validar" required type="text" title="RG" size="55" maxlength="15" value="$candidato->rg"/></td>
			</tr>
			<tr>
				<td align="left">* Órgão Emissor RG:</td>
				<td align="left"><input name="orgaoemissorrg" id="validar" required type="text" title="Órgão Emissor RG" size="55" maxlength="15" value="$candidato->orgao_emissor_rg"/></td>
			</tr>
			<tr>
				<td align="left">* Data Emissão RG:</td>
				<td align="left"><input name="dataemissaorg" required id="validar" type="text" title="Data Emissão RG" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.dataemissaorg);" value="$dataEmissaoRg"/></td>
			</tr>
			<tr>
				<td align="left">* CPF:</td>
				<td align="left"><input name="cpfaux" required type="text" disabled="disabled" title="CPF" id="cpfaux" size="20" value="$cpf"/></td>
			</tr>
			<tr>
				<td align="left">* Estado Civil: </td>
				<td align="left"><select name="estadocivil" required id="validar" >
						<option value="">selecione</option>
						$opcoesEstadoCivil
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Sexo:</td>
				<td align="left"><select name="sexo" required id="validar" >
						<option value="">Selecione</option>
						$opcoesSexo
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Data Nascimento: </td>
				<td align="left">
					<input type="text" value="$dataNascimento" id="validar" required name="datanascimento" title="Data de Nascimento" size="20" maxlength="10" onKeyPress="MascaraData(formCadastro.datanascimento);">(DD/MM/AAAA)
				</td>
			</tr>
			<tr>
				<td align="left">Nome do Pai: </td>
				<td align="left">
					<input type="text" value="$candidato->nome_pai" name="nomepai" title="Nome do Pai" size="55">
				</td>
			</tr>
			<tr>
				<td align="left">Nome da Mãe: </td>
				<td align="left">
					<input type="text" value="$candidato->nome_mae" name="nomemae" title="Nome da Mãe" size="55">
				</td>
			</tr>			
			<tr>
				<td align="left">* Endereço:</td>
				<td align="left"><input name="endereco" id="validar" required title="ENDEREÇO" type="text" size="55" value="$candidato->endereco"></td>
			</tr>
			<tr>
				<td align="left">Número:</td>
				<td align="left"><input name="numeroendereco" title="NÚMERO" type="text" size="55" value="$candidato->numero_endereco"></td>
			</tr>
			<tr>
				<td align="left">Complemento:</td>
				<td align="left"><input name="complementoendereco" type="text" size="55" value="$candidato->complemento_endereco"></td>
			</tr>
			<tr>
				<td align="left">Bairro:</td>
				<td align="left"><input name="bairro" type="text" title="BAIRRO" size="55" value="$candidato->bairro"></td>
			</tr>
			
			<tr>
				<td align="left">* Cidade:</td>
				<td align="left"><input name="cidade" id="validar" required type="text" title="CIDADE" size="55" value="$candidato->cidade"></td>
			</tr>
			<tr>
				<td align="left">* Estado:</td>
				<td align="left"><select name="idestado" required id="validar" >
						<option value=''>Selecione</option>
						$opcoesEstado
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Cep:</td>
				<td align="left"><input name="cep" id="validar" required type="text" title="CEP" size="55" maxlength="10" value="$candidato->cep" onKeyPress="MascaraCep(formCadastro.cep);" /></td>
			</tr>
			<tr>
				<td align="left">Telefone:</td>
				<td align="left"><input name="telefone" type="text" title="DDD+Telefone" size="20" value="$candidato->telefone" onKeyPress="MascaraTelefone(formCadastro.telefone);" maxlength="15"></td>
			</tr>
			<tr>
				<td align="left">* E-mail:</td>
				<td align="left"><input name="email" id="validar" required type="text" size="55" value="$candidato->email"></td>
			</tr>
			<tr>
				<td align="left">* Escolaridade:</td>
				<td align="left"><select name="idescolaridade" title="Escolaridade" required id="validar" >
						<option value=''>Selecione</option>
						$opcoesEscolaridade
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">* Senha:</td>
				<td align="left"><input name="senha" required type="password" title="senha" size="8" style="width:150px" value="" /></td>
			</tr>
			<tr>
				<td align="left">* Confirma Senha:</td>
				<td align="left"><input name="senha2" required type="password" title="senha" size="8" style="width:150px" value="" /></td>
			</tr>
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<div class="rodapeBotoes">
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroCandidato();">
					</div>
				</td>
			</tr>
		</table>
	</form>	
FORM;
		return $conteudo;
	}
?>