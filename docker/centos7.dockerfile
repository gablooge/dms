FROM centos:7

RUN yum -y update

RUN mkdir -p /opt/php74/

# Add EPEL and REMI Repository
COPY docker/centos7/* /opt/php74/
RUN yum -y install /opt/php74/epel-release-latest-7.noarch.rpm
RUN yum -y install /opt/php74/remi-release-7.rpm

# Install PHP 7.4 on CentOS 7
RUN yum -y install yum-utils
RUN yum-config-manager --enable remi-php74

RUN yum -y update

RUN yum history sync

RUN yum -y install php php-cli

RUN yum -y install php php-pecl-mcrypt php-cli php-gd php-curl php-mysqlnd php-ldap php-zip php-fileinfo php-xml php-intl php-mbstring php-opcache php-process systemtap-sdt-devel php-pear php-json php-devel php-common php-bcmath php-pdo php-oci8 libaio which

# Install Oracle Client and PHP OCI
RUN rpm -ivh /opt/php74/oracle-instantclient12.2-*

RUN echo "export ORACLE_HOME=/usr/lib/oracle/12.2/client64" | tee -a /etc/profile
RUN echo "export ORACLE_BASE=/usr/lib/oracle/12.2" | tee -a /etc/profile
RUN echo "export LD_LIBRARY_PATH=/usr/lib/oracle/12.2/client64/lib" | tee -a /etc/profile
RUN echo "export LD_LIBRARY_PATH64=/usr/lib/oracle/12.2/client64/lib/" | tee -a /etc/profile
RUN echo "export PATH=\$ORACLE_HOME/bin:\$PATH" | tee -a /etc/profile

ENV ORACLE_HOME=/usr/lib/oracle/12.2/client64 \
    ORACLE_BASE=/usr/lib/oracle/12.2 \
    LD_LIBRARY_PATH=/usr/lib/oracle/12.2/client64/lib \
    LD_LIBRARY_PATH64=/usr/lib/oracle/12.2/client64/lib/ \
    PATH=$ORACLE_HOME/bin:$PATH 
    
RUN ldconfig

RUN PHP_DTRACE=yes pecl install oci8-2.2.0 <<< instantclient,/usr/lib/oracle/12.2/client64/lib

COPY docker/php7418/* /opt/php74/

RUN chmod +x /opt/php74/install.sh
RUN /opt/php74/install.sh

# Systemd integration
ENV container docker
RUN (cd /lib/systemd/system/sysinit.target.wants/; for i in *; do [ $i == \
systemd-tmpfiles-setup.service ] || rm -f $i; done); \
rm -f /lib/systemd/system/multi-user.target.wants/*;\
rm -f /etc/systemd/system/*.wants/*;\
rm -f /lib/systemd/system/local-fs.target.wants/*; \
rm -f /lib/systemd/system/sockets.target.wants/*udev*; \
rm -f /lib/systemd/system/sockets.target.wants/*initctl*; \
rm -f /lib/systemd/system/basic.target.wants/*;\
rm -f /lib/systemd/system/anaconda.target.wants/*;
VOLUME [ "/sys/fs/cgroup" ]

COPY . /usr/share/nginx/html/dms

# Install Nginx
RUN yum -y install nginx php-fpm

RUN cp /usr/share/nginx/html/dms/docker/nginx.conf /etc/nginx/nginx.conf
# RUN systemctl start nginx
RUN systemctl enable nginx
# RUN systemctl status nginx
# RUN systemctl start php-fpm
RUN systemctl enable php-fpm
# RUN systemctl status php-fpm

# RUN chown -R nginx:nginx /usr/share/nginx/html
RUN chgrp -R nginx /usr/share/nginx/html
RUN chmod -R ug+rwx /usr/share/nginx/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer self-update --1

RUN cd /usr/share/nginx/html/dms && composer install

# Install pdftotext
RUN yum -y install poppler-utils git
# Install leptonica
RUN yum group install -y "Development Tools"
RUN cd /usr/share/nginx/html/dms/docker/pdftotext/ && tar -zxvf leptonica-1.75.3.tar.gz && rm leptonica-1.75.3.tar.gz
RUN cd /usr/share/nginx/html/dms/docker/pdftotext/leptonica-1.75.3 && ./autobuild && ./configure && make -j && make install
# Install jbig2enc
RUN cd /usr/share/nginx/html/dms/docker/pdftotext/jbig2enc && ./autogen.sh && ./configure && make && make install
# install tesseract
RUN cd /usr/share/nginx/html/dms/docker/pdftotext/tesseract-4.1.1/ && ./autogen.sh && PKG_CONFIG_PATH=/usr/local/lib/pkgconfig LIBLEPT_HEADERSDIR=/usr/local/include ./configure --with-extra-includes=/usr/local/include --with-extra-libraries=/usr/local/lib && LDFLAGS="-L/usr/local/lib" CFLAGS="-I/usr/local/include" make -j && make install && ldconfig
RUN mv /usr/share/nginx/html/dms/docker/pdftotext/*.traineddata /usr/local/share/tessdata
# Install OCRMYPDF
RUN yum install -y python3
RUN pip3 install --upgrade pip
RUN pip3 install pikepdf ocrmypdf

CMD ["/usr/sbin/init"]
# ENTRYPOINT ["/usr/sbin/nginx","-g","daemon off;"]