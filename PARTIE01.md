# PARTIE 1 - Conteneurisation de l'application Gestion Produits

## 📋 Vue d'ensemble

Cette partie présente la conteneurisation complète de l'application de gestion de produits avec Docker, incluant la base de données MySQL et l'application PHP/Apache.

## 🗂️ Structure du projet

```
gestion-produits/
├── database/
│   ├── Dockerfile
│   └── gestion_produits.sql
├── php/
│   ├── Dockerfile
│   └── www/
│       ├── *.php
│       ├── *.html
│       ├── *.js
│       ├── *.css
│       └── uploads/
├── docker-compose.yml
└── .env
```

## 🐳 Fichiers Dockerfile

### 1. Dockerfile Database (MySQL)

**Localisation :** `./database/Dockerfile`

```dockerfile
FROM mysql:8.4

# Copie des scripts d'initialisation SQL
COPY *.sql /docker-entrypoint-initdb.d/

EXPOSE 3306
```

### 2. Dockerfile PHP (Apache)

**Localisation :** `./php/Dockerfile`

```dockerfile
FROM php:8-apache

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

# Copie du code source de l'application
COPY www/ /var/www/html/

# Configuration des permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/
RUN mkdir -p /var/www/html/uploads
RUN chmod 777 /var/www/html/uploads

EXPOSE 80
```

## ⚙️ Commandes pour construire les images

### Construction locale des images

```bash
# Construction de l'image de base de données
docker build -t emmanueljprime/gestion-produits-db:v1.0.0 ./database

# Construction de l'image PHP
docker build -t emmanueljprime/gestion-produits-php:v1.0.0 ./php
```

### Publication sur Docker Hub

```bash
# Connexion à Docker Hub
docker login

# Publication de l'image database
docker push emmanueljprime/gestion-produits-db:v1.0.0

# Publication de l'image PHP
docker push emmanueljprime/gestion-produits-php:v1.0.0

# Création et publication des tags latest
docker tag emmanueljprime/gestion-produits-db:v1.0.0 emmanueljprime/gestion-produits-db:latest
docker tag emmanueljprime/gestion-produits-php:v1.0.0 emmanueljprime/gestion-produits-php:latest

docker push emmanueljprime/gestion-produits-db:latest
docker push emmanueljprime/gestion-produits-php:latest
```

## 🔗 URL des images Docker publiées

### Images sur Docker Hub

- **Image Database :** 
  - `emmanueljprime/gestion-produits-db:v1.0.0`
  - `emmanueljprime/gestion-produits-db:latest`
  - **URL :** https://hub.docker.com/r/emmanueljprime/gestion-produits-db

- **Image PHP :** 
  - `emmanueljprime/gestion-produits-php:v1.0.0`
  - `emmanueljprime/gestion-produits-php:latest`
  - **URL :** https://hub.docker.com/r/emmanueljprime/gestion-produits-php

## 📄 Fichier docker-compose.yml

```yaml
services:
  db:
    image: emmanueljprime/gestion-produits-db:${APP_VERSION:-latest}
    restart: always
    env_file:
      - .env
    environment:
      - MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
      - MYSQL_DATABASE=${MYSQL_DATABASE}
      - MYSQL_USER=${MYSQL_USER}
      - MYSQL_PASSWORD=${MYSQL_PASSWORD}
    ports:
      - "${DB_EXTERNAL_PORT:-3306}:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  php:
    image: emmanueljprime/gestion-produits-php:${APP_VERSION:-latest}
    restart: always
    ports:
      - "${PHP_EXTERNAL_PORT:-80}:80"
    depends_on:
      - db
    environment:
      - MYSQL_DATABASE=${MYSQL_DATABASE}
      - MYSQL_USER=${MYSQL_USER}
      - MYSQL_PASSWORD=${MYSQL_PASSWORD}
    volumes:
      - uploads_data:/var/www/html/uploads

volumes:
  mysql_data:
  uploads_data:
```

## 🔧 Fichier de configuration .env

```env
# Ports externes configurables
DB_EXTERNAL_PORT=3306
PHP_EXTERNAL_PORT=80

# Version de l'application
APP_VERSION=v1.0.0

# Configuration MySQL
MYSQL_ROOT_PASSWORD=motdepasse_root_secure
MYSQL_DATABASE=gestion_produits
MYSQL_USER=admin
MYSQL_PASSWORD=password_secure
```

## 🚀 Déploiement

### Lancement de l'application

```bash
# Cloner ou télécharger le projet
git clone <repository-url>
cd gestion-produits

# Lancement avec docker-compose
docker-compose up -d

# Vérification du statut
docker-compose ps
```

### Accès à l'application

- **Application PHP :** http://localhost:80 (ou port configuré dans `.env`)
- **Base de données MySQL :** localhost:3306 (ou port configuré dans `.env`)

### Arrêt de l'application

```bash
# Arrêt des conteneurs
docker-compose down

# Arrêt et suppression des volumes (perte de données)
docker-compose down -v
```

## 🎯 Fonctionnalités

### Paramétrage flexible

- **Ports configurables :** Modifiez `DB_EXTERNAL_PORT` et `PHP_EXTERNAL_PORT` dans `.env`
- **Gestion des versions :** Utilisez `APP_VERSION` pour spécifier la version des images
- **Variables d'environnement :** Toutes les configurations sensibles sont externalisées dans `.env`

### Persistance des données

- **Base de données :** Volume `mysql_data` pour persistance des données MySQL
- **Uploads :** Volume `uploads_data` pour persistance des fichiers uploadés

## 📊 Avantages de cette architecture

1. **Sécurité :** Variables sensibles externalisées dans `.env`
2. **Flexibilité :** Ports et versions configurables
3. **Persistance :** Données conservées entre les redémarrages
4. **Portabilité :** Images disponibles sur Docker Hub
5. **Simplicité :** Déploiement en une commande avec `docker-compose up`

---

**Date de création :** 13 octobre 2025  
**Version :** v1.0.0  
**Auteur :** Emmanuel Prime
