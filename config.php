<?php
	
	session_start();
	//para pegar o fuso-horario de sao paulo 
	date_default_timezone_set('America/Sao_Paulo');

	//$autoload e uma variavel para carregar dInamcamente as classes
	$autoload = function($class){
		if($class == 'Email'){
			require_once('classes/phpmailer/PHPMailerAutoLoad.php');
		}
		include('classes/'.$class.'.php');
	};

	//spl_autoload_register — Registra a função dada como implementação de __autoload()
	spl_autoload_register($autoload);


	define('INCLUDE_PATH','http://localhost/Projeto_01/');//DIRETORIO RAIZ DO SITE
	define('INCLUDE_PATH_PAINEL',INCLUDE_PATH.'painel/');//DIRETORIO PAINEL

	//CONSTANTE __DIR__ É PROPRIA DO PHP QUE PEGA O DIRETORIO ATUAL
	define('BASE_DIR_PAINEL',__DIR__.'/painel');


	//Conectar com banco de dados!
	define('HOST','localhost');
	define('USER','root');
	define('PASSWORD','');
	define('DATABASE','projeto_01');

	//Constantes para o painel de controle
	define('NOME_EMPRESA','Danki Code');

	//Funções do painel
	function pegaCargo($indice){
		return Painel::$cargos[$indice];
	}

	function selecionadoMenu($par){ 		
		$url = explode('/',@$_GET['url'])[0];
		if($url == $par){
			echo 'class="menu-active"';
		}
	}

	function verificaPermissaoMenu($permissao){
		//SE O CARGO FOR MAIOR OU IGUAL A PERMISSÃO
		if($_SESSION['cargo'] >= $permissao){
			return;
		}else{//CASO CONTRARIO : PÁGINA VAZIA
			echo 'style="display:none;"';
		}
	}

	function verificaPermissaoPagina($permissao){
		//SE O CARGO FOR MAIOR OU IGUAL A PERMISSÃO
		if($_SESSION['cargo'] >= $permissao){
			return;
		}else{//CASO CONTRARIO : PERMISSÃO NEGADA
			include('painel/pages/permissao_negada.php');
			die();
		}
	}
?>