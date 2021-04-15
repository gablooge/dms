
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

- Install PHP 7.4
    > sudo apt update && sudo apt upgrade

    > 

### Centos 7
- **[Centos 7](https://www.cyberciti.biz/faq/install-php-7-x-on-centos-8-for-nginx/)**



