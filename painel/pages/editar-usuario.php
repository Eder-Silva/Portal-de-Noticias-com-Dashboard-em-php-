<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Usuário</h2>

	<!--multipart/form-data PARA UPLOAD DE IMAGENS -->
	<form method="post" enctype="multipart/form-data">

		<?php
		//SE EXISTIR A ACAO
			if(isset($_POST['acao'])){
				//Enviei o meu formulário.
				
				$nome = $_POST['nome'];
				$senha = $_POST['password'];
				$imagem = $_FILES['imagem'];
				$imagem_atual = $_POST['imagem_atual'];
				$usuario = new Usuario();

				//VERIFICAR SE ALGUMA IMAGEM FOI SELECIONADA
				//ALGUMA IMAGEM FOI SELECIONADA
				if($imagem['name'] != ''){
					//SE O TIPO DA IMAGEM É VÁLIDA(JPG,JPG,ETC)
					if(Painel::imagemValida($imagem)){
						//DELETA A IMAGEM ANTIGA
						Painel::deleteFile($imagem_atual);
						$imagem = Painel::uploadFile($imagem);
						if($usuario->atualizarUsuario($nome,$senha,$imagem)){
							$_SESSION['img'] = $imagem;//APARECER A NOVA IMAGEM
							Painel::alert('sucesso','Atualizado com sucesso junto com a imagem!');
						}else{
							Painel::alert('erro','Ocorreu um erro ao atualizar junto com a imagem');
						}
					}else{
						Painel::alert('erro','O formato da imagem não é válido');
					}

				//NÃO TEM IMAGEM SELECIONADA	
				}else{
					$imagem = $imagem_atual;//A IMAGEM SERA NOSSA IMAGEM ATUAL
					//SE USUARIO FOI EDITADO COM SUCESSO
					if($usuario->atualizarUsuario($nome,$senha,$imagem)){
						Painel::alert('sucesso','Atualizado com sucesso!');
					}else{//CASO O USUARO NÃO FOI EDITADO COM SUCESSO
						Painel::alert('erro','Ocorreu um erro ao atualizar...');
					}
				}

			}
		?>

		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required value="<?php echo $_SESSION['nome']; ?>">
		</div><!--form-group-->
		<div class="form-group">
			<label>Senha:</label>
			<input type="password" name="password" value="<?php echo $_SESSION['password']; ?>" required>
		</div><!--form-group-->

		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="imagem"/>
			<input type="hidden" name="imagem_atual" value="<?php echo $_SESSION['img']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->