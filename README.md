# tictactoe
TicTacToe Laravel  

## Pré-requis
  * [Docker](https://www.docker.com/products/docker)
  * [Composer](https://getcomposer.org/download/)

## Configuration

### Récupération des dépendances :
```bash
composer install
```

### Créer un répertoire pour les données de postgres
```bash
mkdir -p data/postgres
```

### Lancer les containers
```bash
docker-compose up
```

### Création du schéma BDD

```bash
docker-compose exec app php artisan migrate
```

### Faire un tour sur http://127.0.0.1:8080
