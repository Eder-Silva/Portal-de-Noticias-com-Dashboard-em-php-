<?php 
	//SE FOR PASSADO ALGUM ID PELO GET
	if(isset($_GET['id'])){
		$id = (int)$_GET['id'];//CONVERTE O ID PARA INTEIRO
		$slide = Painel::select('tb_site.noticias','id = ?',array($id));//SELECIONA O ID
	}else{//SE N FOR PASSADO ALGUM ID PELO GET
		Painel::alert('erro','Você precisa passar o parametro ID.');
		die();
	}
?>
<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Editar Notícia</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
		//SE O BOTAO Atualizar FOR CLICADO
			if(isset($_POST['acao'])){
				
				$nome = $_POST['titulo'];
				$conteudo = $_POST['conteudo'];
				$imagem = $_FILES['capa'];
				$imagem_atual = $_POST['imagem_atual'];
				//para verificar se existe com O MESMO TITULO, CATEGORIA_ID E ID
				$verifica = MySql::conectar()->prepare("SELECT `id` FROM `tb_site.noticias` WHERE titulo = ? AND categoria_id = ? AND id != ?");
				$verifica->execute(array($nome,$_POST['categoria_id'],$id));
				//SE O RESULTADO FOR IGUAL A 0, QUER DIZER QUE N EXISTE A NOTICIA NO BD, ENTAO PODEMOS CADASTRAR
				if($verifica->rowCount() == 0){
				if($imagem['name'] != ''){//SE ARQUIVO FOI PREENCHIDO,APRESENTARA SUCESSO
					
					if(Painel::imagemValida($imagem)){//SE É UMA IMAGEM VÁLIDA
						Painel::deleteFile($imagem_atual);//DELETA A IMAGEM ATUAL
						$imagem = Painel::uploadFile($imagem);//ATUALIZA PARA A NOVA IMAGEM
						$slug = Painel::generateSlug($nome);
						$arr = ['titulo'=>$nome,'data'=>date('Y-m-d'),'categoria_id'=>$_POST['categoria_id'],'conteudo'=>$conteudo,'capa'=>$imagem,'slug'=>$slug,'id'=>$id,'nome_tabela'=>'tb_site.noticias'];
						Painel::update($arr);//ATUALIZA O CAMPO NO BD
						//RECUPERAR OS VALORES ATUALIZADOS
						$slide = Painel::select('tb_site.noticias','id = ?',array($id));
						Painel::alert('sucesso','A notícia foi editada com junto com a imagem!');
					}else{//SE N É UMA IMAGEM VÁLIDA
						Painel::alert('erro','O formato da imagem não é válido');
					}
				}else{//SE ARQUIVO N FOI PREENCHIDO,ATUALIZA TUDO, MENOS A IMAGEM
					$imagem = $imagem_atual;
					$slug = Painel::generateSlug($nome);
					$arr = ['titulo'=>$nome,'categoria_id'=>$_POST['categoria_id'],'conteudo'=>$conteudo,'capa'=>$imagem,'slug'=>$slug,'id'=>$id,'nome_tabela'=>'tb_site.noticias'];
					Painel::update($arr);//ATUALIZA O CAMPO NO BD
					//RECUPERAR OS VALORES ATUALIZADOS
					$slide = Painel::select('tb_site.noticias','id = ?',array($id));
					Painel::alert('sucesso','A notícia foi editada com sucesso!');
				}
				}else{//SE JA EXISTIR UMA NOTICIA COM ESSE NOME
					Painel::alert('erro','Já existe uma notícia com este nome!');
				}

			}
		?>

		<div class="form-group">
			<label>Nome:</label>
			<input type="text" name="titulo" required value="<?php echo $slide['titulo']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<label>Conteúdo:</label>
			<textarea class="tinymce" name="conteudo"><?php echo $slide['conteudo']; ?></textarea>
		</div><!--form-group-->

		<div class="form-group">
		<label>Categoria:</label>
		<select name="categoria_id">
			<?php
				$categorias = Painel::selectAll('tb_site.categorias');
				foreach ($categorias as $key => $value) {
			?>
			<option <?php if($value['id'] == $slide['categoria_id']) echo 'selected'; ?> value="<?php echo $value['id'] ?>"><?php echo $value['nome']; ?></option>
			<?php } ?>
		</select>
		</div>

		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="capa"/>
			<input type="hidden" name="imagem_atual" value="<?php echo $slide['capa']; ?>">
		</div><!--form-group-->

		<div class="form-group">
			<input type="submit" name="acao" value="Atualizar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->