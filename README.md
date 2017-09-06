BDN
===

The new version (V3) of the Parabot BDN, just the API will be developed until a statisfied version.

#### How to create a development environment
1. Clone or download the source
2. Navigate to the project-root folder with a terminal
3. Execute the command `composer install`. This will ask you to fill in all kind of Parameters, fill them in with the information you have, leave the fields empty you don't have.
4. Execute the command `php app/console doctrine:schema:update --force`. This will create the database with the required schema.
5. Execute the command `php app/console doctrine:migrations:migrate`. This will create the rest of the database and fill parts of it.

#### I just want the commands to setup
First execute:
```bash
composer install
```

After Composer has installed its libraries, execute the following:
```bash
php app/console doctrine:schema:update --force
php app/console doctrine:migrations:migrate
```

#### How do I setup the cronjob(s)
With `app/console cronos:dump` you can see what cronjob(s) will be installed.

To install the cronjob(s), execute the following command:
```bash
app/console cronos:replace --server=web --env=dev
```