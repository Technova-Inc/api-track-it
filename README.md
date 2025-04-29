# API Track IT

Ce projet est un backend développé en PHP avec CodeIgniter 3, servant d'API pour gérer les informations des PC. L'API permet d'enregistrer et de mettre à jour les informations des PC via des requêtes POST.

## Prérequis

- PHP 7.2 ou supérieur
- Composer
- Un serveur web (Apache, Nginx, etc.)
- MySQL ou toute autre base de données compatible

## Installation

1. Clonez le dépôt :

    ```bash
    git clone https://github.com/Technova-Inc/api-track-it.git
    cd api-track-it
    ```

3. Configurez votre base de données dans `application/config/database.php`.

4. Importez le fichier de base de données `database.sql` dans votre base de données.

5. Configurez votre serveur web pour pointer vers le dossier `public` du projet.

## Utilisation

### Endpoint pour enregistrer/mettre à jour les informations des PC

**URL** : `/pull`

**Méthode** : `POST`

**Headers** :
- `Content-Type: application/json`

**Body** :
```json
{
  "name": "NomPC",
  "os": "Windows",
  "osname": "Windows 10",
  "architecture": "x64",
  "user": "NomUtilisateur",
  "ram": "16",
  "cpu": "Intel Core i7",
  "serial": "1234-5678-9101-1121",
  "mac": "00:1A:2B:3C:4D:5E",
  "ip": "192.168.1.100",
  "domaine": "votre-domaine.com",
  "windows_key": "XXXXX-XXXXX-XXXXX-XXXXX-XXXXX",
  "license_status": "Licensed",
  "uuid": "UUID-EXAMPLE-1234"
}
```

**Réponse** :
- `200 OK` en cas de succès
- `400 Bad Request` en cas d'erreur de données

### Exemple avec Postman

1. Ouvrez Postman et créez une nouvelle requête.
2. Sélectionnez la méthode `POST` et entrez l'URL : `https://votre-domaine.com/pull`.
3. Allez dans l'onglet "Headers" et ajoutez :
   - Key: `Content-Type`
   - Value: `application/json`
4. Allez dans l'onglet "Body", sélectionnez "raw" et "JSON" dans le menu déroulant.
5. Entrez les données JSON à envoyer.
6. Cliquez sur "Send" pour envoyer la requête.

## Documentation de l'API

Pour plus de détails sur l'utilisation de l'API, veuillez consulter notre [documentation API](https://github.com/Technova-Inc/API-TrackIT-Documentation).

## Développement

Si vous souhaitez contribuer au développement de ce projet, suivez les étapes ci-dessous :

1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/ma-fonctionnalité`).
3. Commitez vos modifications (`git commit -am 'Ajoute une nouvelle fonctionnalité'`).
4. Poussez votre branche (`git push origin feature/ma-fonctionnalité`).
5. Ouvrez une Pull Request.

## Aide

Pour toute question ou problème, veuillez ouvrir une [issue](https://github.com/Technova-Inc/api-track-it/issues).

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
