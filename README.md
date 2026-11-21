Projet réaliser par :
sasha Passas Youssi
Léo Murça

## Lancer le projet

Le dossier `vendor/` n'est pas versionné : il faut générer l'autoload de Composer après avoir cloné le projet.

```bash
composer dump-autoload
php -S localhost:8000
```

Puis ouvrir http://localhost:8000 : le scénario s'exécute et sauvegarde l'agence dans `data/agence.txt`.