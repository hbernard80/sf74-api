# sf74-api

📆 17/04/2026

Projet de démo d'une exposition d'<abbr title="Application Programming Interface">API</abbr> en Symfony + avec API Platform/Symfony, type CMS Headless.

## Stack technique

* Symfony 7.4 <abbr title="Long-Term Support">LTS</abbr> (webapp)
* API Platform 4.3.3
* PHP 8.5.0
* MySQL 8.0.43 

## Etat actuel (Done)

* Installation et configuration de Symfony + API Platform
* Gestion des utilisateurs (mail de vérification, rôles, User Checker etc.)
* Fixtures via [FakerPHP](https://fakerphp.org) + commande de synchronisation des adresses mails avec le nom des utilisateurs 
* Entités _Post_, _Category_ et _User_ + Fixtures
* Trait pour la gestion des dates (propriétés `created_at` et `updates_at`)
* Internationalisation (incomplet)

## ⌛ TODO 

### Chore

* Rédiger procédure clonage, installation et utilisation

### Features

* Global : 
  * Pagination : synchroniser _User_ et _Post_ (systèmes différents)
  * Compléter UI/UX des templates
  * Compléter traductions des templates/CRUDs/formulaires 
* Formulaires de recherche, au moins un
* Articles
  * Gestion des auteurs : pour le moment une modification change l'auteur. Historiser les modifications donc auteurs multiples sans changer l'auteur initial. Seul l'auteur initial et le rôle admin peuvent éditer et supprimer un article.  
* Utilisateurs : 
  * Gestion du profil et des formulaires 
  * Gestion du profil 
  * Upload photo/avatar
  * Voter  
* API : 
  * <abbr title="Data Transfer Object">D.T.O.</abbr>
  * Validation
  * Filtrage
  * Pagination
  * Sécurisation/authentification API
  * Frontend appel API (techno à définir).

<hr>

# Utilisation

> URL de la documentation de l'API : http://localhost:8000/api/v1/.
  
## Obtenir la Liste des articles 

* Request URL : _https://127.0.0.1:8000/api/v1/posts?page=1&itemsPerPage=10&order%5Bid%5D=asc&order%5Btitle%5D=asc&order%5Bslug%5D=asc_ (page1, 10 par page, ordre ascendant)
* Commande cURL (syntaxe Windows + serveur local Symfony) : 

```bash
curl -k "https://127.0.0.1:8000/api/v1/posts?page=1&itemsPerPage=10&order%5Bid%5D=asc&order%5Btitle%5D=asc&order%5Bslug%5D=asc" -H "accept: application/ld+json"
```

## Obtenir l'article 1
 
 * Request URL : __.