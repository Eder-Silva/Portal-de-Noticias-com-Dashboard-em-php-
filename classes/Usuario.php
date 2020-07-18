<?php
	
	class Usuario{

		public function atualizarUsuario($nome,$senha,$imagem){
			$sql = MySql::conectar()->prepare("UPDATE `tb_admin.usuarios` SET nome = ?,password = ?,img = ? WHERE user = ?");
			//SE CONSEGUIR ATUALIZAR
			if($sql->execute(array($nome,$senha,$imagem,$_SESSION['user']))){
				return true;
			}else{//CASO N CONSEGUIR ATUALIZAR
				return false;
			}
		}

		//VERIFICAR SE O USUARIO JA EXXTE
		public static function userExists($user){
			$sql = MySql::conectar()->prepare("SELECT `id` FROM `tb_admin.usuarios` WHERE user=?");
			$sql->execute(array($user));
			//NUMERO DE RESULTADOS RETORNADOS FOR IGUAL A 1
			if($sql->rowCount() == 1)
				return true;
			else
				return false;
		}

		//CADASTRAR USUARIO NA PAGINA DE ADICIONAR USUARIOS
		public static function cadastrarUsuario($user,$senha,$imagem,$nome,$cargo){
			$sql = MySql::conectar()->prepare("INSERT INTO `tb_admin.usuarios` VALUES (null,?,?,?,?,?)");
			$sql->execute(array($user,$senha,$imagem,$nome,$cargo));
		}

	}

?>