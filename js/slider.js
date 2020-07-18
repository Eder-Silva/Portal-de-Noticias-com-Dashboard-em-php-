$(function(){

	var curSlide = 0;//slide atual
	var maxSlide = $('.banner-single').length - 1;//maxSlide é o tamanha total da classe .banner-single -1
	var delay = 3;


	initSlider();
	changeSlide();


	function initSlider(){
		//esconde a imagem
		$('.banner-single').css('opacity','0');
		//mostrar apenas a 1 imagem do .banner-single
		$('.banner-single').eq(0).css('opacity','1');
		for(var i = 0; i < maxSlide+1; i++){
			//vai pegar a tag html que contenha a classe bullets
			var content = $('.bullets').html();
			//se i e igual a 0 a primeira bolinha fica branca
			if(i == 0)
				//ira criar uma tag span com uma classe active-slider
				content+='<span class="active-slider"></span>';
			else
				//ira criar uma tag span vazia
				content+='<span></span>';
			//ira inserir a tag span dentro da tag q contenha a classe bullets
			$('.bullets').html(content);
		}
	}

	function changeSlide(){
		// setInterval () chama uma função ou avalia uma expressão em intervalos 
		//especificados (em milissegundos).
		setInterval(function(){
			//vai esconder o primeiro slide
			$('.banner-single').eq(curSlide).animate({'opacity':'0'},2000);
			//passa p o proximo slide
			curSlide++;
			//se curSlide for maior q maxSlide
			if(curSlide > maxSlide){
				//curSlide voltara p o primeiro
				curSlide = 0;
			}
			//se curSlide for n maior q maxSlide, mostrara o proximo
			$('.banner-single').eq(curSlide).animate({'opacity':'1'},2000);
			//Trocar bullets(as bolinhas abaixo da img) da navegacao do slider!
			$('.bullets span').removeClass('active-slider');
			$('.bullets span').eq(curSlide).addClass('active-slider');
		},delay * 1000);
	}

	//tracar o slide ao clicar na bolinha
	//no body quando a bolnha for clicado
	$('body').on('click','.bullets span',function(){
		//currentBullet é igual a bolinha atual
		var currentBullet = $(this);
		//esconde a imagem anterior
		$('.banner-single').eq(curSlide).animate({'opacity':'0'},2000);
		//curSlide será o indice da bolinha clicada
		curSlide = currentBullet.index();
		//mostra a imagem da na bolinha clicada 
		$('.banner-single').eq(curSlide).animate({'opacity':'1'},2000);
		//remove a classe da bolinha 
		$('.bullets span').removeClass('active-slider');
		//insere a classe na bolnha clicada
		currentBullet.addClass('active-slider');
	});

})