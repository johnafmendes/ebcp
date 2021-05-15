<?php
	include("../view/template.class.php");
	include("../model/dao/BoletoDAO.php");
 	include("../model/entidade/Boleto.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['idboleto']) ? $idBoleto = $_GET['idboleto'] : $idBoleto = 0;
// 	isset($_GET['idconcurso']) ? $idConcurso = $_GET['idconcurso'] : $idConcurso = 0;
// 	isset($_POST['nomecodigo']) ? $nomecodigo = $_POST['nomecodigo'] : $nomecodigo = "";
// 	isset($_POST['nomeoucodigo']) ? $nomeoucodigo = $_POST['nomeoucodigo'] : $nomeoucodigo = "";
	

	$boleto = new Boleto();
	/*dados do formulário*/
	isset($_POST['idboleto']) ? ($_POST['idboleto'] == "" ? $boleto->setidboleto(null) : $boleto->setidboleto($_POST['idboleto'])) : $boleto->setidboleto(null);
	isset($_POST['descricao']) ? $boleto->setdescricao($_POST['descricao']) : $boleto->setdescricao(null);
	isset($_POST['bancoagencia']) ? $boleto->setbancoagencia($_POST['bancoagencia']) : $boleto->setbancoagencia(null);
	isset($_POST['contanumero']) ? $boleto->setcontanumero($_POST['contanumero']) : $boleto->setcontanumero(null);
	isset($_POST['contadv']) ? $boleto->setcontadv($_POST['contadv']) : $boleto->setcontadv(null);
	isset($_POST['cedentecodigo']) ? $boleto->setcedentecodigo($_POST['cedentecodigo']) : $boleto->setcedentecodigo(null);
	isset($_POST['cedentecodigodv']) ? $boleto->setcedentecodigodv($_POST['cedentecodigodv']) : $boleto->setcedentecodigodv(null);
	isset($_POST['cedenteidentificacao']) ? $boleto->setcedenteidentificacao($_POST['cedenteidentificacao']) : $boleto->setcedenteidentificacao(null);
	isset($_POST['cedentecnpj']) ? $boleto->setcedentecnpj($_POST['cedentecnpj']) : $boleto->setcedentecnpj(null);
	isset($_POST['cedenteendereco']) ? $boleto->setcedenteendereco($_POST['cedenteendereco']) : $boleto->setcedenteendereco(null);
	isset($_POST['cedentecidade']) ? $boleto->setcedentecidade($_POST['cedentecidade']) : $boleto->setcedentecidade(null);
	isset($_POST['cedenteestado']) ? $boleto->setcedenteestado($_POST['cedenteestado']) : $boleto->setcedenteestado(null);
	isset($_POST['cedenterazaosocial']) ? $boleto->setcedenterazaosocial($_POST['cedenterazaosocial']) : $boleto->setcedenterazaosocial(null);
	isset($_POST['nomearquivo']) ? $boleto->setnomearquivo($_POST['nomearquivo']) : $boleto->setnomearquivo(null);
	isset($_POST['instrucoescaixa1']) ? $boleto->setinstrucoescaixa1($_POST['instrucoescaixa1']) : $boleto->setinstrucoescaixa1(null);
	isset($_POST['instrucoescaixa2']) ? $boleto->setinstrucoescaixa2($_POST['instrucoescaixa2']) : $boleto->setinstrucoescaixa2(null);
	isset($_POST['instrucoescaixa3']) ? $boleto->setinstrucoescaixa3($_POST['instrucoescaixa3']) : $boleto->setinstrucoescaixa3(null);
	isset($_POST['instrucoescaixa4']) ? $boleto->setinstrucoescaixa4($_POST['instrucoescaixa4']) : $boleto->setinstrucoescaixa4(null);
	

	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$boletoDAO = new BoletoDAO();
	
	switch ($acao){
			case "exibir_cadastro" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Cadastro de Boletos";
					$conteudo = montaFormularioCadastroBoleto(null);
					$resultadoPesquisa = $boletoDAO->listarBoletos();
					$conteudoPesquisa = montaFormularioPesquisaBoleto($resultadoPesquisa);
						
					$conteudoLateral =  "";
				} else {
					//falha na autenticacao
				}
			break;

			case "exibir_boleto" :
				if (isset($_SESSION['autenticadoAdmin'])){
					$titulo = "Editar Boleto";
					$boleto = $boletoDAO->getBoletoPorID($idBoleto)->fetch(PDO::FETCH_OBJ);
					$conteudo = montaFormularioCadastroBoleto($boleto);
					$resultadoPesquisa = $boletoDAO->listarBoletos();
					$conteudoPesquisa = montaFormularioPesquisaBoleto($resultadoPesquisa);
			
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
					$titulo = "Resultado Exclusão Boleto";
					$conteudo = "Boleto NÃO foi Excluído!";
					if($boletoDAO->excluirPorID($idBoleto)){
						$conteudo = "Boleto Excluído com Sucesso!";
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
				if($boleto->getidboleto() == null){//inserir
					if($boletoDAO->salvar($boleto)){
						$conteudo = $conteudoSucesso;
					} else {
						$conteudo = $conteudoErro;
					}
				} else { //atualizar
					if($boletoDAO->update($boleto)){
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
						. "<td align=\"left\"><a href=\"?nivel=painel_boleto&acao=exibir_boleto&idboleto=" . $resultado->id_boleto . "\">" . $resultado->id_boleto . "</a></td>"
						. "<td align=\"left\"><a href=\"?nivel=painel_boleto&acao=exibir_boleto&idboleto=" . $resultado->id_boleto . "\">" . $resultado->descricao . "</a></td>"
						. "<td align=\"center\">" . $resultado->banco_agencia . "/" . $resultado->conta_numero . "-" . $resultado->conta_dv . "</td>"
						. "<td align=\"center\">" . $resultado->cedente_codigo . "</td>"
						. "<td align=\"center\">" . $resultado->nome_arquivo . "</td>"
						. "<td align=\"center\"><a href=\"?nivel=painel_boleto&acao=excluir&idboleto=" . $resultado->id_boleto . "\" onclick=\"return confirmarExclusao();\">Excluir</a></td>"
					. "</tr>";
			}
		} else {
			$str .= "<tr>"
					. "<td align=\"left\" colspan=\"5\">Nenhum resultado encontrado</td>"
				. "</tr>";
		}
		
		return $str;
	}
	
	function montaFormularioPesquisaBoleto($resultadoPesquisa){
		$opcaoMontaTabelaResultadoPesquisa = opcaoMontaTabelaResultadoPesquisa($resultadoPesquisa);
		$str = <<<FORM
	<div style="clear: both;" ></div>
	<div class="espacoBranco"></div>
	<div class="borda">
		<div class="titulo"><b>Pesquisa Boletos</b></div>
      	<div class="corpo">
			<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
				<tr style="background-color: #7bb181; color: #355239;">
					<td align="left">Código:</td>
					<td align="left">Descricao</td>
					<td align="center">Agência/Conta-DV</td>
					<td align="center">Código Cedente</td>
					<td align="center">Nome Arquivo</td>
					<td align="center">Ação</td>
				</tr>
				$opcaoMontaTabelaResultadoPesquisa
			</table>
		</div>
	</div>
FORM;
		
		return $str;
	}
	
	function opcoesArquivosBoletos($arquivo){
		$str = "";
			$str .= "<tr>"
					. "<td align=\"left\">* Boleto:</td>"
					. "<td align=\"left\"><select name=\"nomearquivo\" id=\"validar\" required style=\"width: 400px;\">"
					. "<option value=\"\">selecione</option>"
					. "<option value=\"boleto_bb.php\"" . ($arquivo == "boleto_bb.php" ? "selected=\"selected\"" : "") . ">Banco do Brasil</option>"
					. "<option value=\"boleto_cef.php\"" . ($arquivo == "boleto_cef.php" ? "selected=\"selected\"" : "") . ">Caixa Econômica Federal</option>"
					. "<option value=\"boleto_sicredi.php\"" . ($arquivo == "boleto_sicredi.php" ? "selected=\"selected\"" : "") . ">Sicredi</option>"
					. "<option value=\"boleto_sicoob.php\"" . ($arquivo == "boleto_sicoob.php" ? "selected=\"selected\"" : "") . ">Sicoob</option>"
				. "</select>"
				. "</td>"
				. "</tr>";
		return $str;
	}
	
	function montaFormularioCadastroBoleto($boleto){
		$opcoesArquivosBoleto = opcoesArquivosBoletos($boleto->nome_arquivo);
		$str = <<<FORM
	<form name="formCadastro" method="post" action="?nivel=painel_boleto&acao=salvar" >
		<input type="hidden" name="idboleto" value="$boleto->id_boleto">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
			<tr>
				<td align="left">* ID:</td>
				<td align="left"><input name="id" type="text" size="10" value="$boleto->id_boleto" disabled="disabled"></td>
			</tr>
			<tr>
				<td align="left">* Descrição:</td>
				<td align="left"><input name="descricao" required title="Descrição" type="text" size="55" value="$boleto->descricao" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Agência:</td>
				<td align="left"><input name="bancoagencia" required title="Agência" type="text" size="20" value="$boleto->banco_agencia" id="validar"></td>
			</tr>
			<tr>
				<td align="left">* Número Conta:</td>
				<td align="left"><input name="contanumero" required type="text" title="Número Conta" size="20" value="$boleto->conta_numero" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Dídigo Conta:</td>
				<td align="left"><input name="contadv" required type="text" title="Dígito Conta" size="20" value="$boleto->conta_dv" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Código Cedente:</td>
				<td align="left"><input name="cedentecodigo" required type="text" title="Código Cedente" size="20" value="$boleto->cedente_codigo" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* DV Código Cedente:</td>
				<td align="left"><input name="cedentecodigodv" required type="text" title="DV Código Cedente" size="20" value="$boleto->cedente_codigo_dv"/></td>
			</tr>
			<tr>
				<td align="left">* Identificação Cedente:</td>
				<td align="left"><input name="cedenteidentificacao" required type="text" title="Identificação Cedente" size="55" value="$boleto->cedente_identificacao" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* CNPJ Cedente:</td>
				<td align="left"><input name="cedentecnpj" required type="text" title="CNPJ Cedente" size="20" value="$boleto->cedente_cnpj" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Endereço Cedente:</td>
				<td align="left"><input name="cedenteendereco" required type="text" title="Endereço Cedente" size="55" value="$boleto->cedente_endereco" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Cidade Cedente:</td>
				<td align="left"><input name="cedentecidade" required type="text" title="Cidade Cedente" size="20" value="$boleto->cedente_cidade" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Estado Cedente:</td>
				<td align="left"><input name="cedenteestado" required type="text" title="Estado Cedente" size="20" value="$boleto->cedente_estado" id="validar"/></td>
			</tr>
			<tr>
				<td align="left">* Razão Social Cedente:</td>
				<td align="left"><input name="cedenterazaosocial" required type="text" title="Razão Social Cedente" size="55" value="$boleto->cedente_razao_social" id="validar"/></td>
			</tr>
			$opcoesArquivosBoleto;
			<tr>
				<td align="left">* Instruções Caixa 1:</td>
				<td align="left"><input name="instrucoescaixa1" required type="text" title="Instruções Caixa 1" size="55" value="$boleto->instrucoes_caixa1" id="validar"/></td>
			</tr>		
			<tr>
				<td align="left">* Instruções Caixa 2:</td>
				<td align="left"><input name="instrucoescaixa2" required type="text" title="Instruções Caixa 2" size="55" value="$boleto->instrucoes_caixa2" id="validar"/></td>
			</tr>		
			<tr>
				<td align="left">* Instruções Caixa 3:</td>
				<td align="left"><input name="instrucoescaixa3" required type="text" title="Instruções Caixa 3" size="55" value="$boleto->instrucoes_caixa3" id="validar"/></td>
			</tr>		
			<tr>
				<td align="left">* Instruções Caixa 4:</td>
				<td align="left"><input name="instrucoescaixa4" required type="text" title="Instruções Caixa 4" size="55" value="$boleto->instrucoes_caixa4" id="validar"/></td>
			</tr>		
			<tr>
				<td colspan="2" height="40" align="center">
					* Campos Obrigatórios
						<button onclick="location.href='?nivel=painel_boleto&acao=exibir_cadastro'" class="button" type="button">Novo</button>
						<input type="submit" name="submit" value="Confirmo e Salvo" class="button" onclick="return validaCadastroGenerico();">
				</td>
			</tr>
		</table>
	</form>	
FORM;
		
		return $str;
	}
?>