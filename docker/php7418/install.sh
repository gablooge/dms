#!/bin/bash

tar xvf /opt/php74/php-7.4.18.tar.gz -C /opt/php74/

cd /opt/php74/php-7.4.18/ext/pdo_oci/
phpize
./configure --with-pdo-oci=instantclient,/usr/lib/oracle/12.2/client64/lib
make
make install


