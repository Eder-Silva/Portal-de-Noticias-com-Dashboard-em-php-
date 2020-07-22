<?php 
	//FUNÇÃO PARA CLICAR EM EXCLUIR(EXISTIR GET EXCLUIR)
	if(isset($_GET['excluir'])){
		$idExcluir = intval($_GET['excluir']);//PEGA O NUMERO DO ID
		//DELETAR CATEGORIA 
		Painel::deletar('tb_site.categorias',$idExcluir);

		//CASO FOR DELETADO ALGUMA CATEGORIA IRA DELETAR AS NOTICIAS DAQUELA CATEGORIA

		//SELECIONAR TODAS NOTICIAS DA CATEGORIA
		$noticias = MySql::conectar()->prepare("SELECT * FROM `tb_site.noticias` WHERE categoria_id = ?");
		$noticias->execute(array($idExcluir));
		$noticias = $noticias->fetchAll();
		foreach ($noticias as $key => $value) {
			$imgDelete = $value['capa'];
			//DELETAR O ARQUIVO DE IMAGEM
			Painel::deleteFile($imgDelete);
		}
		//DELETAR TODAS NOTICIAS DA CATEGORIA
		$noticias = MySql::conectar()->prepare("DELETE FROM `tb_site.noticias` WHERE categoria_id = ?");
		$noticias->execute(array($idExcluir));
		//REDIRECIONAR PARA A PROPRIA PAGINA
		Painel::redirect(INCLUDE_PATH_PAINEL.'gerenciar-categorias');
		//CASO SEJA SÓ PARA REORDENAR
	}else if(isset($_GET['order']) && isset($_GET['id'])){
		Painel::orderItem('tb_site.categorias',$_GET['order'],$_GET['id']);
	}
	//PAGINAÇÃO
	$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
	$porPagina = 4;
	
	$categorias = Painel::selectAll('tb_site.categorias',($paginaAtual - 1) * $porPagina,$porPagina);
	
?>
<div class="box-content">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Categorias Cadastradas</h2>
	<div class="wraper-table">
	<table>
		<tr>
			<td>Nome</td>
			<td>#</td>
			<td>#</td>
			<td>#</td>
			<td>#</td>
		</tr>

		<?php
			foreach ($categorias as $key => $value) {
		?>
		<tr>
			<td><?php echo $value['nome']; ?></td>
			<td><a class="btn edit" href="<?php echo INCLUDE_PATH_PAINEL ?>editar-categoria?id=<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> Editar</a></td>
			<td><a actionBtn="delete" class="btn delete" href="<?php echo INCLUDE_PATH_PAINEL ?>gerenciar-categorias?excluir=<?php echo $value['id']; ?>"><i class="fa fa-times"></i> Excluir</a></td>
			<td><a class="btn order" href="<?php echo INCLUDE_PATH_PAINEL ?>gerenciar-categorias?order=up&id=<?php echo $value['id'] ?>"><i class="fa fa-angle-up"></i></a></td>
			<td><a class="btn order" href="<?php echo INCLUDE_PATH_PAINEL ?>gerenciar-categorias?order=down&id=<?php echo $value['id'] ?>"><i class="fa fa-angle-down"></i></a></td>
		</tr>

		<?php } ?>

	</table>
	</div>

	<div class="paginacao">
		<?php
			$totalPaginas = ceil(count(Painel::selectAll('tb_site.categorias')) / $porPagina);

			for($i = 1; $i <= $totalPaginas; $i++){
				if($i == $paginaAtual)
					echo '<a class="page-selected" href="'.INCLUDE_PATH_PAINEL.'gerenciar-categorias?pagina='.$i.'">'.$i.'</a>';
				else
					echo '<a href="'.INCLUDE_PATH_PAINEL.'gerenciar-categorias?pagina='.$i.'">'.$i.'</a>';
			}

		?>
	</div><!--paginacao-->


</div><!--box-content-->