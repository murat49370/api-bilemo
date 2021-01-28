# P7 - API Bilemo

[![Maintainability](https://api.codeclimate.com/v1/badges/ffbadec3f8b12c957b82/maintainability)](https://codeclimate.com/github/murat49370/api-bilemo/maintainability)

Création d'une API Rest pour BileMo, dans le cadre de ma formation PHP / Symfony.

## Environnement utilisé durant le développement
* PHP 7.4
* Symfony 5.2

## Informations sur l'API
* L'obtention du token afin de s'authentifier à l'API se fait via l'envoie des identifiants sur l'URI /api/login_check

## Installation
1. Clonez ou téléchargez le repository GitHub :
```
    git clone https://github.com/murat49370/api-bilemo.git
```
2. Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier `.env`

3. Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :
```
    composer install
```
4. Créez la base de données et données de test :
```
    composer prepare
```
5. Générez les clés SSH ([Solution alternative pour OpenSSL sur Windows](https://slproweb.com/products/Win32OpenSSL.html))
   Et noter votre passphrase à la ligne "JWT_PASSPHRASE=" de votre fichier `.env`
```bash
$ mkdir config/jwt
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

## Documentation API - Swagger
```
https://localhost:8000/swagger/
```
## Login utilisateur test
```
{
  "username": "email+1@email.com",
  "password": "password"
}
```
