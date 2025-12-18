# Suivi des Tickets Backend

Ce document répertorie les tickets backend réalisés et les principaux fichiers créés ou modifiés pour chacun d'eux.

| ID Ticket | Titre du Ticket | Fichiers Principaux Créés/Modifiés |
|-----------|----------------|-----------------------------------|
| 5 | La page d'accueil | `app/Http/Controllers/AccueilController.php`, `resources/views/welcome.blade.php` |
| 6 | La page article | `app/Http/Controllers/ArticleController.php`, `resources/views/article/show.blade.php` |
| 7 | Le nombre de vues d'un article | `app/Http/Controllers/ArticleController.php` (méthode show), `database/migrations/2026_11_29_102802_create_article_table.php` |
| 8 | Les articles d'une caractéristique donnée | `app/Http/Controllers/ArticleController.php` (méthode filterByCharacteristic), `app/Http/Controllers/CaracteristiqueController.php` |
| 9 | La page d'un utilisateur | `app/Http/Controllers/UserController.php` (méthode show), `resources/views/users/show.blade.php` |
| 10 | La page personnelle | `app/Http/Controllers/UserController.php` (méthode profile), `resources/views/users/profile.blade.php` |
| 11 | Afficher uniquement les articles actifs | `app/Http/Controllers/ArticleController.php` (méthode index) |
| 12 | Création d'un article | `app/Http/Controllers/ArticleController.php` (méthodes create, store), `resources/views/article/create.blade.php` |
| 13 | Page de modification d'un article | `app/Http/Controllers/ArticleController.php` (méthodes edit, update), `resources/views/article/edit.blade.php` |
| 14 | Activer un article | `app/Http/Controllers/ArticleController.php` (méthode update), `resources/views/article/edit.blade.php` |
| 15 | Utiliser le format markdown (Optionnel) | `resources/views/article/show.blade.php` (intégration markdown) |
| 16 | Aimer ou non un article | `app/Http/Controllers/ArticleController.php` (méthodes like, dislike, unlike), `database/migrations/2027_12_04_150333_create_likes_table.php` |
| 17 | Commenter un article | `app/Http/Controllers/ArticleController.php` (méthode addComment), `app/Models/Avis.php`, `database/migrations/2027_12_04_145654_create_avis_table.php` |
| 18 | Suivre une personne | `app/Http/Controllers/UserController.php` (méthode follow), `database/migrations/2025_12_02_080045_create_suivi_table.php` |
| 19 | Proposer des personnes aux profils similaires | `app/Http/Controllers/UserController.php` (méthode findSimilarUsers) |
| 20 | Notifications | `app/Notifications/NewArticlePublished.php`, `database/migrations/2025_12_17_180714_create_notifications_table.php` |

## Notes
- Les contrôleurs se trouvent dans `app/Http/Controllers/`.
- Les modèles se trouvent dans `app/Models/`.
- Les vues se trouvent dans `resources/views/`.
- Les migrations se trouvent dans `database/migrations/`.

