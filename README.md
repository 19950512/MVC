# Ativar SSH para o Repositório
##### Abra o terminal #####
<p>rsa -b 4096 -C "your_email@example.com"</p>
<p>sudo subl ~/.ssh/id_rsa.pub</p>
#### Ativar o SSH no GitHub #####

# Necessário para o funcionamento do projeto
## PHP 7.4
<p>sudo apt-get update</p>
<p>sudo add-apt-repository ppa:ondrej/php</p>
<p>sudo apt-get install -y php7.4</p>
<p>sudo apt-get install php7.4-pgsql</p>

##### Ativar o display_erros do PHP #####
<p>cd /etc/php/7.4/apache2/</p>
<p>sudo subl php.ini</p>

##### Procurar por "display_errors = Off" alterar para "display_errors = On" #####

<p>sudo service apache2 restart</p>

## Apache2
##### Ativar a sobre escrita (.htaccess) #####
<p>sudo a2enmod rewrite</p>
#### Ativar a escrita de Headers ####
<p>sudo a2enmod headers</p>
#### Ativar o Cache ####
<p>sudo a2enmod expires</p>
##### Ativar o SSL #####
<p>sudo a2enmod ssl</p>
##### Desativar o PHP atual #####
<p>sudo a2dismod php7.x</p>
##### Ativar o PHP 7.4 #####
<p>sudo a2enmod php7.4</p>

## Projeto
##### Permissão para o usuário do apache registrar as sessions no diretório do projeto #####
<p>sudo chown www-data:www-data MVC/Sessions/</p>
