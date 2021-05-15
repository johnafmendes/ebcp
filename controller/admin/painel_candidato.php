<?php
	include("../view/template.class.php");
	include("../model/dao/CandidatoDAO.php");
	include("../model/dao/InscricaoDAO.php");
	include("../model/dao/EstadoDAO.php");
	include("../model/dao/EscolaridadeDAO.php");
	include("../model/entidade/Candidato.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idcandidato']) ? $idCandidato = $_GET['idcandidato'] : $idCandidato = 0;
	isset($_POST['nomecodigocpf']) ? $nomecodigocpf = $_POST['nomecodigocpf'] : (isset($_GET['nomecodigocpf']) ? $nomecodigocpf = $_GET['nomecodigocpf'] : $nomecodigocpf = "");
	isset($_POST['nomeoucodigooucpf']) ? $nomeoucodigooucpf = $_POST['nomeoucodigooucpf'] : (isset($_GET['nomeoucodigooucpf']) ? $nomeoucodigooucpf = $_GET['nomeoucodigooucpf'] : $nomeoucodigooucpf = "");
// 	echo $_POST['nomecodigocpf'] . "\\" . $_POST['nomeoucodigooucpf'];
// 	echo $nomecodigocpf . "/" . $nomeoucodigooucpf;
	
	/*paginacao candidato*/
	//verifica a página atual caso seja informada na URL, senão atribui como 1ª página
	$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;

	//seta a quantidade de itens por página, neste caso, 10 itens
	$registros = 20;

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($registros*$pagina)-$registros;
	/*fim paginacao candidato*/

	
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
					$titulo = "Cadastro Candidato";
					$conteudo = montaFormularioCadastroCandidato(null);
					if($nomeoucodigooucpf == 1){//por codigo
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorID($nomecodigocpf);
					} else if ($nomeoucodigooucpf == 2) { //por cpf
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorCPF($nomecodigocpf);
					} else { //por nome
						//conta o total de itens
						$total = $candidatoDAO->getTotalCandidatoPorNome($nomecodigocpf);
							
						//calcula o número de páginas arredondando o resultado para cima
						$numPaginas = ceil($total/$registros);
						
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorNome($nomecodigocpf, $inicio, $registros);
					}
					

					$conteudoPesquisa = montaFormularioPesquisaCandidato($resultadoPesquisa, montaPaginacao($numPaginas, $nomeoucodigooucpf, $nomecodigocpf, $pagina));

					$resultadoPesquisaInscricao = $inscricaoDAO->getInscricaoPorIdCandidato($idCandidato);
					
					$conteudoPesquisa .= montaFormularioInscricaoCandidato($resultadoPesquisaInscricao);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_candidato" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Candidato";
					$candidato = $candidatoDAO->getCandidatoPorID($idCandidato)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroCandidato($candidato);
					if($nomeoucodigooucpf == 1){//por codigo
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorID($nomecodigocpf);
					} else if ($nomeoucodigooucpf == 2) { //por cpf
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorCPF($nomecodigocpf);
					} else { //por nome
						//conta o total de itens
						$total = $candidatoDAO->getTotalCandidatoPorNome($nomecodigocpf);
							
						//calcula o número de páginas arredondando o resultado para cima
						$numPaginas = ceil($total/$registros);
						
						$resultadoPesquisa = $candidatoDAO->getCandidatoPorNome($nomecodigocpf, $inicio, $registros);
					}

					$conteudoPesquisa = montaFormularioPesquisaCandidato($resultadoPesquisa, montaPaginacao($numPaginas, $nomeoucodigooucpf, $nomecodigocpf, $pagina));
					$resultadoPesquisaInscricao = $inscricaoDAO->getInscricaoPorIdCandidato($idCandidato);
					$conteudoPesquisa .= montaFormularioInscricaoCandidato($resultadoPesquisaInscricao);
					
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
				break;
				
			case "excluir" :
				if($_SESSION['nivel'] < 3){
					$titulo = $tituloNivelNegado;
					$conteudo = $conteudoNivelNegado;
					$conteudoLateral =  "";
					break;
				}
				
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Resultado Exclusão Candidato";
					$conteudo = "Candidato NÃO foi Excluído!";
					if($candidatoDAO->excluirPorID($idCandidato)){
						$conteudo = "Candidato Excluído com Sucesso!";
					}
					
					$conteudoPesquisa = "";
					
					$conteudoLateral =  "";
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
	
	function montaPaginacao($numPaginas, $nomeoucodigooucpf, $nomecodigocpf, $pagina){
		$str = "Páginas: ";
		if(isset($numPaginas)){
			for($i = 1; $i < $numPaginas + 1; $i++) {
				if($i != $pagina){
					$str .= "<a href='?nivel=painel_candidato&acao=exibir_cadastro&nomeoucodigooucpf=" . $nomeoucodigooucpf . "&nomecodigocpf=" . $nomecodigocpf . "&pagina=$i'>[".$i."]</a> ";
				} else {
					$str .= "<b>[" . $i . "]</b> ";
				} 
			}
		}
		return $str;
	}

	function opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa){
		$str = "";
		if($resultadoPesquisa != null){
			while ($resultado = $resultadoPesquisa->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
						. "<td align=\"left\"><a href=\"?nivel=painel_candidato&acao=exibir_candidato&idcandidato=" . $resultado->id_candidato . "\">" . $resultado->id_candidato . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_candidato&acao=exibir_candidato&idcandidato=" . $resultado->id_candidato . "\">" . $resultado->nome . "</a></td>"
						. "<td align=\"left\">" . formataData($resultado->data_nascimento) . "</td>"
						. "<td align=\"center\">" . $resultado->cpf . "</td>"
						. "<td align=\"center\">" . $resultado->email . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_candidato&acao=excluir&idcandidato=" . $resultado->id_candidato . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function opcaoMontaTabelaResultadoInscricao($resultadoInscricao){
		$str = "";
		if($resultadoInscricao != null){
			while ($resultado = $resultadoInscricao->fetch(PDO::FETCH_OBJ)){
				$str .= "<tr>"
					. "<td align=\"left\"><a href=\"?nivel=painel_inscricao&acao=exibir_inscricao&idinscricao=" . $resultado->id_inscricao . "\">" . $resultado->id_inscricao . "</a></td>"
					. "<td align=\"left\"><a href=\"?nivel=painel_inscricao&acao=exibir_inscricao&idinscricao=" . $resultado->id_inscricao . "\">" . $resultado->titulo . "</a></td>"
					. "<td align=\"left\">" . $resultado->cargo . "</td>"
					. "<td align=\"center\">" . ($resultado->homologado == 1 ? "Sim" : "Não") . "</td>"
					. "<td align=\"center\">" . $resultado->cidade . "</td>"
				. "</tr>";
			}
		} else {
			$str .= "<tr>"
				. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
			. "</tr>";
		}
	
		return $str;
	}
	
	function montaFormularioPesquisaCandidato($resultadoPesquisa, $paginacao){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Candidato</b></div>
      	<div class="corpo">
		
			<form name="formPesquisa" method="post" action="?nivel=painel_candidato&acao=exibir_cadastro" >
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* <input type="radio" name="nomeoucodigooucpf" value="0">Nome <input type="radio" name="nomeoucodigooucpf" value="1">Código <input type="radio" name="nomeoucodigooucpf" value="2">CPF</td>
						<td align="left"><input name="nomecodigocpf" required title="Nome ou Código ou CPF" type="text" size="55" value=""><input type="submit" name="submit" value="Pesquisar"></td>
					</tr>
				</table>
			</form>
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Nome</td>
					<td align="left">Data de Nascimento</td>
					<td align="center">CPF</td>
					<td align="center">e-Mail</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
			<br/>
			$paginacao
		</div>
	</div>
FORM;
		
		return $str;
	}

	function montaFormularioInscricaoCandidato($resultadoInscricao){
		$opcaoMontaTabelaResultadoInscricao = opcaoMontaTabelaResultadoInscricao($resultadoInscricao);
		$str = <<<FORM
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Inscrição Candidato</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Inscrição:</td>
					<td align="left">Concurso</td>
					<td align="left">Cargo</td>
					<td align="center">Homologado</td>
					<td align="center">Cidade Prova</td>
				</tr>
				$opcaoMontaTabelaResultadoInscricao
			</table>
			<br/>
		</div>
	</div>
FORM;
	
		return $str;
	}
	
	function opcoesSexo($sexo){
		return  "<option value=\"M\"" . ($sexo == "M" ? "selected=\"selected\"" : "") . ">Masculino</option>"
			. "<option value=\"F\"" . ($sexo == "F" ? "selected=\"selected\"" : "") . ">Feminino</option>";
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
	
	function montaFormularioCadastroCandidato($candidato){
		$opcoesSexo = opcoesSexo(isset($candidato->sexo) ? $candidato->sexo : "");
		$opcoesEscolaridade = opcoesEscolaridade($candidato->id_escolaridade);
		$dataNascimento = formataData(isset($candidato->data_nascimento) ? $candidato->data_nascimento : "");
		$dataEmissaoRg = formataData(isset($candidato->data_emissao_rg) ? $candidato->data_emissao_rg : "");
		$opcoesEstado = opcoesEstado($candidato->id_estado);
		$opcoesEstadoCivil = opcoesEstadoCivil($candidato->estado_civil);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_candidato&acao=salvar" >
		<input type="hidden" name="idcandidato" value="$candidato->id_candidato">
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
				<td align="left"><input name="cpfaux" required type="text" title="CPF" id="cpfaux" size="20" value="$candidato->cpf"/></td>
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
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
					<button onclick="location.href='?nivel=painel_candidato&acao=exibir_cadastro'" class="button" type="button">Novo</button>
					<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>