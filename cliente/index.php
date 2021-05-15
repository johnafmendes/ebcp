<?php
	session_start();
	if(isset($_GET['nivel'])){
		$segundoNivel = "../controller/cliente/" . $_GET['nivel'] . ".php";
	} else {
		$segundoNivel = "";
	}
    if (!file_exists($segundoNivel)) {
		include("../controller/cliente/inicio.php");
	} else {
		include($segundoNivel);
	}

?>