---
to: mytheme/inc/mailer/mail/admin.php
---
============================================
このメッセージは
<?php echo $site_name; ?> WEBサイトより自動的に送信されています。
============================================

<?php foreach ($body as $key => $value) {
	$label = htmlspecialchars($value["label"]);
	$data = htmlspecialchars($value["value"]);

	echo <<<EOM

■ {$label}
{$data}

EOM;
} ?>

-------------------------------------------------------

date : <?php echo date("Y/m/d H:i:s") . PHP_EOL; ?>
ip : <?php echo $_SERVER["REMOTE_ADDR"] . PHP_EOL; ?>
host : <?php echo gethostbyaddr(
	$_SERVER["REMOTE_ADDR"]
); ?>:<?php echo $_SERVER["REMOTE_PORT"] . PHP_EOL; ?>
ua : <?php echo $_SERVER["HTTP_USER_AGENT"] . PHP_EOL; ?>

-------------------------------------------------------
