---
to: mytheme/inc/mailer/mail/user.php
---
以下の内容でお問い合わせを受け付けました。
改めて、担当よりご連絡をさせていただきます。

なお、営業時間は平日10時〜19時となっております。
時間外のお問い合わせは翌営業日にご連絡差し上げます。

ご理解・ご了承の程よろしくお願い致します。

**********************************
<?php foreach ($body as $key => $value) {
  $label = $value["label"];
  $data = $value["value"];

  echo <<<EOM
■ {$label}
{$data}
EOM;
} ?>
