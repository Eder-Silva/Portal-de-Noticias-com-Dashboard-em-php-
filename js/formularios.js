//enviiar os formulários sem atualizar atualzar a pagna(DINAMICAMENTE) 
$(function(){
	

	$('body').on('submit','form',function(){
		var form = $(this);
		//chamar o ajax
		$.ajax({
			//beforeSend é executado antes de envar o form
			beforeSend:function(){
				//mostrar o GIFF quando estver enviando o form
				$('.overlay-loading').fadeIn();
			},
			url:include_path+'ajax/formularios.php',//para onde sera enviada a requisiçao
			method:'post',//tipo de metodo
			dataType: 'json',//tipo de resposta que espera do servidor
			data:form.serialize()//quais informaçoes enviaremos p o formulario
		}).done(function(data){
			if(data.sucesso){
				//Tudo certo vamos melhorar a interface!
				//esconder o GIFF quando for enviando o form
				$('.overlay-loading').fadeOut();
				//mostrar a mensagem de sucesso quando for enviando o form
				$('.sucesso').fadeIn();
				//esconde a mensagem de sucesso depois de 3s
				setTimeout(function(){					
					$('.sucesso').fadeOut();
				},3000)
			}else{
				//Algo deu errado.
				$('.overlay-loading').fadeOut();
			}
		});
		return false;
	})

})