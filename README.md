# Photo sharing portal

This will be some kind of mini-instagram. Users can upload posts containing photos. Other users can follow profiles and like/comment posts.
Every user has his own proflile with all posts.

Project is based on Symfony 4.
Project is still in development.

## Getting Started

### Requirements

If you want to host it yourself, you need

PHP 7.2+ or newer
HTTP server with PHP support (eg: Apache, Nginx)
MySQL server
Composer

### Installing

Install dependencies using composer. Go to project root directory and run:

```
composer install
```

If you want you can add fixtures:

```
php bin/console doctrine:fixtures:load
```

After this you can use admin:devpass for login.
