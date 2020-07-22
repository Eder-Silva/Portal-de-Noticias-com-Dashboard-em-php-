<?php
//ao entara na pagina de login...se já existir o cookie lembrar...
	if(isset($_COOKIE['lembrar'])){
		$user = $_COOKIE['user'];
		$password = $_COOKIE['password'];
		$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE user = ? AND password = ?");
		$sql->execute(array($user,$password));
		if($sql->rowCount() == 1){
				$info = $sql->fetch();
				$_SESSION['login'] = true;
				$_SESSION['user'] = $user;
				$_SESSION['password'] = $password;
				$_SESSION['cargo'] = $info['cargo'];
				$_SESSION['nome'] = $info['nome']; 
				$_SESSION['img'] = $info['img'];
				header('Location: '.INCLUDE_PATH_PAINEL);
				die();
		}
	}

?>


<!DOCTYPE html>
<html>
<head>

	<title>Painel de Controle</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<link rel="stylesheet" href="<?php echo INCLUDE_PATH; ?>estilo/all.css">
	<link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>css/style.css">

</head>
<body>

	<div class="box-login">

		<?php 

			if (isset($_POST['acao'])) {
				$user = $_POST['user'];
				$password = $_POST['password'];
				$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.usuarios` WHERE user = ? AND password = ?");
				$sql->execute(array($user, $password));
				//verificar se já existe um usuario e senha cadastrado no bd
				if($sql->rowCount() == 1){
					$info = $sql->fetch();
					//logamos com sucesso
					$_SESSION['login'] = true;
					$_SESSION['user'] = $user;
					$_SESSION['password'] = $password;
					$_SESSION['cargo'] = $info['cargo'];
					$_SESSION['nome'] = $info['nome'];
					$_SESSION['img'] = $info['img'];

					//criar cookies e faze-lo durar um dia 
					if (isset($_POST['lembrar'])) {
						setcookie('lembrar',true,time()+(60*60*24),'/');
						setcookie('user',$user,time()+(60*60*24),'/');
						setcookie('password',$password,time()+(60*60*24),'/');
					}

					header('Location: '.INCLUDE_PATH_PAINEL);
					die();

				}else{
					//falha ao conectar, usuario e senha não cadastrados
					echo '<div class="erro-box"><i class="fa fa-times"></i> Usuário ou Senha incorretos! </div>';
				}
			}

		?>

		<h2>Efetue o Login:</h2>
		<form method="post">
			<input type="text" name="user" placeholder="Login..." required>
			<input type="password" name="password" placeholder="Senha..." required>
			
			<div class="form-group-login left">
				<input type="submit" name="acao" value="Logar">
			</div><!-- form-group-login -->

			<div class="form-group-login right">
				<label>Lembrar-me</label>
				<input type="checkbox" name="lembrar">
				<div class="clear"></div>
			</div>
		</form>
	</div><!-- box-login -->

</body>
</html>