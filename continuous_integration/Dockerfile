FROM ubuntu:latest

MAINTAINER Jorge Rodrigues <jorgerodrigues9@outlook.com>

ENV DEBIAN_FRONTEND=noninteractive
# ESTÁGIO DE CONFIGURAÇÃO DO AMBIENTE
# Atualiza os repositórios e pacotes do sistema operacional
RUN apt update -y && apt upgrade -y &&\
    # Instala alguns pacotes necessário para o funcionamento dos comandos abaixo
    apt install curl zip unzip -y &&\
    # Instala o cliente SSH, importante para os estágio de deploy
    apt install openssh-client -y &&\
    # Instalação e configuração do Apache 2
    apt install apache2 -y && a2enmod rewrite &&\
    # Instalação e configuação do PHP 7.2
    apt install php7.2 php7.2-mysql php7.2-xml php7.2-mbstring php7.2-curl php7.2-json php7.2-gd php7.2-sqlite3 php7.2-zip php7.2-xdebug -y &&\
    # Instalação e configuração do Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer &&\
    # Remove os desnecessários
    apt autoremove -y

# Copia as configurações necessários para o container
COPY etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY etc/apache2/apache2.conf /etc/apache2/apache2.conf
# Diretório de trabalho
WORKDIR /var/www
# Importante! Inicia o Apache
CMD ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
# Expõe a porta sempre que um novo container é criado.
EXPOSE 80

# Remove todas as imagens -> docker rmi $(docker images -q) -f
# Remove todos os processos -> docker rm $(docker ps -qa) -f
# Remove todos os volumes -> docker volume rm $(docker volume ls -q) -f