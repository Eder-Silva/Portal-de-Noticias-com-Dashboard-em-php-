//funcao para esconder/mostrar menu do painel 
$(function(){

	var open  = true;//por padrao o nosso mennu ta sempre aberto
	//pegar o objeto da janela,[0] é para usar js nativo(.innerWidth ), .innerWidth pega o tamanho da tela com base no viewport
	var windowSize = $(window)[0].innerWidth;
	var targetSizeMenu = (windowSize <= 400) ? 200 : 250;

	if(windowSize <= 768){
		$('.menu').css('width','0').css('padding','0');
		open = false;//menu fechado
	}

	$('.menu-btn').click(function(){
		if(open){
			//O menu está aberto, precisamos fechar e adaptar nosso conteudo geral do painel
			//esconde o menu 
			$('.menu').animate({'width':0,'padding':0},function(){
				open = false;//menu fechado
			});
			//aumenta a largura do conteudo e o header
			$('.content,header').css('width','100%');
			$('.content,header').animate({'left':0},function(){
				open = false;//menu fechado
			});
		}else{
			//O menu está fechado, entao vamos abrir
			$('.menu').css('display','block');
			$('.menu').animate({'width':targetSizeMenu+'px','padding':'10px 0'},function(){
				open = true;//menu aberto
			});
			if(windowSize > 768)
				$('.content,header').css('width','calc(100% - 250px)');
				$('.content,header').animate({'left':targetSizeMenu+'px'},function(){
				open = true;//menu aberto
			});
		}
	})
	//para caso o cliente redimensione a tela
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

	$('[formato=data]').mask('99/99/9999');//JQUERY MASK,PARA FORMATAR DATAS

	//MOSTAR UMA CAIXA DE DIALOGO AO EXCLUIR 
	$('[actionBtn=delete]').click(function(){
			var txt;
			var r = confirm("Deseja excluir o registro?");
			if (r == true) {//PODE EXCLUIR
			    return true;
			} else {//CANCELAR A EXCLUSAO
			    return false;
			}
	})


})