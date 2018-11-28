Netgen Site install instructions
================================

Software requirements
---------------------

* PHP built in server / Apache 2.4+ / Nginx 1.12+
* MySQL 5.6+
* PHP 7.1+ (with `gd`, `imagick`, `curl`, `json`, `mysql`, `xsl`, `xml`, `intl` and `mbstring` extensions)
* ImageMagick

Optional dependencies
---------------------

* Varnish 4+
* Solr 6.5+

Installation instructions
-------------------------

### MySQL database

Use the following MySQL DDL to create a database which will be used for your project:

```mysql
CREATE DATABASE <db_name> CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
```

### Clone the repo

Clone this repo to an empty directory and position yourself in newly created directory.

### Run Composer to install dependencies

If you're setting up the site in development environment, you can simply run:

```
composer install
```

However, if you're setting up the site in production environment,
Composer **must** be ran as follows:

```
SYMFONY_ENV=prod composer install --no-dev -o
```

Near the end of vendor installation procedure, when asked, be sure to specify
the correct database connection for the site.

### Generate frontend assets

Run the following to generate development versions of the assets:

```
yarn install
yarn build:dev
```

or to build production versions of the assets:

```
yarn install
yarn build:prod
```

### Import database schema and demo data

Run the following command to import database schema and demo data (add `--env=prod`
after `bin/console` if running in prod mode):

```
php bin/console ezplatform:install <SITE_NAME>
```

where `<SITE_NAME>` is the name of wanted site, e.g. `netgen-media`,
or `netgen-media-clean` for the clean version, without demo data.

### Run PHP built in server / Setup Apache virtual host

For development purposes, you can use PHP built in server to run the site.

Just start with:

```
php bin/console server:run -d web
```

Alternatively, you can create a new Apache virtual host and set it up to point
to `web/` directory inside the repo root.

An example virtual host is available at `doc/apache2/netgen-site-vhost.conf`

If you wish to use rewrite rules located `.htaccess` file instead of putting
them in virtual host configuration, you can use a virtual host variant located
at `doc/apache2/netgen-site.conf`

### Setup folder permissions

You need to setup file and directory permissions so eZ Platform can write to cache,
log and var folders:

```bash
$ setfacl -R -m u:<web-user>:rwX -m g:<web-user>:rwX var web/var
$ setfacl -dR -m u:<web-user>:rwX -m g:<web-user>:rwX var web/var
```

In case `setfacl` is not available on your system, refer to [Symfony installation instructions]
to set up the permissions correctly.

[Symfony installation instructions]: https://symfony.com/doc/3.4/setup/file_permissions.html
