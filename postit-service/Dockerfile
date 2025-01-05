FROM php:8.0-cli
WORKDIR /usr/src/app

# Installer mysqli
RUN docker-php-ext-install mysqli

# Copier les fichiers de l'application
COPY . .

# Configuration Apache
RUN apt-get update && apt-get install -y apache2 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exposer le port
EXPOSE 8002

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8002", "postit_service.php"]
