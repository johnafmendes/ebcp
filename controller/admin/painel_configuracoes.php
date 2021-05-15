<?php
	include("../view/template.class.php");
	include("../model/dao/ConfiguracaoDAO.php");
	include("../model/entidade/Configuracao.php");
	include("../controller/admin/coluna_lateral.php");
	
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";

	$configuracao = new Configuracao();
	
	isset($_POST['idconfiguracoes']) ? ($_POST['idconfiguracoes'] == "" ? $configuracao->setidconfiguracoes(null) : $configuracao->setidconfiguracoes($_POST['idconfiguracoes'])) : $configuracao->setidconfiguracoes(null);
	isset($_POST['autenticacaoldaplocal']) ? $configuracao->setautenticacaoldaplocal($_POST['autenticacaoldaplocal']) : $configuracao->setautenticacaoldaplocal(0);	
	isset($_POST['ldapbasedn']) ? $configuracao->setldapbasedn($_POST['ldapbasedn']) : $configuracao->setldapbasedn("");
	isset($_POST['ldapdominio']) ? $configuracao->setldapdominio($_POST['ldapdominio']) : $configuracao->setldapdominio("");
	isset($_POST['ldapip']) ? $configuracao->setldapip($_POST['ldapip']) : $configuracao->setldapip("");
	isset($_POST['ldapporta']) ? $configuracao->setldapporta($_POST['ldapporta']) : $configuracao->setldapporta("");
	isset($_POST['ldapgrupo']) ? $configuracao->setldapgrupo($_POST['ldapgrupo']) : $configuracao->setldapgrupo("");
	isset($_POST['ireporturl']) ? $configuracao->setireporturl($_POST['ireporturl']) : $configuracao->setireporturl("");
	isset($_POST['ireportusuario']) ? $configuracao->setireportusuario($_POST['ireportusuario']) : $configuracao->setireportusuario("");
	isset($_POST['ireportpassword']) ? $configuracao->setireportpassword($_POST['ireportpassword']) : $configuracao->setireportpassword("");
		
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloNivelNegado = "Nível Negado";
	$conteudoNivelNegado = "Você não tem permissão de acesso!";	
	$tituloLateral = "Ajuda";
	$conteudoLateral = "";
	
	$configuracaoDAO = new ConfiguracaoDAO();
	
	switch ($acao){
		case "exibir_configuracao" :
			if (isset($_SESSION['autenticadoAdmin'])){
				$titulo = "Configurações do Site";
				$configuracao = $configuracaoDAO->getConfiguracao()->fetch(PDO::FETCH_OBJ);
				$conteudo = montaFormularioConfiguracoes($configuracao);
				
// 				$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 				$tituloLateral = "Ajuda";
				$conteudoLateral =  "";
			}
			
			break; //fim case exibir_configuracao
			
		case "salvar" :
			if($_SESSION['nivel'] < 3){
				$titulo = $tituloNivelNegado;
				$conteudo = $conteudoNivelNegado;
				$conteudoLateral =  "";
				break;
			}
				
			if (isset($_SESSION['autenticadoAdmin'])){
				$conteudoSucesso = "Dados salvo com sucesso.";
				$conteudoErro = "Não houve alterações no cadastro. Dados NÃO foram salvos. <br/><br/>Tente novamente e se o problema persistir, contate-nos.";
				if($configuracaoDAO->update($configuracao)){
					$conteudo = $conteudoSucesso;
				} else {
					$conteudo = $conteudoErro;
				}
				$titulo = "Resultado do Salvamento";
							
// 				$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
// 				$tituloLateral = "Ajuda";
				$conteudoLateral =  "";
			} else {
				//falha na autenticacao
			}
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

	function montaFormularioResumo(){
		$str = "";
		
		return $str;
	}
	
	function opcaoTipoAutenticacao($configuracoes){
		$ldap = "";
		$local = "";
		if($configuracoes->autenticacao_ldap_local == 0){
			$ldap = "checked";
		} else {
			$local = "checked";
		}
		
		return "<input type=\"radio\" " . $ldap . " name=\"autenticacaoldaplocal\" value=\"0\">LDAP <input type=\"radio\" " . $local . " name=\"autenticacaoldaplocal\" value=\"1\">Local";
	}
	
	function montaFormularioConfiguracoes($configuracoes){
		$opcaoTipoAutenticacao = opcaoTipoAutenticacao($configuracoes);
		$str = <<<FORM
			<form name="formConfiguracao" method="post" action="?nivel=painel_configuracoes&acao=salvar" >
				<input type="hidden" name="idconfiguracoes" value="$configuracoes->id_configuracoes">
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
					<tr>
						<td align="left">* Tipo Autenticação</td>
						<td align="left">$opcaoTipoAutenticacao</td>
					</tr>
					<tr>
						<td align="left">LDAP Base DN</td>
						<td align="left"><input type="text" name="ldapbasedn" value="$configuracoes->ldap_base_dn" size="55"></td>
					</tr>
					<tr>
						<td align="left">LDAP Domínio</td>
						<td align="left"><input type="text" name="ldapdominio" value="$configuracoes->ldap_dominio" size="55"></td>
					</tr>
					<tr>
						<td align="left">LDAP IP</td>
						<td align="left"><input type="text" name="ldapip" value="$configuracoes->ldap_ip" size="55"></td>
					</tr>
					<tr>
						<td align="left">LDAP porta</td>
						<td align="left"><input type="text" name="ldapporta" value="$configuracoes->ldap_porta" size="55"></td>
					</tr>
					<tr>
						<td align="left">LDAP Grupo</td>
						<td align="left"><input type="text" name="ldapgrupo" value="$configuracoes->ldap_grupo" size="55"></td>
					</tr>
					<tr>
						<td align="left">iReport URL</td>
						<td align="left"><input type="text" name="ireporturl" value="$configuracoes->ireport_url" size="55"></td>
					</tr>
					<tr>
						<td align="left">iReport Usuário</td>
						<td align="left"><input type="text" name="ireportusuario" value="$configuracoes->ireport_usuario" size="55"></td>
					</tr>
					<tr>
						<td align="left">iReport Senha</td>
						<td align="left"><input type="text" name="ireportpassword" value="$configuracoes->ireport_password" size="55"></td>
					</tr>		
					<tr>
						<td align="center" colspan="2"><input type="submit" class="button" name="submit" value="Confirmo e Salvo"></td>
					</tr>
				</table>
			</form>
FORM;

	return $str;		
		
	}
	
?>