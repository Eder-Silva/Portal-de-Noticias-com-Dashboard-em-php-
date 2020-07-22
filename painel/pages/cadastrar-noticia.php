<div class="box-content">
	<h2><i class="fa fa-pencil"></i> Cadastrar Notícia</h2>

	<form method="post" enctype="multipart/form-data">

		<?php
			//se o botao cadastrar for clicado
			if(isset($_POST['acao'])){
				$categoria_id = $_POST['categoria_id'];
				$titulo = $_POST['titulo'];
				$conteudo = $_POST['conteudo'];
				$capa = $_FILES['capa'];
				//SE TITULO OU CONTEUDO N FOI PREENCHIDO,APRESENTARA ERRO
				if($titulo == '' || $conteudo == ''){
					Painel::alert('erro','Campos Vázios não são permitidos!');
				}else if($capa['tmp_name'] == '' ){//SE ARQUIVO N FOI PREENCHIDO,APRESENTARA ERRO
					Painel::alert('erro','A imagem de capa precisa ser selecionada.');

					//CASO TODOS OS CAMPOS FORAM PREENCHIDOS
				}else{
					//SE É UMA IMAGEM VÁLIDA
					if(Painel::imagemValida($capa)){
						//IRA SELECIONAR NO BD JA TIVER CADASTRADO 
						$verifica = MySql::conectar()->prepare("SELECT * FROM `tb_site.noticias` WHERE titulo=? AND categoria_id = ?");
						$verifica->execute(array($titulo,$categoria_id));
						//SE O RESULTADO FOR IGUAL A 0, QUER DIZER QUE N EXISTE O CONTEUDO NO BD, ENTAO PODEMOS CADASTRAR
						if($verifica->rowCount() == 0){
							//faz o upload O ARQUIVO
							$imagem = Painel::uploadFile($capa);
							$slug = Painel::generateSlug($titulo);
							$arr = ['categoria_id'=>$categoria_id,'data'=>date('Y-m-d'),'titulo'=>$titulo,'conteudo'=>$conteudo,'capa'=>$imagem,'slug'=>$slug,
							'order_id'=>'0',
							'nome_tabela'=>'tb_site.noticias'
							];
							//SE CONSEGUIR INSERIR 
						if(Painel::insert($arr)){
						//REDIRECIONA PARA A URL UM GET['SUCESSO']
							Painel::redirect(INCLUDE_PATH_PAINEL.'cadastrar-noticia?sucesso');
						}

						//Painel::alert('sucesso','O cadastro da notícia foi realizado com sucesso!');
						}else{//SE N CONSEGUIR INSERIR 
							Painel::alert('erro','Já existe uma notícia com esse nome!');
						}
					}else{//SE A IMAGEM N E VALIDA
						Painel::alert('erro','Selecione uma imagem válida!');
					}
					
				}
				
				
			}//SE FOR CADASTRADA A NOTICIA com sucesso e o botao cadastrar n foi clicado,que dizer que foi passado diretamente pela url, APRESENTARA UM ALERTA DE SUCESSO
			if(isset($_GET['sucesso']) && !isset($_POST['acao'])){
				Painel::alert('sucesso','O cadastro foi realizado com sucesso!');
			}
		?>
		<div class="form-group">
		<label>Categoria:</label>
		<select name="categoria_id">
			<?php
				$categorias = Painel::selectAll('tb_site.categorias');
				foreach ($categorias as $key => $value) {
			?>
			<option <?php if($value['id'] == @$_POST['categoria_id']) echo 'selected'; ?> value="<?php echo $value['id'] ?>"><?php echo $value['nome']; ?></option>
			<?php } ?>
		</select>
		</div>

		<div class="form-group">
			<label>Título:</label>
			<input type="text" name="titulo" value="<?php recoverPost('titulo'); ?>">
		</div><!--form-group-->

		<div class="form-group">
			<label>Conteúdo</label>
			<textarea class="tinymce" name="conteudo"><?php recoverPost('conteudo'); ?></textarea>
		</div><!--form-group-->


		<div class="form-group">
			<label>Imagem</label>
			<input type="file" name="capa"/>
		</div><!--form-group-->

		<div class="form-group">
			<input type="hidden" name="order_id" value="0">
			<input type="hidden" name="nome_tabela" value="tb_site.noticias" />
			<input type="submit" name="acao" value="Cadastrar!">
		</div><!--form-group-->

	</form>



</div><!--box-content-->