release: php bin/console cache:clear && php bin/console cache:warmup && php bin/console d:mi:mi && php bin/console lexik:jwt:generate-keypair --skip-if-exists

web: heroku-php-apache2 public/