FROM centos:7

RUN yum -y update

RUN mkdir -p /opt/php74/

# Add EPEL and REMI Repository
COPY centos7/* /opt/php74/
RUN yum -y install /opt/php74/epel-release-latest-7.noarch.rpm
RUN yum -y install /opt/php74/remi-release-7.rpm

# Install PHP 7.4 on CentOS 7
RUN yum -y install yum-utils
RUN yum-config-manager --enable remi-php74

RUN yum -y update

RUN yum -y install php php-cli

RUN yum -y install php php-pecl-mcrypt php-cli php-gd php-curl php-mysqlnd php-ldap php-zip php-fileinfo php-xml php-intl php-mbstring php-opcache php-process systemtap-sdt-devel php-pear php-json php-devel php-common php-bcmath php-pdo php-oci8 libaio

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

COPY php7418/* /opt/php74/

RUN chmod +x /opt/php74/install.sh
RUN /opt/php74/install.sh

CMD ["/usr/sbin/init"]