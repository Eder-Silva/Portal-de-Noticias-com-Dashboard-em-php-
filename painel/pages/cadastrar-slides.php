<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Cadastrar Slide</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
			//SE O BOTAO CADASTRAR FOR CLICADO
			if(isset($_POST['acao'])){
				$nome = $_POST['nome'];
				$imagem = $_FILES['imagem'];
				//SE O NOME N FOR PREENCHIDO
				if($nome == ''){
					Painel::alert('erro','O campo nome não pode ficar vázio!');
				}else{//SE O NOME FOI PREENCHIDO
					//SE A IMAGEM N FOR VALIDA, APARECERA MENSAGEM DE ERRO
					if(Painel::imagemValida($imagem) == false){
						Painel::alert('erro','O formato especificado não está correto!');
					}else{//SE A IMAGEM FOR VALIDA
						
						include('../classes/lib/WideImage.php');
						$imagem = Painel::uploadFile($imagem);
						//ESSE ABAIXO E O WIDEIMAGE, além de redimensionar fornece várias funcionalidades como marca d’água e recorte.
						//WideImage::load('uploads/'.$imagem)->resize(100)->rotate(180)->saveToFile('uploads/'.$imagem);
						$arr = ['nome'=>$nome,'slide'=>$imagem,'order_id'=>'0','nome_tabela'=>'tb_site.slides'];
						Painel::insert($arr);
						Painel::alert('sucesso','O cadastro do slide foi realizado com sucesso!');
					}
				}
				
			}
		?>

		<div class="form-group">
			<label>Nome do slide:</label>
			<input type="text" name="nome">
		</div><!--form-group-->


		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="imagem"/>
		</div><!--form-group-->

		<div class="form-group">
			<input type="submit" name="acao" value="Cadastrar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->