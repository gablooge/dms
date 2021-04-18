
## Tentang DMS

DMS merupakan sebuah sistem yang digunakan untuk mengelola dokumen-dokumen PDF seperti peraturan pemerintah, SOP, dan dokumen PDF lainnya. Sistem ini dirancang dengan menggunakan framework Laravel 8 dengan beberapa requirements sebagai berikut:

- **[PHP 7.4](https://www.php.net/distributions/php-7.4.16.tar.gz)**
- **[OCI 8](https://pecl.php.net/package/oci8)**
    - **[Oracle Instant Client 12.2.0.1.0](https://www.oracle.com/database/technologies/instant-client/downloads.html)**
- **[PDFtoText](https://github.com/spatie/pdf-to-text)**
- **[Laravel 8](https://laravel.com/docs/8.x/releases)**

## Cara Instalasi
### Windows dengan WSL2 Ubuntu 20.04
- Check [this reference for installing wsl2 ubuntu 20.04 on windows 10 https://www.omgubuntu.co.uk/how-to-install-wsl2-on-windows-10](https://www.omgubuntu.co.uk/how-to-install-wsl2-on-windows-10).

- Check ubuntu state is running as default wsl with version 2

    > wsl -l -v

- Go to the project root directory and remote inside to the ubuntu

    > cd dms-bapenda

    > wsl

- Chmod / Chown WSL Improvements 

    > cd ~/

    > sudo umount /mnt/c 

    > sudo mount -t drvfs C: /mnt/c -o metadata

    > exit

    > wsl

- Install PHP 7.4
    > sudo apt update && sudo apt upgrade

    > sudo apt install php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath unzip poppler-utils

    > php -v

- Install Oracle Client
    > sudo mkdir /opt/oracle 

    > cd /opt/oracle

    > sudo wget https://raw.githubusercontent.com/pwnlabs/oracle-instantclient/master/instantclient-basic-linux.x64-12.2.0.1.0.zip

    > sudo wget https://raw.githubusercontent.com/pwnlabs/oracle-instantclient/master/instantclient-sdk-linux.x64-12.2.0.1.0.zip

    > sudo unzip instantclient-basic-linux.x64-12.2.0.1.0.zip

    > sudo unzip instantclient-sdk-linux.x64-12.2.0.1.0.zip

    > sudo rm instantclient-basic-linux.x64-12.2.0.1.0.zip

    > sudo rm instantclient-sdk-linux.x64-12.2.0.1.0.zip

    > sudo mv instantclient_12_2 /opt/oracle/instantclient

    > sudo chown -R root:www-data /opt/oracle/instantclient

    > sudo apt-get install php7.4-dev php-pear build-essential libaio1 composer

    > sudo ln -s /opt/oracle/instantclient/libclntsh.so.12.1 /opt/oracle/instantclient/libclntsh.so

    > sudo ln -s /opt/oracle/instantclient/libocci.so.12.1 /opt/oracle/instantclient/libocci.so

    > sudo su

    > echo /opt/oracle/instantclient > /etc/ld.so.conf.d/oracle-instantclient.conf

    > ldconfig

    > pecl install oci8-2.2.0

    - **INI PENTING!** Saat ditanyakan konfigurasi oracle, isikan **instantclient,/opt/oracle/instantclient**

    > echo " extension = oci8.so " >> /etc/php/7.4/cli/php.ini

    > echo " extension = oci8.so " >> /etc/php/7.4/apache2/php.ini

    - For Apache2

    > echo "LD_LIBRARY_PATH=\"/opt/oracle/instantclient\"" >> /etc/environment

    > echo "ORACLE_HOME=\"/opt/oracle/instantclient\"" >> /etc/environment

    > echo "LD_LIBRARY_PATH=\"/opt/oracle/instantclient\"" >> /etc/apache2/envvars

    > echo "ORACLE_HOME=\"/opt/oracle/instantclient\"" >> /etc/apache2/envvars

    > service apache2 restart

    - for testing phpinfo with apache2

    > echo "<?php phpinfo(); ?>" >> /var/www/html/info.php

    > exit

    > sensible-browser http://localhost/info.php

- Pastikan pdf-to-text sudah terinstall

    > which pdftotext

    - Jika belum install dengan command di bawah ini

    > sudo apt-get install poppler-utils

- Pastikan masih berada dalam folder root project dan pastikan versi composer nya 1.x bukan 2.x

    > composer -V

    > composer install 

- Setup .env for database and Solr Connection setting, look .env.example as references
- Setup database directly or using laravel database migrations if possible
    > php artisan migrate

- Serving Laravel
    > php artisan serve --host=0.0.0.0


### Centos 7
- **[Centos 7](https://www.cyberciti.biz/faq/install-php-7-x-on-centos-8-for-nginx/)**



