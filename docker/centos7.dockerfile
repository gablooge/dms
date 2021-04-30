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

CMD ["/usr/sbin/init"]