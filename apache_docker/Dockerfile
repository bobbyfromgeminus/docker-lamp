FROM httpd:2.4-alpine

# Frissítések telepítése
RUN apk update && apk upgrade

# Szükséges csomagok telepítése
RUN apk add --no-cache wget unzip

# Apache virtuális hoszt fájl másolása, hogy a PHP kéréseket proxyzza a PHP konténerhez
COPY apache-vhost.conf /usr/local/apache2/conf/apache-vhost.conf
RUN echo "Include /usr/local/apache2/conf/apache-vhost.conf" \
    >> /usr/local/apache2/conf/httpd.conf     

# WordPress letöltése és kicsomagolása
RUN wget https://wordpress.org/latest.zip -O /tmp/wordpress.zip \
    && unzip /tmp/wordpress.zip -d /tmp/ \
    && mv /tmp/wordpress/* /usr/local/apache2/htdocs/ \
    && rm /tmp/wordpress.zip

# Jogosultságok beállítása
RUN chown -R www-data:www-data /usr/local/apache2/htdocs/

# Apache futtatása www-data felhasználóként
USER www-data
