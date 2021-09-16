---
to: mytheme/inc/mailer/Mailer.php
---
<?php

namespace Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require "vendor/autoload.php";

class Mailer
{
	private $props;
	public $mail;
	public static $DEBUG = true;
	public static $USE_LOG = false;

	public function __construct($props)
	{
		$this->props = $props;
		$this->mail = new PHPMailer(true);
		$this->mail->CharSet = "UTF-8";
		$this->mail->Encoding = "base64";

		//		$this->mail->isSMTP();
		//		$this->mail->Host = "mail.example.com";
		//		$this->mail->SMTPAuth = true;
		//		$this->mail->Username = "user@example.com";
		//		$this->mail->Password = "password";
		//		$this->mail->SMTPSecure = "tls";
		//		$this->mail->Port = 465;
	}

	public function setupUser($subject, $path = MAILER_PATH . "/mail/user.php")
	{
		$this->mail->clearAddresses();
		$this->mail->addAddress($this->props["user"]);
		$body = $this->_evaluate($path, $this->props);
		$this->_setContent($subject, $body);
	}

	public function setupAdmin($subject)
	{
		$this->mail->clearAddresses();

		if (self::$DEBUG) {
			$this->mail->addAddress($this->props["user"]);
		} else {
			foreach ($this->props["admins"] as $admin) {
				$this->mail->addAddress($admin);
			}
		}

		$body = $this->_evaluate(MAILER_PATH . "/mail/admin.php", $this->props);
		$this->_setContent($subject, $body);

		if (!self::$DEBUG && self::$USE_LOG) {
			$this->writeMailLog($body);
		}
	}

	private function _setContent($subject, $body)
	{
		$this->mail->Subject = $subject;
		$this->mail->Body = $body;
	}

	private function _evaluate($viewFile, $dataForView)
	{
		extract($dataForView);
		ob_start();
		include $viewFile;
		return ob_get_clean();
	}

	private function writeMailLog($message)
	{
		$path = MAILER_PATH . "logs";
		$this->checkDir($path);
		$cnt = 0;
		$file_name = date("YmdHis");
		if (file_exists($path . $file_name . sprintf("%03d", $cnt) . ".txt")) {
			while (true) {
				$cnt++;
				if (
					!file_exists(
						$path . DS . $file_name . sprintf("%03d", $cnt) . ".txt"
					)
				) {
					break;
				}
			}
		}
		$file_name = $file_name . sprintf("%03d", $cnt) . ".txt";
		file_put_contents(
			$path . DS . $file_name,
			$message,
			FILE_APPEND | LOCK_EX
		);
	}

	private function checkDir($dir)
	{
		if (!file_exists($dir) || !is_dir($dir)) {
			mkdir($dir, 0777, true);
			chmod($dir, 0777);
		}
	}
}
