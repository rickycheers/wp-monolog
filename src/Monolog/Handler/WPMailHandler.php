<?php

namespace Monolog\Handler;

use Monolog\Logger;

class WPMailHandler extends MailHandler {

	function __construct($to, $subject, $from, $level = Logger::ERROR, $bubble = true, $maxColumnWidth = 70){

		parent::__construct($level, $bubble);
		$this->to = $to;
		$this->subject = $subject;
		$this->from = $from;
		$this->maxColumnWidth = $maxColumnWidth;		

	}

	protected function send($content, array $records){
		wp_mail($this->to, $this->subject, $content, "Content-type: text/html");
	}

}
