<?php 
	if(isset($_GET['id'])){//se existir o id
		$id = (int)$_GET['id'];//transforma o id em inteiro
		$categoria = Painel::select('tb_site.categorias','id = ?',array($id));
	}else{//se n existir o id
		Painel::alert('erro','Você precisa passar o parametro ID.');
		die();
	}
 ?>
<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Categoria</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
			//se o botao Atualizar foi clicado
			if(isset($_POST['acao'])){
				$slug = Painel::generateSlug($_POST['nome']);
				//array_merge()->combinar um ou mais array
				//inserir o valor de slug ao post
				$arr = array_merge($_POST,array('slug'=>$slug));
				//para verificar se existe com O MESMO NOME E ID
				$verificar = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` WHERE nome = ? AND id != ?");
				$verificar->execute(array($_POST['nome'],$id));
				$info = $verificar->fetch();
				
				if($verificar->rowCount() == 1){//se ja existir a categoria
					Painel::alert("erro",'Já existe uma categoria com este nome!');
					
				}else{//se n existir a categoria
					
				if(Painel::update($arr)){//se atualizar a categoria
					Painel::alert('sucesso','A categoria foi editada com sucesso!');
					$categoria = Painel::select('tb_site.categorias','id = ?',array($id));

				}else{//se n atualizar a categoria
					Painel::alert('erro','Campos vázios não são permitidos.');
				}
				}
			}
		?>

		<div class="form-group">
			<label>Categoria:</label>
			<input type="text" name="nome" value="<?php echo $categoria['nome']; ?>">
		</div><!--form-group-->



		<div class="form-group">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="nome_tabela" value="tb_site.categorias" />
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->