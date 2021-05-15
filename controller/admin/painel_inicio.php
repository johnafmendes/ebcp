<?php
	include("../view/template.class.php");
	include("../model/dao/UsuarioDAO.php");
	include("../model/dao/ConfiguracaoDAO.php");
	include("../controller/admin/coluna_lateral.php");
// 	include("controller/areacandidato/coluna_lateral.php");
	
	isset($_POST['login']) ? $login = $_POST['login'] : $login = "";	
	isset($_POST['senha']) ? $senha = $_POST['senha'] : $senha = "";
	isset($_GET['acao']) ? $acao = $_GET['acao'] : $acao = "";
	isset($_GET['usuario']) ? $usuario = $_GET['usuario'] : $usuario = "";
	
	$titulo = "Falha na Autenticação";
	$conteudo = "Seu Login ou Senha estão errados. Por favor, digite novamente, ou solicite uma nova senha para o administrador de redes.";
	$tituloLateral = "";
	$conteudoLateral = "";
	
	switch ($acao){
		case "logoff" :
			session_unset();
			session_destroy();
			header('Location: ?nivel=inicio');
			break;
			
		case "login" :
			$configuracaoDAO = new ConfiguracaoDAO();
			
			$configuracao = $configuracaoDAO->getConfiguracao()->fetch(PDO::FETCH_OBJ);
			
			if($configuracao->autenticacao_ldap_local == 0){ //0=ldap, 1=local
// 				$adServer = "179.157.24.52";
				$adServer = $configuracao->ldap_ip;//"192.168.0.2";
			
				$ldap = ldap_connect($adServer, $configuracao->ldap_porta);
				$username = $login;
				$password = $senha;
			
				$ldaprdn = $configuracao->ldap_dominio . '\\' . $username;
			
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
			
				$bind = @ldap_bind($ldap, $ldaprdn, $password);
			
			
				if ($bind) {
					$filter = "(sAMAccountName=" . $username . ")";
					$attr = array("memberof");
// 					echo $configuracao->ldap_base_dn;
					$result = ldap_search($ldap, $configuracao->ldap_base_dn, $filter, $attr);
					$entries = ldap_get_entries($ldap, $result);
					//print_r($entries);
					foreach ($entries[0]['memberof'] as $value) {
// 						echo $value;					
  						if (strpos($value, 'CN=' . $configuracao->ldap_grupo . "1") !== false) {//Se achar, entra no if
  							$_SESSION['nivel'] = 1;
//   							echo "entrou nivel 1";
  						} else if (strpos($value, 'CN=' . $configuracao->ldap_grupo . "2") !== false) {//Se achar, entra no if
  							$_SESSION['nivel'] = 2;
//   							echo "entrou nivel 2";
  						} else if (strpos($value, 'CN=' . $configuracao->ldap_grupo . "3") !== false) {//Se achar, entra no if
  							$_SESSION['nivel'] = 3;
//   							echo "entrou nivel 3";
  						}
  						
  						if(isset($_SESSION['nivel'])){
//   							echo "entrou nivel " . $_SESSION['nivel'];
							$filter="(sAMAccountName=$username)";
							$result = ldap_search($ldap, $configuracao->ldap_base_dn, $filter);
							ldap_sort($ldap,$result,"sn");
							$info = ldap_get_entries($ldap, $result);
							for ($i=0; $i<$info["count"]; $i++)
							{
								if($info['count'] > 1)
									break;
								$sobrenome = $info[$i]["sn"][0];
								$nome = $info[$i]["givenname"][0];
							}
							$_SESSION['autenticadoAdmin'] = true;
							$_SESSION['usuarioAcesso'] = $username;
							ldap_unbind($ldap);
//   							die();
							header('Location: ?nivel=painel_inicio&acao=exibir_resumo&usuario=' . $nome . " " . $sobrenome);
							break;
  						} else { //Sem acesso ao sistema
//   							if ($bind) {ldap_unbind($ldap);}
  							$conteudo = "Login ou Senha inválido. Tente novamente ou contacte o administrador de redes.";
  						}
					}
				} else {
					$conteudo = "Login ou Senha inválido. Tente novamente ou contacte o administrador de redes.";
				}
			} else { //1 = local
				$usuarioDAO = new UsuarioDAO();
				
				$usuario = $usuarioDAO->autentica($login, md5($senha));

				if($usuario != null){
					$u = $usuario->fetch(PDO::FETCH_OBJ);
					$_SESSION['autenticadoAdmin'] = true;
					$_SESSION['usuarioAcesso'] = $login;
					$_SESSION['nivel'] = $u->nivel_acesso;
					header('Location: ?nivel=painel_inicio&acao=exibir_resumo&usuario=' . $u->nome);
				} else { //Sem acesso ao sistema
					$conteudo = "Login ou Senha inválido. Tente novamente ou contacte o administrador de redes.";
				}
			}
			
			break; //fim case login
			
			case "exibir_resumo" :
				if (isset($_SESSION['autenticadoAdmin'])){
// 					$dadosCandidato = $candidatoDAO->getCandidatoPorID($_SESSION['idCandidato']);
// 					if($dadosCandidato != null){
// 						$candidato = $dadosCandidato->fetch(PDO::FETCH_OBJ);
						$titulo = "Resumos";
						$conteudo = montaFormularioResumo();
							
// 						$corpoTemplate = new Template("../view/comum/areaAdministrativa.tpl");
						$tituloLateral = "Bem Vindo";
						$conteudoLateral =  $usuario;
// 					} else {
						//falha na autenticacao
// 					}
				} else {
					//falha na autenticacao
				}
				break;
	}
		
	isset($_SESSION['autenticadoAdmin']) ? $menu = new Template("../view/admin/menu_autenticado.tpl") : $menu = new Template("../view/comum/menu_sem_autenticacao.tpl"); 
	$menu->set("resumos", "active");
	
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
?>