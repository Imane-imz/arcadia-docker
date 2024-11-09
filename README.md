# Site Web du Zoo Arcadia

## Description
Le site web du Zoo Arcadia offre aux visiteurs des informations complètes sur les services disponibles au zoo. Les employés et les administrateurs peuvent également modifier les services via le site. Les fonctionnalités actuelles incluent la gestion des services, la connexion et la création de compte.

## Table des Matières
1. [Installation](#installation)
2. [Étapes](#etapes)
3. [Importation de la base de données](#BDD)
4. [Lancement du projet](#lancement)

## Installation
### Prérequis
- Node.js
- npm (Node Package Manager)

## Étapes
1. Clonez le dépôt:
    ```bash
    git clone https://github.com/votre-utilisateur/zoo-arcadia.git
    ```
2. Accédez au répertoire du projet:
    ```bash
    cd zoo-arcadia
    ```
3. Installez les dépendances:
    ```bash
    npm install
    composer install
    ```

## Importation de la base de données
### Étape 1
Exporter la base de données depuis l'environnement de production.

### Étape 2
Placez le fichier exporté dans le répertoire de votre projet local. 

## Lancement du projet
Utilisez la commande suivante pour lancer le projet :
```bash
symfony server:start
```

# Utilisation avec docker
## Ouvrir le projet
```bash
docker-compose build
docker-compose up
```

## Exécuter les migrations
```bash
docker exec -it symfony_php bash #rentrer dans le terminal php
php bin/console doctrine:migrations:migrate
```

## Ouvrir Postgresql
```bash
docker exec -it symfony_postgres bash
psql -U symfony
```
