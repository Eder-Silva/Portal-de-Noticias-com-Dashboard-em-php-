<?php 

	if(isset($_GET['id'])){//SE EXISTE O GET ID
		$id = (int)$_GET['id'];//PEGA O VALOR INTEIRO DO ID 
		//TABELA->tb_site.depoimentos
		//QUERY->id = ?
		//ARR->array($id)
		$depoimento = Painel::select('tb_site.depoimentos','id = ?',array($id));
	}else{
		Painel::alert('erro','Você precisa passar o parametro ID.'); 
		die();
	}
 ?>
<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Depoimento</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
		//SE BOTA ATUALIZAR FOR CLICADO
			if(isset($_POST['acao'])){
				//SE ATALIZAR
				if(Painel::update($_POST)){
					Painel::alert('sucesso','O depoimento foi editado com sucesso!');
					//SELECIONA NOVAMENTE A PAGINA, MAS COM OS ITENS ATUALIZADOS
					$depoimento = Painel::select('tb_site.depoimentos','id = ?',array($id));
				}else{//CASO N ATUALIZE
					Painel::alert('erro','Campos vázios não são permitidos.');
				}
			}
		?>

		<div class="form-group">
			<label>Nome da pessoa:</label>
			<input type="text" name="nome" value="<?php echo $depoimento['nome']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<label>Depoimento:</label>
			<textarea name="depoimento"><?php echo $depoimento['depoimento']; ?></textarea>
		</div><!--form-group-->

		<div class="form-group">
			<label>Data:</label>
			<input formato="data" type="text" name="data" value="<?php echo $depoimento['data']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="nome_tabela" value="tb_site.depoimentos" />
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->