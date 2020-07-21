<?php   
	
	class Painel
	{
		
		public static $cargos = [
		'0' => 'Normal', 
		'1' => 'Sub Administrador',
		'2' => 'Administrador'];

		public static function generateSlug($str){
			//converter para minúscula
			$str = mb_strtolower($str);
			//para pegar qualquer um desses caracteres e substituir 
			//igual ao str_replace, porém dá para utilizar vários carecteres 
			$str = preg_replace('/(â|á|ã)/', 'a', $str);
			$str = preg_replace('/(ê|é)/', 'e', $str);
			$str = preg_replace('/(í|Í)/', 'i', $str);
			$str = preg_replace('/(ú)/', 'u', $str);
			$str = preg_replace('/(ó|ô|õ|Ô)/', 'o',$str);
			$str = preg_replace('/(_|\/|!|\?|#)/', '',$str);
			$str = preg_replace('/( )/', '-',$str);
			$str = preg_replace('/ç/','c',$str);
			$str = preg_replace('/(-[-]{1,})/','-',$str);
			$str = preg_replace('/(,)/','-',$str);
			$str=strtolower($str);
			return $str;
		}
		
		public static function logado(){
			return isset($_SESSION['login']) ? true : false;
		}

		public static function loggout(){
			setcookie('lembrar','true',time()-1,'/');//deletar o cookie de lembrar-me
			session_destroy();//destrir a sessao
			header('Location: '.INCLUDE_PATH_PAINEL);//redirecionar para index, q verifica se esta logado
		}

		public static function carregarPagina(){
			if(isset($_GET['url'])){//se existe a url
				//explode() converte uma string em um array, quebrando os elementos por meio de um separador
				$url = explode('/',$_GET['url']);
				if(file_exists('pages/'.$url[0].'.php')){ // se a pagina existir
					include('pages/'.$url[0].'.php');//ra para a pagina indicada
				}else{//caso a pagina n exista
					header('Location: '.INCLUDE_PATH_PAINEL);//redirecionar para index, q verifica se esta logado
				}
			}else{//caso contrário vai para a pagina home
				include('pages/home.php');
			}
		}

		public static function listarUsuariosOnline(){
			self::limparUsuariosOnline();
			//SELECIONE TUDO DE tb_admin.online 
			$sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.online`");
			$sql->execute();
			return $sql->fetchAll();
		}

		//PARA LIMPAR OS USUARIOS QUE ESTAO INATIVOS POR UM MINUTO
		public static function limparUsuariosOnline(){
			$date = date('Y-m-d H:i:s');//PEGAR A DATA E HORA ATUAL DO SISTEMA
			//DELETAR DA tb_admin.online ONDE ultima_acao FOR MENOR QUE '$date' - INTERVAL 1 MINUTE
			$sql = MySql::conectar()->exec("DELETE FROM `tb_admin.online` WHERE ultima_acao < '$date' - INTERVAL 1 MINUTE");
		}

		//PARA MOSTRAR SUCESSO OU ERRO
		public static function alert($tipo,$mensagem){
			if($tipo == 'sucesso'){
				echo '<div class="box-alert sucesso"><i class="fa fa-check"></i> '.$mensagem.'</div>';
			}else if($tipo == 'erro'){
				echo '<div class="box-alert erro"><i class="fa fa-times"></i> '.$mensagem.'</div>';
			}
		}

		public static function imagemValida($imagem){
			//SE O TIPO DA IMAGEM FOR JPG OU JPG OU PNG 
			if($imagem['type'] == 'image/jpeg' ||
				$imagem['type'] == 'imagem/jpg' ||
				$imagem['type'] == 'imagem/png'){

				//VERIFICAR SE O TAMANHO DA IMAGEM E VALIDA
				//CONVERTER BYTES PARA KBYTES E ARREDONDAR PARA INTEIRO
				$tamanho = intval($imagem['size']/1024);
				//SE O TAMANHO FOR MENOR QUE 300KB
				if($tamanho < 300)
					return true;
				else
					return false;
			}else{
				return false;
			}
		}

		public static function uploadFile($file){
			//PEGAR O FORMATO DO ARQUIVO
			//explode()-> FICA ASSIM: $formatoArquivo[0]=EDER, $formatoArquivo[1]=jpg
			$formatoArquivo = explode('.',$file['name']);
			//uniqid() -> GERAR UM ID UINICO PARA SER O NOME DA IMAGEM
			$imagemNome = uniqid().'.'.$formatoArquivo[count($formatoArquivo) - 1];

			//A função move_uploaded_file () move um arquivo carregado para um novo destino.
			//tmp_name O nome temporário com o qual o arquivo enviado foi armazenado no servidor.
			//BASE_DIR_PAINEL.'/uploads/'.$imagemNome É PARA ONDE SERÁ ENVIADA A IMAGEM
			if(move_uploaded_file($file['tmp_name'],BASE_DIR_PAINEL.'/uploads/'.$imagemNome))
				return $imagemNome;
			else
				return false;
		}

		public static function deleteFile($file){
			//UNLINK()->APAGA UM ARQUIVO PASSADO COMO PARAMETRO
			//@-> PARA N MOSTRAR ALGUM TIPO DE AVISO
			@unlink('uploads/'.$file);
		}

		public static function insert($arr){
			$certo = true;
			$nome_tabela = $arr['nome_tabela'];//PEGA O VALOR DO INPUT TYPE HIDDEN
			$query = "INSERT INTO `$nome_tabela` VALUES (null";//COMECA A QUERY
			foreach ($arr as $key => $value) {
				$nome = $key;
				$valor = $value;

				//SE O CAMPO ENVIADO FOR ACAO OU NOME_TABELA,IRA PULAR
				if($nome == 'acao' || $nome == 'nome_tabela')
					continue;
				//SE HOUVER CAMPOS VAZIOS
				if($value == ''){
					$certo = false;
					break;
				}
				$query.=",?";//INSERE A VIRGULA E ? AO FINAL DE CADA ITERAÇÃO
				$parametros[] = $value;
			}

			$query.=")";//TERMINA A QUERY

			//SE ESTIVER TUDO CORRETO, NENHUM CAMPO VAZIO
			if($certo == true){
				//VAI INSERIR O NOVO USUARIO NO BD
				$sql = MySql::conectar()->prepare($query);
				$sql->execute($parametros);
				//
				$lastId = MySql::conectar()->lastInsertId();
				$sql = MySql::conectar()->prepare("UPDATE `$nome_tabela` SET order_id = ? WHERE id = $lastId");
				$sql->execute(array($lastId));
			}
			return $certo;
		}

		public static function update($arr,$single = false){
			$certo = true;
			$first = false;
			$nome_tabela = $arr['nome_tabela'];

			$query = "UPDATE `$nome_tabela` SET ";
			foreach ($arr as $key => $value) {
				$nome = $key;
				$valor = $value;
				if($nome == 'acao' || $nome == 'nome_tabela' || $nome == 'id')
					continue;
				if($value == ''){
					$certo = false;
					break;
				}
				
				if($first == false){
					$first = true;
					$query.="$nome=?";
				}
				else{
					$query.=",$nome=?";
				}

				$parametros[] = $value;
			}

			if($certo == true){
				if($single == false){
					$parametros[] = $arr['id'];
					$sql = MySql::conectar()->prepare($query.' WHERE id=?');
					$sql->execute($parametros);
				}else{
					$sql = MySql::conectar()->prepare($query);
					$sql->execute($parametros);
				}
			}
			return $certo;
		}

		//$tabela->NOME DA TABELA, $start-> POSICAO INICIAL, $end->FINAL
		public static function selectAll($tabela,$start = null,$end = null){
			//SE START E ENDE FOREM NULOS, ESTAMOS SELECIONANDO TUDO
			if($start == null && $end == null)
				$sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC");
			//SE START E ENDE NÃO FOREM NULOS,N ESTAMOS SELECIONANDO TUDO
			else
				$sql = MySql::conectar()->prepare("SELECT * FROM `$tabela` ORDER BY order_id ASC LIMIT $start,$end");
	
			$sql->execute();
			return $sql->fetchAll();

		}

		public static function deletar($tabela,$id=false){
			//SE NAO EXISTIR O ID DO DEPOIMENTO, DELETARA A TABELA INTEIRA
			if($id == false){
				$sql = MySql::conectar()->prepare("DELETE FROM `$tabela`");
			}else{//SE EXISTIR O ID DO DEPOIMENTO, DELETARA O DEPOIMENTO
				$sql = MySql::conectar()->prepare("DELETE FROM `$tabela` WHERE id = $id");
			}
			$sql->execute();
		}

		//REDIRECIONAR A PAGINA 
		public static function redirect($url){
			echo '<script>location.href="'.$url.'"</script>';
			die();
		}

		
		//Metodo especifico para selecionar apenas 1 registro.		
		public static function select($table,$query = '',$arr = ''){
			if($query != false){
				$sql = MySql::conectar()->prepare("SELECT * FROM `$table` WHERE $query");
				$sql->execute($arr);
			}else{
				$sql = MySql::conectar()->prepare("SELECT * FROM `$table`");
				$sql->execute();
			}
			return $sql->fetch();
		}

		public static function orderItem($tabela,$orderType,$idItem){			
			//SE O TIPO DE ORDENAR FOR PARA CIMA
			if($orderType == 'up'){
				//ITEM ATUAL 
				$infoItemAtual = Painel::select($tabela,'id=?',array($idItem));
				$order_id = $infoItemAtual['order_id'];
				//ITEM ANTERIOR
				$itemBefore = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE order_id < $order_id ORDER BY order_id DESC LIMIT 1");
				$itemBefore->execute();
				//SE N HOUVER UM ITEM ANTERIOR
				if($itemBefore->rowCount() == 0)
					return;
				$itemBefore = $itemBefore->fetch();
				//PARA ATUALIZAR AS POSIÇÕES NO BD
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemBefore['id'],'order_id'=>$infoItemAtual['order_id']));
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoItemAtual['id'],'order_id'=>$itemBefore['order_id']));

			}else if($orderType == 'down'){//SE O TIPO DE ORDENAR FOR PARA BAIXO
				//ITEM ATUAL 
				$infoItemAtual = Painel::select($tabela,'id=?',array($idItem));
				$order_id = $infoItemAtual['order_id'];
				//ITEM POSTERIOR
				$itemAfter = MySql::conectar()->prepare("SELECT * FROM `$tabela` WHERE order_id > $order_id ORDER BY order_id ASC LIMIT 1");
				$itemAfter->execute();
				//SE N HOUVER UM ITEM ANTERIOR
				if($itemAfter->rowCount() == 0)
					return;
				$itemAfter = $itemAfter->fetch();
				//PARA ATUALIZAR AS POSIÇÕES NO BD
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$itemAfter['id'],'order_id'=>$infoItemAtual['order_id']));
				Painel::update(array('nome_tabela'=>$tabela,'id'=>$infoItemAtual['id'],'order_id'=>$itemAfter['order_id']));
			}
		}
		
	}

?>