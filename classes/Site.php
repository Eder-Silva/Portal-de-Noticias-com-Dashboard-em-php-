<?php
	
	class Site{

		public static function updateUsuarioOnline(){
			if(isset($_SESSION['online'])){//se ja existir a sessao online(ja e usuario do site)
				$token = $_SESSION['online'];
				$horarioAtual = date('Y-m-d H:i:s');//pega a data e hora
				//selecionar o id da tb_admin.online onde coluna token é igual ao $_SESSION['online'] passado
				$check = MySql::conectar()->prepare("SELECT `id` FROM `tb_admin.online` WHERE token = ?");
				$check->execute(array($_SESSION['online']));

				//SE PASSAR 1 MINUTO E O USUARIO AINDA ESTIVER ONLINE
				if($check->rowCount() == 1){
					//atualizar a data e hora na coluna ultima_acao na tb_admin.online onde coluna token é igual ao $_SESSION['online'] passado
					$sql = MySql::conectar()->prepare("UPDATE `tb_admin.online` SET ultima_acao = ? WHERE token = ?");
					$sql->execute(array($horarioAtual,$token));
				}else{//CASO PASSE UM MINUTO INATIVO E O USUARO VOLTE AO SITE
					$ip = $_SERVER['REMOTE_ADDR'];//para pegar o ip do usuario
					$token = $_SESSION['online'];
					$horarioAtual = date('Y-m-d H:i:s');
					$sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.online` VALUES (null,?,?,?)");
					$sql->execute(array($ip,$horarioAtual,$token));
				}
			}else{//caso nao existir a sessao online(primeira vez do usuario no site)
				$_SESSION['online'] = uniqid();//vai gerar um id unico 
				$ip = $_SERVER['REMOTE_ADDR'];//para pegar o ip do usuario
				$token = $_SESSION['online'];//token será o id unico gerado pelo uniqid()
				$horarioAtual = date('Y-m-d H:i:s');
				//inserir na tb_admin.online os valores nas colunas $ip,$horarioAtual,$token
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.online` VALUES (null,?,?,?)");
				$sql->execute(array($ip,$horarioAtual,$token));
			}
		}

		public static function contador(){
			//SE N EXSTIR O COOKIE VISITA, O USUARIO N E UM NOVO VISITANTE DO SITE
			if(!isset($_COOKIE['visita'])){
				setcookie('visita','true',time() + (60*60*24*7));//CRIAR COOKIE Q DURA POR 7 DIAS
				$sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.visitas` VALUES (null,?,?)");
				$sql->execute(array($_SERVER['REMOTE_ADDR'],date('Y-m-d')));
			}
		}

	}

?>