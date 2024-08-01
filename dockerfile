FROM ubuntu:20.04

RUN apt-get update


# PHP8.1  installation
RUN  apt-get  install -y     nano sudo   git  curl  zip unzip
RUN ln -snf /usr/share/zoneinfo/$CONTAINER_TIMEZONE /etc/localtime && echo $CONTAINER_TIMEZONE > /etc/timezone
RUN apt-get update && apt-get install -y tzdata
RUN sudo apt-get install lsb-release ca-certificates apt-transport-https software-properties-common -y
RUN sudo add-apt-repository ppa:ondrej/php
RUN sudo apt-get install -y php8.1 
RUN sudo apt-get install -y php8.1-fpm php8.1-common php8.1-curl php8.1-xml php8.1-mbstring php8.1-cli php8.1-zip  php8.1-dom php8.1-fpm php8.1-pgsql

RUN apt-get update
RUN sudo apt-get install -y  postgresql postgresql-contrib

# Nginx files configurations
RUN sudo apt-get install -y nginx
RUN mkdir -p /var/www/html/icarepro
COPY ./   /var/www/html/icarepro
WORKDIR /var/www/html/icarepro
COPY Docker/domain  /etc/nginx/sites-available/
RUN rm -f /etc/nginx/sites-enabled/*
RUN ln -s /etc/nginx/sites-available/domain /etc/nginx/sites-enabled/
EXPOSE 80

# Database user creation and roles
USER postgres
RUN /etc/init.d/postgresql start &&\
    psql --command "CREATE USER icarepro_user WITH SUPERUSER PASSWORD '12345';" &&\
    createdb -O  icarepro_user  icarepro
RUN echo "host all  all    0.0.0.0/0  md5" >> /etc/postgresql/12/main/pg_hba.conf


# Composer install
USER root
RUN cp  .env.example   .env
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
WORKDIR /var/www/html/icarepro
RUN composer install


# DB  migerations & seeds
RUN ["chmod", "+x", "run.sh"]
RUN bash run.sh
#RUN php artisan migrate:fresh
#RUN php artisan db:seed
#RUN php artisan passport:install
#RUN php artisan l5-swagger:generate

#RUN php artisan storage:link

#RUN php artisan key:generate


#directory permission
RUN chown -R ${USER}:www-data /var/www/html/icarepro/storage
RUN chown -R ${USER}:www-data /var/www/html/icarepro/bootstrap
RUN chown -R ${USER}:www-data /var/www/html/icarepro/public
RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache

#nginx deamon off and start fpm postgresql
ENTRYPOINT ["/bin/bash", "-c", " service nginx start && service postgresql start &&  service php8.1-fpm start && tail -f /dev/null"]



