<?php 
	//FUNÇÃO PARA CLICAR EM EXCLUIR(EXISTIR GET EXCLUIR)
	if(isset($_GET['excluir'])){
		$idExcluir = intval($_GET['excluir']);//PEGA O NUMERO DO ID
		Painel::deletar('tb_site.depoimentos',$idExcluir);
		Painel::redirect(INCLUDE_PATH_PAINEL.'listar-depoimentos');//REDIRECIONAR

		//FUNÇÃO PARA CLICAR E ORDERNAR
	}else if(isset($_GET['order']) && isset($_GET['id'])){
		//$TABELA -> 'tb_site.depoimentos' $orderType -> $_GET['order'] //$idItem -> $_GET['id']		
		Painel::orderItem('tb_site.depoimentos',$_GET['order'],$_GET['id']);
	}

	//SISTEMA DE PAGINAÇÃO
	//SE EXISTIR PAGINA(PAGINAÇÃO),PEGA O NUMERO DE PAGINAS, CASO CONTRARIO SERÁ SO UMA PAGINA
	$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
	$porPagina = 4;
	
	//SELECIONA TUDO DA PAGINA ATUAL
	//$TABELA -> 'tb_site.depoimentos'
	//$START -> ($paginaAtual - 1) * $porPagina EX:(1-1)*4=0, (2-1)*4=4, (3-1)*4=8 (INDICE INICIAL)
	//$END -> $porPagina = 4 (INDICE FINAL)
	$depoimentos = Painel::selectAll('tb_site.depoimentos',($paginaAtual - 1) * $porPagina,$porPagina);
	
?>
<div class="box-content">
	<h2><i class="fa fa-id-card-o" aria-hidden="true"></i> Depoimentos Cadastrados</h2>
	<div class="wraper-table">
	<table>
		<tr>
			<td>Nome</td>
			<td>Data</td>
			<td>#</td>
			<td>#</td>
			<td>#</td> 
			<td>#</td>
		</tr>

		<?php
			foreach ($depoimentos as $key => $value) {
		?>
		<tr>
			<td><?php echo $value['nome']; ?></td>
			<td><?php echo $value['data']; ?></td>
			<td><a class="btn edit" href="<?php echo INCLUDE_PATH_PAINEL ?>editar-depoimento?id=<?php echo $value['id']; ?>"><i class="fa fa-pencil"></i> Editar</a></td>
			<!-- actionBtn="delete" É UMA ACAO PARA JS MOSTRAR UMA CAIXA DE DIALOGO(main.js) -->
			<td><a actionBtn="delete" class="btn delete" href="<?php echo INCLUDE_PATH_PAINEL ?>listar-depoimentos?excluir=<?php echo $value['id']; ?>"><i class="fa fa-times"></i> Excluir</a></td>

			<td><a class="btn order" href="<?php echo INCLUDE_PATH_PAINEL ?>listar-depoimentos?order=up&id=<?php echo $value['id'] ?>"><i class="fa fa-angle-up"></i></a></td>

			<td><a class="btn order" href="<?php echo INCLUDE_PATH_PAINEL ?>listar-depoimentos?order=down&id=<?php echo $value['id'] ?>"><i class="fa fa-angle-down"></i></a></td>
		</tr>

		<?php } ?>

	</table>
	</div>

	<div class="paginacao">
		<?php
			//CONTA A QUANTIDADE DE ELEMENTOS NA 'tb_site.depoimentos', DIVIDE POR 4 E Arredonda A frações para cima
			//EX:$totalPaginas=20/4 =>5 
			$totalPaginas = ceil(count(Painel::selectAll('tb_site.depoimentos')) / $porPagina);

			//EX:ENQUANTO 1 FOR MENOR OU IGUAL A 5, IRÁ PERCORRER O TRECHO DE CODIGO
			for($i = 1; $i <= $totalPaginas; $i++){
				//SE I=$totalPaginas, IRA INSERIR UM BOTAO DE LINK SELECIONADO 
				if($i == $paginaAtual)
					echo '<a class="page-selected" href="'.INCLUDE_PATH_PAINEL.'listar-depoimentos?pagina='.$i.'">'.$i.'</a>';
				//CASO I FOR DIFERENTE DE $totalPaginas, IRA INSERIR LINKS NÃO SELECIONADOS
				else
					echo '<a href="'.INCLUDE_PATH_PAINEL.'listar-depoimentos?pagina='.$i.'">'.$i.'</a>';
			}

		?>
	</div><!--paginacao-->


</div><!--box-content-->