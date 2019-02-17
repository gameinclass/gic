#!/usr/bin/env bash

: '
    Atenção! Mude o valor das variáveis abaixo com informações do projeto.
'
PROJECT="GiC"
DOMAIN="gic.unscode.com"
GIT_BRANCH="master"
GIT_REMOTE_SSH="git@gitlab.com:unscode/gic.git"
SLACK_WEBHOOK="https://hooks.slack.com/services/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"

# Remove o diretório root do projeto.
if [ -d /var/www/$DOMAIN ]; then
  sudo rm /var/www/$DOMAIN -R
fi

# CONFIGURAÇÕES DE SUBDOMÍNIO NO APACHE
sudo echo "
<VirtualHost *:80>
    ServerAdmin jorgerodrigues9@outlook.com
    ServerName ${DOMAIN}
    ServerAlias www.${DOMAIN}
    DocumentRoot /var/www/${DOMAIN}/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    <Directory /var/www/${DOMAIN}/public>
        Allowoverride All
     </Directory>
</VirtualHost>" | sudo tee /etc/apache2/sites-available/$DOMAIN.conf
sudo a2ensite $DOMAIN && sudo /etc/init.d/apache2 restart
cd /var/www/ && sudo mkdir $DOMAIN && sudo chown $USER:$USER $DOMAIN -R
# Clona o repositório
git clone -b $GIT_BRANCH $GIT_REMOTE_SSH $DOMAIN && cd $DOMAIN
# Cria o arquivo de banco de dados sqlite
touch database/database.sqlite
# Instala as dependências da aplicação
composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
# Adiciona o arquivo de variáveis de ambiente do framework e gera a chave da aplicação.
cp .env.testing .env && php artisan key:generate
# Popula banco de dados, configura o Passport e armazenamento
php artisan migrate --seed && php artisan passport:install && php artisan storage:link
sudo chown www-data:www-data . -R
curl -X POST -H 'Content-type: application/json' $SLACK_WEBHOOK --data @message.json
exit