<?php
	verificaPermissaoPagina(2);
				
?>

<div class="box-content">
	<h2> <i class="fa fa-pencil-alt"></i> Adicionar Usuário</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
			if(isset($_POST['acao'])){
				$login = $_POST['login'];
				$nome = $_POST['nome'];
				$senha = $_POST['password'];
				$imagem = $_FILES['imagem'];
				$cargo = $_POST['cargo'];

				//validar os campos
				if ($login == '') {
					Painel::alert('erro','O campo Login está vazio!');
				}else if($nome == ''){
					Painel::alert('erro','O campo Nome está vazio!');
				}else if($senha == ''){
					Painel::alert('erro','O campo Senha está vazio!');
				}else if($cargo == ''){
					Painel::alert('erro','O campo Cargo não está selecionado!');
				}else if($imagem['name'] == ''){
					Painel::alert('erro','A Imagem não está selecionada!');
				}else{
					//podemos cadastrar
					if($cargo >= $_SESSION['cargo']){
						Painel::alert('erro','É preciso selecionar um cargo menor ou igual ao seu!');
					}else if(Painel::imagemValida($imagem) == false){
						Painel::alert('erro','O formato especificado não está correto!');
					}else if(Usuario::userExists($login)){
						Painel::alert('erro','O login já existe!');
					}else{
						//Apenas cadastrar no bd
						$usuario = new Usuario();
						$imagem = Painel::uploadFile($imagem);
						$usuario->cadastrarUsuario($login, $senha, $imagem, $nome, $cargo);
						Painel::alert('sucesso','O cadastro do usuário '.$login.' foi feito com sucesso!');
					}
				}			
				
				
			}
				
		?>

		<div class="form-group">
			<label >Login: </label>
			<input type="text" name="login">
		</div><!-- form-group -->
		
		<div class="form-group">
			<label >Nome: </label>
			<input type="text" name="nome">
		</div><!-- form-group -->

		<div class="form-group">
			<label >Senha: </label>
			<input type="password" name="password">
		</div><!-- form-group -->

		<div class="form-group">
			<label >Cargo: </label>
			<select name="cargo">
				<?php
					foreach (Painel::$cargos as $key => $value) {
						if($key <= $_SESSION['cargo']) echo '<option value="'.$key.'">'.$value.'</option>';
					}
				?>
			</select> 
		</div><!-- form-group -->

		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="imagem"/>			
		</div><!--form-group-->

		<div class="form-group">			
			<input type="submit" name="acao" value="Cadastrar">
		</div><!-- form-group -->

	</form>

</div><!-- box-content -->