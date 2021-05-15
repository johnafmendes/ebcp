<?php
	session_start();
	if(isset($_GET['nivel'])){
		$segundoNivel = "../controller/admin/" . $_GET['nivel'] . ".php";
	} else {
		$segundoNivel = "";
	}
    if (!file_exists($segundoNivel)) {
		include("../controller/admin/inicio.php");
	} else {
		include($segundoNivel);
	}

?>