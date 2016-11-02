BDN
===

The new version (V3) of the Parabot BDN, just the API will be developed until a statisfied version.

#### How to create your database
First make sure the parameters.yml file is correctly filled with all details.  
Then make sure the database you defined also exists!

Now perform the following commands:
```
php app/console doctrine:schema:update --force
php app/console doctrine:migrations:migrate
```

The database should now be filled with tables and some content from the migration.