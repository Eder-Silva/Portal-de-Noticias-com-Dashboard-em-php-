<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Cadastrar Categoria</h2>

	<form method="post" enctype="multipart/form-data">

		<?php

			if(isset($_POST['acao'])){//se o botao cadastrar foi clicado(formulario enviado)
				$nome = $_POST['nome'];
				if($nome == ''){//se o usuario n inserir o campo nome da categoria
					Painel::alert('erro','O campo nome não pode ficar vázio!');
				}else{//caso o usuario inseriu o campo nome
					//Apenas cadastrar no banco de dados!
					$verificar = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` WHERE nome = ?");
					$verificar->execute(array($_POST['nome']));
					if($verificar->rowCount() == 0){
					$slug = Painel::generateSlug($nome);//gerar um slug da categoria

					$arr = ['nome'=>$nome,'slug'=>$slug,'order_id'=>'0','nome_tabela'=>'tb_site.categorias'];
					Painel::insert($arr);
					Painel::alert('sucesso','O cadastro da categoria foi realizado com sucesso!');
					}else{
						Painel::alert("erro",'Já existe uma categoria com este nome!');
					}
				}
				
			}
		?>

		<div class="form-group">
			<label>Nome da categoria:</label>
			<input type="text" name="nome">
		</div><!--form-group-->


		<div class="form-group">
			<input type="submit" name="acao" value="Cadastrar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->