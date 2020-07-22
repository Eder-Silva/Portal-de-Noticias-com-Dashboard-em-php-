<?php
	// /noticias/categorias/nomedanoticia
	//     [0]       [1]        [2]
	$url = explode('/',$_GET['url']);
	

	$verifica_categoria = MySql::conectar()->prepare("SELECT * FROM `tb_site.categorias` WHERE slug = ?");
	$verifica_categoria->execute(array($url[1]));
	if($verifica_categoria->rowCount() == 0){//se categoria não existe
		Painel::redirect(INCLUDE_PATH.'noticias');
	}
	//se existir a categoia ira retornar 
	$categoria_info = $verifica_categoria->fetch();

	$post = MySql::conectar()->prepare("SELECT * FROM `tb_site.noticias` WHERE slug = ? AND categoria_id = ?");
	$post->execute(array($url[2],$categoria_info['id']));
	if($post->rowCount() == 0){//se a variavel post n existir
		Painel::redirect(INCLUDE_PATH.'noticias');
	}

	//É POR QUE MINHA NOTICIA EXISTE
	$post = $post->fetch();

?>
<section class="noticia-single">
	<div class="center">
	<header>
		<h1><i class="fa fa-calendar"></i> <?php echo $post['data'] ?> - <?php echo $post['titulo'] ?></h1>
	</header>
	<article>
		<?php echo $post['conteudo']; ?>
	</article>
	</div>
</section>