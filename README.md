
## Tentang DMS

DMS merupakan sebuah sistem yang digunakan untuk mengelola dokumen-dokumen PDF seperti peraturan pemerintah, SOP, dan dokumen PDF lainnya. Sistem ini dirancang dengan menggunakan framework Laravel 8 dengan beberapa requirements sebagai berikut:

- **[PHP 7.4](https://www.php.net/distributions/php-7.4.16.tar.gz)**
- **[OCI 8](https://pecl.php.net/package/oci8)**
    - **[Oracle Instant Client 12.2.0.1.0](https://www.oracle.com/database/technologies/instant-client/downloads.html)**
- **[PDFtoText](https://github.com/spatie/pdf-to-text)**
- **[Laravel 8](https://laravel.com/docs/8.x/releases)**

## CHECK REQUIREMENTS 
> php artisan check:all

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

    > sudo apt install php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath unzip poppler-utils ocrmypdf leptonica-progs libleptonica-dev zlib1g-dev

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

- Pastikan ocrmypdf sudah terinstall
    > which ocrmypdf
    
    - Jika belum install dengan command di bawah ini

    > sudo apt-get install ocrmypdf leptonica-progs libleptonica-dev

    - Sesuaikan alamat path binary ocrmypdf pada variabel BIN_OCRMYPDF dalam file .env
    
- Install JBIG2 for OCRMYPDF
    > git clone https://github.com/agl/jbig2enc

    > cd jbig2enc

    > ./autogen.sh

    > ./configure && make

    > sudo make install

    > cd ..

    > sudo rm -r jbig2enc

- Pastikan masih berada dalam folder root project dan pastikan versi composer nya 1.x bukan 2.x

    > composer -V

    > composer install 

- Setup .env for database and Solr Connection setting, look .env.example as references
- Setup database directly or using laravel database migrations if possible
    > php artisan migrate

- Serving Laravel
    > php artisan serve --host=0.0.0.0

- Configuration of Queue
    > php artisan queue:table

    > php artisan migrate
    
### Centos 7
- **[Centos 7](https://www.cyberciti.biz/faq/install-php-7-x-on-centos-8-for-nginx/)**

- Install PHP 7.4
    - Add EPEL and REMI Repository
    > sudo yum -y install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm

    > sudo yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm

    - Install PHP 7.4 on CentOS 7
    > sudo yum -y install yum-utils

    > sudo yum-config-manager --enable remi-php74

    > sudo yum update
    
    > sudo yum -y install php php-cli

- Install Oracle Client and PHP OCI8
    > sudo yum -y install php php-pecl-mcrypt php-cli php-gd php-curl php-mysqlnd php-ldap php-zip php-fileinfo php-xml php-intl php-mbstring php-opcache php-process systemtap-sdt-devel php-pear php-json php-devel php-common php-bcmath php-pdo php-oci8 libaio
    
    > sudo rpm -ivh docker/centos7/oracle-instantclient12.2-*

    > sudo su

    > echo "export ORACLE_HOME=/usr/lib/oracle/12.2/client64" | tee -a /etc/profile

    > echo "export ORACLE_BASE=/usr/lib/oracle/12.2" | tee -a /etc/profile

    > echo "export LD_LIBRARY_PATH=/usr/lib/oracle/12.2/client64/lib" | tee -a /etc/profile

    > echo "export LD_LIBRARY_PATH64=/usr/lib/oracle/12.2/client64/lib/" | tee -a /etc/profile
    
    > echo "export PATH=\$ORACLE_HOME/bin:\$PATH" | tee -a /etc/profile
    
    > ldconfig

    > PHP_DTRACE=yes pecl install oci8-2.2.0 <<< instantclient,/usr/lib/oracle/12.2/client64/lib

    > wget https://www.php.net/distributions/php-7.4.18.tar.gz

    > tar xvf php-7.4.18.tar.gz

    > cd php-7.4.18/ext/pdo_oci/

    > phpize

    > ./configure --with-pdo-oci=instantclient,/usr/lib/oracle/12.2/client64/lib

    > make 

    > make install



- Install OCRMYPDF
    - Install Python3 with pip3
    > sudo yum install -y python3

    > sudo -H pip3 install --upgrade pip

    > sudo pip3 install wheel pybind11 setuptools-rust  

    > sudo yum -y install curl qpdf 
    
    > sudo yum -y install libstdc++ autoconf automake libtool autoconf-archive pkg-config gcc gcc-c++ make libjpeg-devel libpng-devel libtiff-devel zlib-devel ghostscript pngquant

    - install leptonica https://www.hoangdung.net/2020/01/how-to-install-tesseract-4-on-centos-7.html

    > sudo yum group install -y "Development Tools"

    > wget http://www.leptonica.org/source/leptonica-1.75.3.tar.gz

    > tar -zxvf leptonica-1.75.3.tar.gz

    > rm leptonica-1.75.3.tar.gz

    > cd leptonica-1.75.3

    > ./autobuild

    > ./configure

    > make -j

    > sudo make install

    - check leptonica is installed

    > ls /usr/local/include

    - install tesseract https://www.hoangdung.net/2020/01/how-to-install-tesseract-4-on-centos-7.html

    > wget https://github.com/tesseract-ocr/tesseract/archive/refs/tags/4.1.1.tar.gz

    > tar -zxvf 4.1.1.tar.gz

    > rm 4.1.1.tar.gz
    
    > cd tesseract-4.1.1/

    > ./autogen.sh

    > PKG_CONFIG_PATH=/usr/local/lib/pkgconfig LIBLEPT_HEADERSDIR=/usr/local/include ./configure --with-extra-includes=/usr/local/include --with-extra-libraries=/usr/local/lib
    
    > LDFLAGS="-L/usr/local/lib" CFLAGS="-I/usr/local/include" make -j
    
    > sudo make install

    > sudo ldconfig

    > wget https://github.com/tesseract-ocr/tessdata/raw/master/eng.traineddata

    > sudo mv *.traineddata /usr/local/share/tessdata

    > tesseract --version

    > curl https://sh.rustup.rs -sSf | sh

    > source $HOME/.cargo/env

    > sudo cp $HOME/.cargo/env /etc/profile.d/rustc.sh

    > rustc --version

    > sudo pip3 install pikepdf ocrmypdf

- Install JBIG2 for OCRMYPDF
    > git clone https://github.com/agl/jbig2enc

    > cd jbig2enc

    > ./autogen.sh

    > ./configure && make

    > sudo make install

    > cd ..

    > sudo rm -r jbig2enc


    
### SOLR

- create core
    > docker exec -it docker_solr_1 solr create_core -c dms

- delete core
    > docker exec -it docker_solr_1 solr delete -c dms
