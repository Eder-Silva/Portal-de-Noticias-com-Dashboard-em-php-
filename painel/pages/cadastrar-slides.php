
<div class="box-content">
	<h2> <i class="fa fa-pencil-alt"></i> Cadastrar Slide</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
			if(isset($_POST['acao'])){
				
				$nome = $_POST['nome'];				
				$imagem = $_FILES['imagem'];
				

				//validar os campos
				if ($nome == '') {
					Painel::alert('erro','O campo Nome está vazio!');
				}else{
					//podemos cadastrar
					if(Painel::imagemValida($imagem) == false){
						Painel::alert('erro','O formato especificado não está correto!');
					}else{
						//Apenas cadastrar no bd
						include('../classes/lib/WideImage.php');
						$imagem = Painel::uploadFile($imagem);
						WideImage::load('uploads/'.$imagem)->resize(100)->saveToFile('uploads/'.$imagem);
						$arr = ['nome'=>$nome,'slide'=>$imagem,'order_id'=>'0','nome_tabela'=>'tb_site.slides'];
						Painel::insert($arr);
						Painel::alert('sucesso','O cadastro do slide foi realizado com sucesso!');
					}
				}			
				
				
			}
				
		?>

		<div class="form-group">
			<label >Nome do Slide: </label>
			<input type="text" name="nome">
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