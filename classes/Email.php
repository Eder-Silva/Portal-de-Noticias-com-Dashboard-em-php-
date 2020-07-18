<?php
	
	class Email
	{
		
		private $mailer;

		public function __construct($host,$username,$senha,$name)
		{
			
			$this->mailer = new PHPMailer;//instanciando a classe

			$this->mailer->isSMTP(); // Definir o remetente para usar SMTP
			$this->mailer->Host = $host;// Especificar servidores SMTP principais e de backup
			$this->mailer->SMTPAuth = true;// Ativar autenticação SMTP
			$this->mailer->Username = $username;//Nome de usuário SMTP
			$this->mailer->Password = $senha;// SMTP password
			$this->mailer->SMTPSecure = 'ssl';// Ative a criptografia TLS, o `ssl` também é aceito
			$this->mailer->Port = 465;//Porta TCP à qual se conectar

			$this->mailer->setFrom($username,$name);//mesmo q Nome de usuário SMTP (DE)
			$this->mailer->isHTML(true);// Definir formato de email para HTML
			$this->mailer->CharSet = 'UTF-8';

		}

		public function addAdress($email,$nome){
			$this->mailer->addAddress($email,$nome);//para quem sera enviado o email (PARA)
		}

		public function formatarEmail($info){
			$this->mailer->Subject = $info['assunto'];//assunto
			$this->mailer->Body    = $info['corpo'];//corpo
			$this->mailer->AltBody = strip_tags($info['corpo']);//se o Navegador nao tiver suporte p html
			//a função strip_tags() serve para retirar todas as tags html existentes 
		}

		public function enviarEmail(){
			if($this->mailer->send()){//se o email foi enviado
				return true;
			}else{
				return false;
			}
		}

	}
?>