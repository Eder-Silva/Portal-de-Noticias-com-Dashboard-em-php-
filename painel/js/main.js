$(function(){
	
	var open = true;
	//o  [0] quer dizer que trabalharemos com as funções nativas do javascript, nesse caso o .innerWidth,
	// que pega o tamanho real com base no viewport
	var windowSize = $(window)[0].innerWidth;

	var targetSizeMenu = (windowSize <= 400) ? 200 : 300;

	if (windowSize <= 768) {
		$('.menu').css('width','0').css('padding','0');		
		open = false;
	}

	$('.menu-btn').click(function(){
		
		if (open) {
			//o menu esta aberto,precisamos fechar e adaptar o conteudo geral do painel			
			$('.menu').animate({'width':0,'padding':0},function(){
				open = false;
			});
			$('.content,header').css('width','100%');
			$('.content,header').animate({'left':0},function(){
				open = false;
			});

		}else{
			//o menu esta fechado
			$('.menu').css('display','block');
			$('.menu').animate({'width':targetSizeMenu+'px','padding':'10px'},function(){
				open = true;
			});
			//$('.content,header').css('width','calc(100% - 300px)');
			$('.content,header').animate({'left':targetSizeMenu+'px'},function(){
				open = true;
			});

		}

	})

	$(window).resize(function(){
		windowSize = $(window)[0].innerWidth;
		targetSizeMenu = (windowSize <= 400) ? 200 : 250;
		if(windowSize <= 768){
			$('.menu').css('width','0').css('padding','0');
			$('.content,header').css('width','100%').css('left','0');
			open = false;
		}else{
			$('.menu').animate({'width':targetSizeMenu+'px','padding':'10px 0'},function(){
				open = true;
			});

			$('.content,header').css('width','calc(100% - 250px)');
			$('.content,header').animate({'left':targetSizeMenu+'px'},function(){
			open = true;
			});
		}

	})

	//atributo para formato de data utilizando o jquery mask
	$('[formato=data]').mask('99/99/9999');

	//caixa de confirmação de exclusão
	$('[actionBtn=delete]').click(function(){
		var txt;
		var r = confirm("Deseja excluir o registro?");
		if (r == true) {
		    return true;
		} else {
		    return false;
		}
	})

})