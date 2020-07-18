<?php
	//para poder acessar a pasta classes
	include('../config.php');
	//se a pessoa nao estiver logada, irá para login.php
	if(Painel::logado() == false){
		include('login.php');
	//caso contrario(se estiver logado), ira para main.php	
	}else{
		include('main.php');
	}

?>