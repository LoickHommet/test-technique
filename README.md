# Test technique — Gestion des commandes

Application réalisée dans le cadre du test technique **Développeur Full Stack PHP / Laravel / Livewire**.

L'objectif est de proposer une interface back-office permettant au service client de consulter, rechercher et gérer les commandes, tout en affichant quelques indicateurs commerciaux.

## Environnement technique

* PHP 7.4
* Laravel 8
* Livewire 2
* MySQL
* Blade
* HTML / CSS
* JavaScript

## Installation

Cloner le projet puis installer les dépendances :

```bash
composer install
```

Créer le fichier d'environnement :

```bash
cp .env.example .env
```

Générer la clé de l'application :

```bash
php artisan key:generate
```

Configurer la connexion MySQL dans `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_technique
DB_USERNAME=root
DB_PASSWORD=
```

Les valeurs doivent être adaptées à l'environnement local utilisé.

Créer ensuite la structure de la base et les données de démonstration :

```bash
php artisan migrate:fresh --seed
```

Les seeders génèrent environ :

* 100 clients
* 150 produits
* 2 000 commandes
* 5 000 lignes de commande

Démarrer ensuite l'application :

```bash
php artisan serve
```

La gestion des commandes est accessible depuis :

```text
/admin/orders
```

## Fonctionnalités réalisées

### Liste des commandes

La page affiche pour chaque commande :

* la référence ;
* la date ;
* le client ;
* le nombre de lignes de commande ;
* le montant ;
* le statut.

Les relations Eloquent sont chargées avec eager loading afin notamment d'éviter le problème N+1 lors de l'affichage des clients.

### Recherche et filtres

La liste peut être filtrée dynamiquement avec Livewire sans rechargement complet de la page.

La recherche porte sur :

* la référence de commande ;
* le prénom du client ;
* le nom du client ;
* l'adresse e-mail du client.

Les filtres disponibles sont :

* statut ;
* date de début ;
* date de fin.

Les différents filtres sont combinables et peuvent être réinitialisés avec le bouton prévu à cet effet.

### Indicateurs commerciaux

Les indicateurs sont recalculés automatiquement selon les filtres actifs :

* nombre de commandes ;
* chiffre d'affaires ;
* panier moyen.

Les commandes ayant le statut `cancelled` sont exclues du chiffre d'affaires et du calcul du panier moyen.

Si aucune commande non annulée ne correspond aux filtres, le panier moyen est affiché à `0 €`.

La logique des filtres est centralisée dans une même méthode afin de garantir que la liste et les indicateurs utilisent les mêmes critères.

### Modification du statut

Le statut d'une commande peut être modifié directement depuis la liste.

Les statuts autorisés sont :

* `pending`
* `paid`
* `processing`
* `shipped`
* `cancelled`

La nouvelle valeur est validée côté serveur avant la mise à jour de la commande.

Une confirmation est demandée à l'utilisateur avant la modification et un message confirme ensuite la réussite de l'opération.

Après la modification, Livewire actualise automatiquement la liste et les indicateurs commerciaux.

### Pagination

Les commandes sont paginées afin de ne pas charger l'ensemble des commandes en mémoire.

20 commandes sont affichées par page.

Lorsqu'un filtre est modifié, la pagination revient automatiquement à la première page.

## Tests

Un test fonctionnel Livewire a été ajouté pour vérifier la modification du statut d'une commande.

Le test simule la sélection d'un nouveau statut depuis le composant puis vérifie que la modification a bien été enregistrée en base de données.

Les tests peuvent être exécutés avec :

```bash
php artisan test
```


### Requête de filtrage centralisée

La construction de la requête filtrée est centralisée dans le composant Livewire.

Ce choix évite de dupliquer les conditions entre la liste des commandes et les indicateurs commerciaux.

### Validation serveur

La modification du statut est systématiquement validée côté serveur. Les valeurs provenant de l'interface ne sont donc pas considérées comme fiables sans validation.

### Interface

L'interface a volontairement été gardée simple et lisible avec du CSS personnalisé, sans ajouter de framework CSS supplémentaire.

## Limites et améliorations possibles

Compte tenu du temps imparti, certaines améliorations pourraient être apportées avec davantage de temps :

* ajouter davantage de tests automatisés sur les filtres et les indicateurs ;
* améliorer la gestion et l'affichage des erreurs de validation ;
* ajouter des tests spécifiques sur l'exclusion des commandes annulées du chiffre d'affaires ;
* améliorer davantage l'accessibilité et le responsive de l'interface ;
* ajouter une notification au client lors d'un changement important du statut de sa commande ;
* utiliser une file d'attente pour les traitements asynchrones tels que l'envoi d'e-mails ;
* ajouter davantage d'optimisations et d'index SQL si le volume de commandes devait fortement augmenter.

## Problèmes de performances éventuels

Plusieurs points seraient à surveiller si le volume de données augmentait fortement.

Chargement des relations

La liste utilise l'eager loading avec :

->with('customer')

Ce choix permet d'éviter le problème N+1 lors de l'affichage des informations du client pour chaque commande.

L'eager loading reste néanmoins à utiliser de manière raisonnée : charger beaucoup de relations ou un grand nombre d'enregistrements en une seule fois peut augmenter la consommation mémoire et le temps de traitement.

Dans cette application, la pagination à 20 commandes limite ce problème puisque seules les relations nécessaires aux commandes de la page courante sont chargées.

## Requêtes des indicateurs

À chaque modification des filtres, plusieurs requêtes sont exécutées afin de calculer :

la liste des commandes ;
le nombre de commandes ;
le chiffre d'affaires ;
le nombre de commandes non annulées utilisé pour calculer le panier moyen.

Cela reste acceptable pour le volume de données du test, mais sur une base beaucoup plus importante, il serait intéressant d'analyser ces requêtes et leur temps d'exécution.

Des index adaptés pourraient notamment être étudiés sur les colonnes fréquemment utilisées pour les filtres et les recherches.

La recherche avec des expressions de type :

LIKE '%recherche%'

peut également devenir coûteuse sur un volume important de données. Pour une application à plus grande échelle, une solution de recherche plus adaptée pourrait être envisagée.

## État du projet

Les fonctionnalités principales demandées sont réalisées :

* liste des commandes ;
* recherche multi-critères ;
* filtres combinables ;
* filtres Livewire sans rechargement complet ;
* réinitialisation des filtres ;
* indicateurs commerciaux dynamiques ;
* exclusion des commandes annulées du chiffre d'affaires ;
* modification et validation du statut ;
* confirmation avant modification ;
* pagination ;
* interface responsive simple ;
* test automatisé ciblé.
