---
to: mytheme/inc/mailer/composer.json
sh: cd <%= cwd %>/mytheme/inc/mailer && if composer -v > /dev/null; then composer install; fi
---
{
  "require": {
    "phpmailer/phpmailer": "^6.5"
  }
}
