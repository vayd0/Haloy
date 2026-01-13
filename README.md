# HaLoy.

Projet réalisé dans le cadre d’une SAE à l’IUT de Lens (BUT 2ème année)  
**Sujet :** Réalisation d’un site web en 48h

## Présentation

Ce projet a été développé en 48h par le groupe 7 lors d’un marathon web à l’IUT de Lens.  
L’objectif était de concevoir un site web complet avec gestion d’articles, profils utilisateurs, interactions et animations modernes.

---

## Stack technique

- **Laravel** (backend, API, routes)
- **Vite** (build & hot reload frontend)
- **Tailwind CSS** (design & responsive)
- **Three.js** (animations 3D)
- **GSAP** (animations avancées)
- **Font Awesome** (icônes)

---

## Routes principales

| Méthode | URL                                 | Nom de la route                | Description                                 | Middleware      |
|---------|-------------------------------------|-------------------------------|---------------------------------------------|----------------|
| GET     | `/`                                 | accueil                       | Page d’accueil                              |                |
| GET     | `/contact`                          | contact                       | Page de contact                             |                |
| POST    | `/contact`                          | contact.store                 | Envoi du formulaire de contact              |                |
| GET     | `/articles`                         | articles.all                  | Liste des articles                          |                |
| GET     | `/articles/filter/{type}/{id}`      | articles.filter               | Filtrer les articles par caractéristique    |                |
| GET     | `/profile`                          | user.profile                  | Profil utilisateur                          | auth           |
| POST    | `/notifications/mark-all-read`      | notifications.mark-all-read   | Marquer notifications comme lues            | auth           |
| GET     | `/users/{user}`                     | users.show                    | Voir un utilisateur                         |                |
| POST    | `/users/{user}/follow`              | users.follow                  | Suivre un utilisateur                       | auth           |
| GET     | `/article/create`                   | article.create                | Créer un article                            | auth           |
| POST    | `/article`                          | article.store                 | Enregistrer un article                      | auth           |
| GET     | `/article/{article}/edit`           | article.edit                  | Modifier un article                         | auth           |
| PUT     | `/article/{article}`                | article.update                | Mettre à jour un article                    | auth           |
| GET     | `/article/{article}`                | article.show                  | Voir un article                             |                |
| POST    | `/article/{article}/like`           | article.like                  | Liker un article                            | auth           |
| POST    | `/article/{article}/dislike`        | article.dislike               | Disliker un article                         | auth           |
| POST    | `/article/{article}/unlike`         | article.unlike                | Annuler le like                             | auth           |
| POST    | `/article/{article}/comment`        | article.comment               | Commenter un article                        | auth           |
| GET     | `/rythme/{id}`                      | rythme.articles               | Articles par rythme                         |                |
| GET     | `/accessibilite/{id}`               | accessibilite.articles        | Articles par accessibilité                  |                |
| GET     | `/conclusion/{id}`                  | conclusion.articles           | Articles par conclusion                     |                |

---

## Installation

1. **Cloner le repo**
   ```sh
   git clone https://github.com/<ton-utilisateur>/<nom-du-repo>.git
   cd <nom-du-repo>
   ```
2. **Installer les dépendances**
   ```sh
   composer install
   npm install
   ```
3. **Configurer l’environnement**
   - Copier `.env.example` en `.env` et adapter les variables
   - Migration `php artisan migrate` et `php artisan migrate --seed`
   - Générer la clé Laravel :  
     `php artisan key:generate`
4. **Lancer le serveur**
   ```sh
   php artisan serve
   npm run dev
   ```

---

## Auteurs

- Luc, Louka, Simon, Mathéo, Théo, Iliano, Justin, Emma
