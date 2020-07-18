<?php
	if(isset($_GET['id'])){ //SE EXISTE O GET ID
		$id = (int)$_GET['id'];//PEGA O VALOR INTEIRO DO ID 
		//TABELA->tb_site.depoimentos
		//QUERY->id = ?
		//ARR->array($id)
		$slide = Painel::select('tb_site.slides','id = ?',array($id));
	}else{
		Painel::alert('erro','Você precisa passar o parametro ID.');
		die();
	}
?>
<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Slide</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
		//SE BOTA ATUALIZAR FOR CLICADO
			if(isset($_POST['acao'])){//SE ATALIZAR
				
				$nome = $_POST['nome'];
				$imagem = $_FILES['imagem'];
				$imagem_atual = $_POST['imagem_atual'];
				
				//SE FOI SELECIONADA UMA IMAGEM
				if($imagem['name'] != ''){
					//SE A IMAGEM E VALIDA
					if(Painel::imagemValida($imagem)){
						//DELETA A IM,AGEM ATUAL
						Painel::deleteFile($imagem_atual);
						$imagem = Painel::uploadFile($imagem);
						$arr = ['nome'=>$nome,'slide'=>$imagem,'id'=>$id,'nome_tabela'=>'tb_site.slides'];
						//ATUALIZA A IMAGEM
						Painel::update($arr);
						//SELECIONA NOVAMENTE A PAGINA, MAS COM OS ITENS ATUALIZADOS
						$slide = Painel::select('tb_site.slides','id = ?',array($id));
						Painel::alert('sucesso','O Slide foi editado junto com a imagem!');
					}else{//CASO N ATUALIZE
						Painel::alert('erro','O formato da imagem não é válido');
					}
				}else{//SE A IMAGEM N E VALIDA
					$imagem = $imagem_atual;
					$arr = ['nome'=>$nome,'slide'=>$imagem,'id'=>$id,'nome_tabela'=>'tb_site.slides'];
					Painel::update($arr);
					//SELECIONA NOVAMENTE A PAGINA, MAS COM OS ITENS ATUALIZADOS
					$slide = Painel::select('tb_site.slides','id = ?',array($id));
					Painel::alert('sucesso','O Slide foi editado com sucesso!');
				}

			}
		?>

		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="nome" required value="<?php echo $slide['nome']; ?>">
		</div><!--form-group-->


		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="imagem"/>
			<input type="hidden" name="imagem_atual" value="<?php echo $slide['slide']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->