<?php
	include("../view/template.class.php");
	include("../model/dao/UsuarioInstituicaoDAO.php");
	include("../controller/cliente/coluna_lateral.php");
	
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
			$usuarioInstituicaoDAO = new UsuarioInstituicaoDAO();
			
			$usuario = $usuarioInstituicaoDAO->autentica($login, md5($senha));
			
			if($usuario != null){
				$u = $usuario->fetch(PDO::FETCH_OBJ);
				$_SESSION['idInstituicao'] = $u->id_instituicao;
				$_SESSION['autenticadoCliente'] = true;
				header('Location: ?nivel=painel_inicio&acao=exibir_resumo&usuario=' . $u->nome);
			} else { //Sem acesso ao sistema
				$conteudo = "Login ou Senha inválido. Tente novamente ou contacte o administrador de redes.";
			}
			
			break; //fim case login
			
			case "exibir_resumo" :
				if (isset($_SESSION['autenticadoCliente'])){
					$titulo = "Resumos";
					$conteudo = montaFormularioResumo();
						
					$tituloLateral = "Bem Vindo";
					$conteudoLateral =  $usuario;
				} else {
					//falha na autenticacao
				}
				break;
	}
		
	isset($_SESSION['autenticadoCliente']) ? $menu = new Template("../view/cliente/menu_autenticado.tpl") : $menu = new Template("../view/comum/menu_sem_autenticacao.tpl"); 
	$menu->set("resumos", "active");
	
	if(isset($_SESSION['autenticadoCliente'])){
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