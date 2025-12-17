# Système de Notifications - Ticket #18

## Description
Ce système permet d'afficher les nouveaux articles publiés par les personnes que vous suivez dans votre page personnelle.

## Fonctionnalités implémentées

1. **Création de la table notifications** : Utilise le système de notifications Laravel basé sur la base de données.

2. **Notification NewArticlePublished** : Classe de notification qui envoie une alerte aux suiveurs lorsqu'un nouvel article est publié.

3. **Envoi automatique** :
   - Lors de la création d'un article en ligne
   - Lors de la mise en ligne d'un article en cours de rédaction

4. **Affichage dans la page personnelle** : Les notifications non lues s'affichent dans la page `/profile`.

## Utilisation en local

**IMPORTANT** : Pour que les notifications fonctionnent correctement, vous devez lancer le worker de queue :

```bash
php artisan queue:work
```

Ce worker doit rester actif en arrière-plan pour traiter les notifications en temps réel.

## Fichiers modifiés

- `app/Notifications/NewArticlePublished.php` - Classe de notification
- `app/Http/Controllers/ArticleController.php` - Envoi des notifications lors de la publication
- `app/Http/Controllers/UserController.php` - Récupération des notifications
- `resources/views/users/profile.blade.php` - Affichage des notifications
- `database/migrations/xxxx_create_notifications_table.php` - Table des notifications

## Comment tester

1. Lancer le serveur : `php artisan serve`
2. Lancer le worker : `php artisan queue:work`
3. Se connecter avec un utilisateur A
4. Suivre un utilisateur B
5. Se connecter avec l'utilisateur B
6. Créer un article en ligne
7. Se reconnecter avec l'utilisateur A
8. Aller sur `/profile` : la notification du nouvel article de B devrait s'afficher

