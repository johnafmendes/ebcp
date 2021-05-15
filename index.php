<?php
	session_start();
	if(isset($_GET['nivel'])){
		$segundoNivel = "controller/" . $_GET['nivel'] . ".php";
	} else {
		$segundoNivel = "";
	}
    if (!file_exists($segundoNivel)) {
		include("controller/inicio.php");
	} else {
		include($segundoNivel);
	}

?>