<?php 
	if(isset($_GET['id'])){ //SE EXISTE O GET ID
		$id = (int)$_GET['id'];//PEGA O VALOR INTEIRO DO ID 
		//TABELA->tb_site.depoimentos
		//QUERY->id = ?
		//ARR->array($id)
		$servico = Painel::select('tb_site.servicos','id = ?',array($id));
	}else{
		Painel::alert('erro','Você precisa passar o parametro ID.');
		die();
	}
 ?>
<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Serviço</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
		//SE BOTA ATUALIZAR FOR CLICADO
			if(isset($_POST['acao'])){
				//SE ATALIZAR
				if(Painel::update($_POST)){
					Painel::alert('sucesso','O serviço foi editado com sucesso!');
					//SELECIONA NOVAMENTE A PAGINA, MAS COM OS ITENS ATUALIZADOS
					$servico = Painel::select('tb_site.servicos','id = ?',array($id));
				}else{//CASO N ATUALIZE
					Painel::alert('erro','Campos vázios não são permitidos.');
				}
			}
		?>

		<div class="form-group">
			<label>Servico:</label>
			<textarea name="servico"><?php echo $servico['servico']; ?></textarea>
		</div><!--form-group-->



		<div class="form-group">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="nome_tabela" value="tb_site.servicos" />
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->