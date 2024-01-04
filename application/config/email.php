<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'pwtramanuj@gmail.com',
			'smtp_pass' => 'scetrxsjcipsustu',
			'smtp_crypto' => 'ssl', // Use 'ssl' or 'tls' for Gmail
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		);
		
/* $config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'pwtramanuj@gmail.com',
			'smtp_pass' => 'scetrxsjcipsustu',
			'smtp_crypto' => 'ssl', // Use 'ssl' or 'tls' for Gmail
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		); */

		//$this->email->initialize($config);