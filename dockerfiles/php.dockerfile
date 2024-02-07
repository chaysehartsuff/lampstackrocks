# Use AlmaLinux 8 as the base image
FROM almalinux:8

RUN yum install -y epel-release https://rpms.remirepo.net/enterprise/remi-release-8.rpm && \
    yum clean all && \
    rm -rf /var/cache/yum

RUN yum module reset php -y && \
    yum module enable php:remi-7.4 -y

RUN yum install -y php php-fpm php-mysqlnd php-json && \
    yum clean all && \
    rm -rf /var/cache/yum

RUN mkdir -p /run/php-fpm && \
    chown -R nobody:nobody /run/php-fpm

RUN sed -i 's/^listen = .*/listen = 9000/' /etc/php-fpm.d/www.conf

RUN sed -i '/^listen.allowed_clients =/d' /etc/php-fpm.d/www.conf

RUN echo 'catch_workers_output = yes' >> /etc/php-fpm.d/www.conf

RUN echo 'env[MYSQL_DATABASE] = $MYSQL_DATABASE' >> /etc/php-fpm.d/www.conf && \
    echo 'env[MYSQL_USER] = $MYSQL_USER' >> /etc/php-fpm.d/www.conf && \
    echo 'env[MYSQL_PASSWORD] = $MYSQL_PASSWORD' >> /etc/php-fpm.d/www.conf

EXPOSE 9000

CMD ["php-fpm", "-F"]
