<?php include('config.php'); ?>
<?php Site::updateUsuarioOnline(); ?>
<?php Site::contador(); ?>

<?php

	$infoSite = MySql::conectar()->prepare("SELECT * FROM `tb_site.config` ");
	$infoSite->execute();
	$infoSite = $infoSite->fetch();

?>

<!DOCTYPE html>
<html>
	<head>
							<!-- PEGAR O TÍTULO DO BD -->
		<title> <?php echo $infoSite['titulo']; ?> </title>
							<!-- FONTEAWESOME -->
		<link rel="stylesheet" href="<?php echo INCLUDE_PATH; ?>estilo/all.css">
							<!-- INCLUR A OPEN SANS -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
							<!-- css -->
		<link rel="stylesheet" type="text/css" href="<?php echo INCLUDE_PATH; ?>estilo/style.css">
						<!-- meta para design responsivo -->
		<!-- width=device-width -> quer dizer que a largura e com base no design responsivo -->
						<!-- initial-scale=1.0 -> sempre 1.0 -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- metas para SEO -->
		<meta name="keywords" content="palavras-chave,do,meu,site" >
		<meta name="description" content="descrição do meu web site">
		<!-- icone do site -->
		<link rel="icon" type="image/x-icon" href="<?php echo INCLUDE_PATH; ?>favicon.ico">
		<meta charset="utf-8">
	</head>
	<body>

		

	<base base="<?php echo INCLUDE_PATH; ?>" />
	<?php
		// SE EXISTIR O GET[URL]
		$url = isset($_GET['url']) ? $_GET['url'] : 'home';
		//CASO A URL FOR depoimentos ou servicos será inserido uma tag target(alvo)
		switch ($url) {
			case 'depoimentos':
				echo '<target target="depoimentos" />';
				break;

			case 'servicos':
				echo '<target target="servicos" />';
				break;
		}
	?>

		<div class="sucesso"> Formulário Enviado com Sucesso!</div><!-- sucesso -->
		<div class="overlay-loading">
			<img src="<?php echo INCLUDE_PATH; ?>img/ajax-loader.gif">
		</div><!-- overlay-loading -->
		

		<header>
			<div class="center">
				<div class="logo left"> <a href="/">LogoMarca</a> </div><!-- LOGO -->
				<nav class="desktop right">
					<ul>
						<li><a href="<?php echo INCLUDE_PATH; ?>">Home</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>depoimentos">Depoimentos</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>servicos">Serviços</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>noticias">Notícias</a></li>
						<li><a realtime="contato" href="<?php echo INCLUDE_PATH; ?>contato">Contato</a></li>
					</ul>
				</nav><!-- Desktop -->

				<nav class="mobile right">
					<div class="botao-menu-mobile">
						<i class="fas fa-bars"></i>
					</div><!-- botao-menu-mobile -->
					<ul>
						<li><a href="<?php echo INCLUDE_PATH; ?>">Home</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>depoimentos">Depoimentos</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>servicos">Serviços</a></li>
						<li><a href="<?php echo INCLUDE_PATH; ?>noticias">Notícias</a></li>
						<li><a realtime="contato" href="<?php echo INCLUDE_PATH; ?>contato">Contato</a></li>
					</ul>
				</nav><!-- mobile -->
				<div class="clear"></div><!-- clear -->
			</div><!-- center -->
		</header>

		<div class="container-principal">
			<?php
				//se existe o arquivo com a url na pasta page, redirecionara para ela
				if(file_exists('pages/'.$url.'.php')){
					include('pages/'.$url.'.php');

				//se n existe o arquivo com a url na pasta page
				}else{
					//se a url n existir n pasta page e for diferente de depoimentos ou servicos
					if($url != 'depoimentos' && $url != 'servicos'){
						$urlPar = explode('/',$url)[0];//pega a url
						if($urlPar != 'noticias'){//se a url for diferente de noticias,redirecionara para pagina de erro
							$pagina404 = true;
							include('pages/404.php');
						}else{//se a url for noticias,redirecionara para pagina de noticias
							include('pages/noticias.php');
						}
					}else{//se a url n existir n pasta page e se a url for de depoimentos ou servicos,redirecionara para pagina home
						include('pages/home.php');
					}
				}

			?>
		</div><!-- container-principal -->

		<!-- se a pagina404 existe e se ela está selecionada, o footer terá uma class chamada fixed que será utilizada para o footer 
		ficar fixo no fim da página-->
		<footer <?php if(isset($pagina404) && $pagina404 == true) echo 'class="fixed"'; ?>>
			<div class="center">
				<p>Todos os direitos reservados</p>
			</div><!--center-->
		</footer>

		<script src="<?php echo INCLUDE_PATH; ?>js/jquery.js"></script>
		<script src="<?php echo INCLUDE_PATH; ?>js/constants.js"></script>
		<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDHPNQxozOzQSZ-djvWGOBUsHkBUoT_qH4'></script>
		
		<script src="<?php echo INCLUDE_PATH; ?>js/scripts.js"></script>
		
		<script src="<?php echo INCLUDE_PATH; ?>js/slider.js"></script>
		

		<?php
		//função para caso a url for noticias, redirecionar para a pagina noticias

		//lembrando... nós transformamos a url[noticias] em array na noticias.php
		//se a url for um array e o indice 0 for noticias
		//strstr()->busca(verifica se existe) uma string em uma variavel

		if(is_array($url) && strstr($url[0],'noticias') !== false){
		?>
		<script>
			$(function(){
				//quando select for trocado
				$('select').change(function(){
					//redireciona para a pagina do valor da noticia selecionado na box 
					location.href=include_path+"noticias/"+$(this).val();
				})
			})
		</script>
		<?php
		}
		?>

		<?php
			if($url == 'contato'){
		?>
	<?php } ?>

		<script src="<?php echo INCLUDE_PATH; ?>js/formularios.js"></script>

	</body>
</html>