FROM php:8.0-apache
WORKDIR /var/www/html

# Installer les extensions nécessaires
RUN docker-php-ext-install mysqli

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exposer le port 80 pour Apache
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
