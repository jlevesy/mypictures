FROM alpine:3.6
RUN apk --update add curl php7 php7-json php7-iconv php7-phar php7-openssl php7-zlib php7-pdo php7-xml php7-ctype && \
		curl -sS https://getcomposer.org/installer | php7 -- --install-dir=/usr/bin --filename=composer 
COPY . /mypictures
