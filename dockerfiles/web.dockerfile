# Use AlmaLinux 8 as the base image
FROM almalinux:8
RUN dnf -y install httpd php php-common php-cli php-mysqlnd php-json

RUN echo "LoadModule php7_module modules/libphp7.so" >> /etc/httpd/conf/httpd.conf

EXPOSE 80

COPY ./src /var/www/html

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
