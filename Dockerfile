FROM debian:stretch-slim
COPY . /mypictures
RUN apt-get update && \
  apt-get install -y php7.0 php7.0-xml php7.0-zip git curl && \
  curl -sS https://getcomposer.org/installer |\
     php -- --install-dir=/usr/bin --filename=composer && \
  cd mypictures && \
  composer install -n


EXPOSE 8080
WORKDIR /mypictures

CMD ["php", "bin/console", "server:run", "0.0.0.0:8080"]
