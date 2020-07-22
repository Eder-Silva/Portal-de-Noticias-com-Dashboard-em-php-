<?php
	$url = explode('/',$_GET['url']);
	// /noticias/categorias/nomedanoticia
	//     [0]       [1]        [2]
	//se nao existir uma noticia selecionada, ira listar as noticias
	if(!isset($url[2]))
	{
	//conectar com a tabela tb_site.categorias onde slug=categoria passada pelo get $url[1]
	$categoria = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` WHERE slug = ?");
	$categoria->execute(array(@$url[1]));
	$categoria = $categoria->fetch();
?>

<section class="header-noticias">
	<div class="center">
		<h2><i class="far fa-bell" aria-hidden="true"></i></h2>
		<h2 style="font-size: 21px;">Acompanhe as últimas <b>notícias do portal</b></h2>
	</div><!--center-->
</section>

<section class="container-portal">
	<div class="center">
		
			<div class="sidebar">
				<div class="box-content-sidebar">
					<h3><i class="fa fa-search"></i> Realizar uma busca:</h3>
					<form method="post">
						<input type="text" name="parametro" placeholder="O que deseja procurar?" required>
						<input type="submit" name="buscar" value="Pesquisar!">
					</form>
				</div><!--box-content-sidebar-->
				<div class="box-content-sidebar">
					<h3><i class="fa fa-list-ul" aria-hidden="true"></i> Selecione a categoria:</h3>
					<form>
						<select name="categoria">
						<option value="" selected="">Todas as categorias</option>
							<?php
							//conectar com a tabela tb_site.categorias com ordenação crescente pelo order_id 
								$categorias = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` ORDER BY order_id ASC");
								$categorias->execute();
								//recuperar todos os dados da tabela tb_site.categorias
								$categorias = $categorias->fetchAll();
								foreach ($categorias as $key => $value) {
								
							?>
							<!-- se o nome da opção for o mesmo da categoria passada pelo get url[1](categorias), ficara selecionado-->
								<option <?php if($value['slug'] == @$url[1]) echo 'selected'; ?> value="<?php echo $value['slug'] ?>">
									<?php echo $value['nome']; ?>
								</option>
							<?php } ?>
							
						</select>
					</form>
				</div><!--box-content-sidebar-->
				<div class="box-content-sidebar">
					<h3><i class="fa fa-user" aria-hidden="true"></i> Sobre o autor:</h3>
						<div class="autor-box-portal">
							<div class="box-img-autor"></div>
							<div class="texto-autor-portal text-center">
								<?php
								//conectar com a tabela tb_site.config
									$infoSite = MySql::conectar()->prepare("SELECT * FROM `tb_site.config`");
									$infoSite->execute();
									//recuperar um unico dado
									$infoSite = $infoSite->fetch();
								 ?>
								<h3><?php echo $infoSite['nome_autor'] ?></h3>
								<!-- substr() serve para limitar a quantidade de string, do 0 até 300 -->
								<p><?php echo substr($infoSite['descricao'],0,300).'...' ?></p>
							</div><!--texto-autor-portal-->
						</div><!--autor-box-portal-->
				</div><!--box-content-sidebar-->
			</div><!--sidebar-->

			<div class="conteudo-portal">
					<div class="header-conteudo-portal">
						<?php
						//sistema de quantidade de noticias por paginas
							$porPagina = 10;
							if(!isset($_POST['parametro'])){//se n existir parametro(busca)	
							if(@$categoria['nome'] == ''){//se nome da categoria estiver vazio
								echo '<h2>Visualizando todos os Posts</h2>';
							}else{//se nome da categoria n estiver vazio
								echo '<h2>Visualizando Posts em <span>'.$categoria['nome'].'</span></h2>';
							}
							}else{//se existir parametro(busca)
								echo '<h2><i class="fa fa-check"></i> Busca realizada com sucesso!</h2>';
							}

							$query = "SELECT * FROM `tb_site.noticias` ";
							if(@$categoria['nome'] != ''){//se nome n estiver vazio, existe uma categoria valida 
								$categoria['id'] = (int)$categoria['id'];//pega o id convertido para inteiro
								//seleciona tuda de tb_site.noticias onde a categoria_id seja igual ao id
								$query.="WHERE categoria_id = $categoria[id]";
							}
							if(isset($_POST['parametro'])){//se parametro(busca) existir
								if(strstr($query,'WHERE') !== false){//verificar se ja existe WHERE
									$busca = $_POST['parametro'];//parametro passado no campo de busca
									//"SELECT * FROM `tb_site.noticias` WHERE categoria_id = $categoria[id] AND titulo LIKE '%$busca%'"
									//selecone tuda de `tb_site.noticias` onde a categoria E titulo é parecido com o passado no campo busca
									$query.=" AND titulo LIKE '%$busca%'";
								}else{//verificar se n existe WHERE
									$busca = $_POST['parametro'];//parametro passado no campo de busca
									//"SELECT * FROM `tb_site.noticias` WHERE titulo LIKE '%$busca%'"
									//selecone tuda de `tb_site.noticias` ONDE titulo é parecido com o passado no campo busca
									$query.=" WHERE titulo LIKE '%$busca%'";
								}
							}
							$query2 = "SELECT * FROM `tb_site.noticias` "; 
							if(@$categoria['nome'] != ''){//se nome n estiver vazio
									$categoria['id'] = (int)$categoria['id'];//converte id para inteiro
									//seleciona tuda de tb_site.noticias onde a categoria_id seja igual ao id
									$query2.="WHERE categoria_id = $categoria[id]";
							}
							if(isset($_POST['parametro'])){//se parametro(busca) existir
								if(strstr($query2,'WHERE') !== false){//verificar se ja existe WHERE
									
									$busca = $_POST['parametro'];//parametro passado no campo de busca
									//"SELECT * FROM `tb_site.noticias` WHERE categoria_id = $categoria[id] AND titulo LIKE '%$busca%'"
									//selecone tuda de `tb_site.noticias` onde a categoria E titulo é parecido com o passado no campo busca
									$query2.=" AND titulo LIKE '%$busca%'";
								}else{//verificar se n existe WHERE
									$busca = $_POST['parametro'];//parametro passado no campo de busca
									//"SELECT * FROM `tb_site.noticias` WHERE titulo LIKE '%$busca%'"
									//selecone tuda de `tb_site.noticias` ONDE titulo é parecido com o passado no campo busca
									$query2.=" WHERE titulo LIKE '%$busca%'";
								}
							}
							$totalPaginas = MySql::conectar()->prepare($query2);
							$totalPaginas->execute();
							//paginação
							$totalPaginas = ceil($totalPaginas->rowCount() / $porPagina);
							if(!isset($_POST['parametro'])){//se n existir parametro(busca), tera paginação
								if(isset($_GET['pagina'])){//se existir a pagina
									$pagina = (int)$_GET['pagina'];//converte pagina para inteiro
									if($pagina > $totalPaginas){//se a pagina for maior que o total de paginas, retornara para a 1
										$pagina = 1;
									}
									//mostrar de 10 em 10
									$queryPg = ($pagina - 1) * $porPagina;
									//pagina (1-1=0)*10=>0,(2-1=1)*10=>10,(3-1=2)*10=>20
									//"SELECT * FROM `tb_site.noticias` ORDER BY order_id ASC LIMIT 0,10"
									$query.=" ORDER BY order_id ASC LIMIT $queryPg,$porPagina";
								}else{//se n existir a pagina
									$pagina = 1;
									//"SELECT * FROM `tb_site.noticias` ORDER BY order_id ASC LIMIT 0,10"
									$query.=" ORDER BY order_id ASC LIMIT 0,$porPagina";
								}
							}else{//se existir parametro(busca), n tera paginação
								//"SELECT * FROM `tb_site.noticias` ORDER BY order_id ASC "

								$query.=" ORDER BY order_id ASC";
							}
							$sql = MySql::conectar()->prepare($query);
							$sql->execute();
							$noticias = $sql->fetchAll();
						?>
						
						
					</div>
					<?php
					print_r($query2);
					//aqui pega da `tb_site.noticias`
						foreach($noticias as $key=>$value){
							//aqui pega da `tb_site.categorias`
							//selecione os slugs da `tb_site.categorias` onde o id, seja igual a categoria_id 
							//da tabela `tb_site.noticias`
						$sql = MySql::conectar()->prepare("SELECT `slug` FROM `tb_site.categorias` WHERE id = ?");
						//$value['categoria_id'] -> e da `tb_site.noticias`
						$sql->execute(array($value['categoria_id']));							
						$categoriaNome = $sql->fetch()['slug'];//retorna o slug
					?>
					<div class="box-single-conteudo">
					<!--converter a data para formato BR-->
						<h2><?php echo date('d/m/Y',strtotime($value['data'])) ?> - <?php echo $value['titulo']; ?></h2>
						<!--substr() para pegar o conteudo do inde 0 até o 400-->
						<!-- strip_tags() para remover todas as tags html contidos -->
						<p><?php echo substr(strip_tags($value['conteudo']),0,400).'...'; ?></p>
							
						<a href="<?php echo INCLUDE_PATH; ?>noticias/<?php echo $categoriaNome; ?>/<?php echo $value['slug']; ?>">
						Leia mais</a>
					</div><!--box-single-conteudo-->
					<?php } ?>

					

					<div class="paginator">
						<?php
							if(!isset($_POST['parametro'])){//se n existir parametro(busca), aparecerá a paginação
							for($i = 1; $i <= $totalPaginas; $i++){
								//SE NOME DA CATEGORIA FOI PREENCHIDO, ENTAO /SLUG DA CATEGORIA, CASO CONTRARIO FICA VAZIO
								$catStr = (@$categoria['nome'] != '') ? '/'.$categoria['slug'] : '';
								if($pagina == $i)
									echo '<a class="active-page" href="'.INCLUDE_PATH.'noticias'.$catStr.'?pagina='.$i.'">'.$i.'</a>';
								else
									echo '<a href="'.INCLUDE_PATH.'noticias'.$catStr.'?pagina='.$i.'">'.$i.'</a>';
							}
							}
						?>
						
					</div><!--paginator-->
			</div><!--conteudo-portal-->


			<div class="clear"></div>
	</div><!--center-->

</section><!--container-portal-->

<?php }else{ //caso tenha uma noticia selecionada, redirecionara para noticia_single.php
	include('noticia_single.php');
}
?>

