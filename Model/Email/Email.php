<?php

namespace Model\Email;

use Model\Email\PHPMailer;
use Model\Email\SMTP;

class Email {

	public function enviar(){
		return ['r' => 'ok', 'data' => 'Menssagem enviada com sucesso.'];	
	}
}