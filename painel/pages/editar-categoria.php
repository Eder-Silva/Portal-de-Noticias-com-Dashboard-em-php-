<?php 
//AO CLICAR EM EDITAR NA PAGINA GERENCIAR-NOTICIAS
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
				//gerar um slug da categoria
				$slug = Painel::generateSlug($_POST['nome']);
				//array_merge()->combinar um ou mais array
				//inserir o valor de slug ao post
				$arr = array_merge($_POST,array('slug'=>$slug));
				//verifica se existe alguma outra categoria com o nome passado
				$verificar = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` WHERE nome = ? AND id != ?");
				$verificar->execute(array($_POST['nome'],$id));
				$info = $verificar->fetch();
				if($verificar->rowCount() == 1){//já existe uma categoria com este nome!
					Painel::alert("erro",'Já existe uma categoria com este nome!');
				}else{//n existe uma categoria com este nome, pode cadastrar
				if(Painel::update($arr)){//Atualizar
					Painel::alert('sucesso','A categoria foi editada com sucesso!');
					//carregar os campos novamente, agora atualizados
					$categoria = Painel::select('tb_site.categorias','id = ?',array($id));
				}else{//se n atualizar a categoria, apresentara erro
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