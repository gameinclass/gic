#!/usr/bin/env bash

: '
    Este arquivo contém a sequência de comandos para configurar o ambiente da aplicação. Todas as configurações
    do SO, Servidor, Banco e Aplicação deve ser adicionado aqui para colocar a aplicação em funcionamento. Este
    arquivo será executado no servidor a partir de uma conexão SSH.

    Atenção! Mude o valor das variáveis abaixo com informações do projeto.
'

PROJECT="GiC"
DOMAIN="gic.unscode.com"
GIT_BRANCH="master"
GIT_REMOTE_SSH="git@gitlab.com:unscode/gic.git"
SLACK_WEBHOOK="https://hooks.slack.com/services/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"
MESSAGE="
{
    \"attachments\": [
        {
            \"pretext\": \"Olá, tem algo novo do projeto *$PROJECT*\",
            \"color\": \"#36a64f\",
            \"title\": \"http://$DOMAIN\",
            \"title_link\": \"http://$DOMAIN\",
            \"text\": \"Acesse o link acima para verificar e validar as alterações\",
        }
    ]
}
"

# Verifica se o diretório do projeto existe no diretório padrão do Apache
# Atenção, as configurações de subdomínio devem estar configurado.
if [ -d /var/www/$DOMAIN ]; then
    # Muda para o diretório da aplicação e troca de proprietário para o usuário atual.
    cd /var/www/$DOMAIN && sudo chown $USER:$USER . -R
    # Fetch all remotes e reseta o diretório local com o remoto.
    git fetch --all && git reset --hard origin/$GIT_BRANCH
    # Remove o diretório de dependêcia do framework e instala novamente.
    if [ -d vendor ]; then sudo rm vendor -R; fi
    composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
    # Sobrescreve o arquivo de variáveis de ambiente do framework e gera a chave da aplicação.
    cp .env.testing .env && php artisan key:generate
    # Faz as configurações do banco de dados e armazenamento.
    if [ -f database/database.sqlite ]; then
        sudo rm database/database.sqlite
    fi
    touch database/database.sqlite && php artisan migrate --seed && php artisan storage:link
    # Muda o proprietário dos arquivos e diretórios.
    sudo chown www-data:www-data . -R
    curl -X POST -H 'Content-type: application/json' --data "$MESSAGE" $SLACK_WEBHOOK
    exit
fi

# Se o diretório não existe ...
if [ ! -d /var/www/$DOMAIN ]; then
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
    sudo a2ensite $DOMAIN
    sudo /etc/init.d/apache2 restart
    # Vai para o diretório padrão do servidor Apache, cria um diretória para o site, e muda as permissões de usuário.
    # Importante, o nome do diretório deve ser o domnínio sem o 'www'
    cd /var/www/ && sudo mkdir $DOMAIN && sudo chown $USER:$USER $DOMAIN -R
    git clone -b $GIT_BRANCH $GIT_REMOTE_SSH $DOMAIN && cd $DOMAIN
    # Instala as dependências da aplicação
    composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
    # Adiciona o arquivo de variáveis de ambiente do framework e gera a chave da aplicação.
    cp .env.testing .env && php artisan key:generate
    # Configura o banco de dados e armazenamento
    touch database/database.sqlite && php artisan migrate --seed && php artisan storage:link
    sudo chown www-data:www-data . -R
    curl -X POST -H 'Content-type: application/json' --data "$MESSAGE" $SLACK_WEBHOOK
    exit
fi
