#ATOM backend API

##Info
todo

###getting up and running:
* get PHP + mySQL + composer up and running
  * php: install via package manager
  * mySQL: install via package manager
	* remember to install/setup the php mysql driver
	  * arch linux: https://wiki.archlinux.org/index.php/PHP#MySQL.2FMariaDB
	  * ubuntu/debian linux: `sudo apt-get install php5-mysql`
	* also remember to start the service if you're in linux
  * composer: [instructions here](https://getcomposer.org/doc/00-intro.md)
  	* useful: [https://adamcod.es/2013/03/07/composer-install-vs-composer-update.html](https://adamcod.es/2013/03/07/composer-install-vs-composer-update.html) 
* run `composer install`
* copy .env.example into .env and configure your DB credentials (and create a DB)
* create the database
  * run `mysql -u root -p`
  * in the sql shell run `create database yourdatabasename;`
* run `php artisan migrate`
* run `php artisan db:seed` (this seeds the roles table)
* Generate a jwt secret
  * php artisan jwt:generate
  * also run this: php artisan key:generate
* if you are on apache for dev; read this:
	* https://github.com/tymondesigns/jwt-auth/wiki/Authentication