Opcion 1:
# Acceder desde el Hosting 

-  https://horuslegalalliance.es/
	user Admin: admin@restajav.com
	pass: 1234

Opcion 2:
# Acceder en Local, para ello usar estos comandos para crear el virtual host 

- instalar php y extensiones básicas
sudo apt install php php-mysql php-intl php-curl php-xml php-mbstring
- instalar apache
sudo apt install apache2
- configurar php como módulo de apache
sudo apt install libapache2-mod-php
- crear el virtual host en:
sudo nano /etc/apache2/sites-avalaible
- activamos el módulo mod-rewrite de apache para la re-escritura de urls
sudo a2enmod rewrite
- copiamos plantilla de configuración para v-host
sudo cp /etc/apache2/sites-avalaible/000-defeault.conf restaurantly.com.conf
- editamos el v-host con la siguiente configuración:

<VirtualHost *:80>

    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/restaurantly.com/public
    ErrorLog ${APACHE_LOG_DIR}/restaurantly.com_error.log
    CustomLog ${APACHE_LOG_DIR}/restaurantly.com_access.log

    <!-- re-escritura de rutas tipo : /login , en lugar de /index.php/login -->
	<IfModule mod_rewrite.c>
	    RewriteEngine On
	    RewriteBase /
	    RewriteCond %{REQUEST_FILENAME} !-f
	    RewriteCond %{REQUEST_FILENAME} !-d
	    RewriteRule . /index.php [L]
	</IfModule>

</VirtualHost>

- añadir el dominio en el fichero /etc/hosts/
127.0.0.1 restaurantly.com 
- reiniciar apache
sudo service apache2 reload
- instalar Mysql
sudo apt install mysql-server
- configuración del usuario de base de datos

<--- Instrucciones para añadir un usuario de forma segura en mysql  ---->
- importar base de datos desde el comando o hacerlo manualmente /SQL
mysql -u usuario -p “proyecto” < dump.sql


# usar estos comandos cuando comencemos
php bin/console doctrine:schema:update --force
php bin/console assets:install public
composer install
