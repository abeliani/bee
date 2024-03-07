Phinx migrations documentation
https://book.cakephp.org/phinx/0/en/index.html

Runs all the available migrations
```shell
vendor/bin/phinx migrate -e development
```
Rollback
```shell
vendor/bin/phinx rollback -e development
```
Only target
```shell
vendor/bin/phinx rollback -e development -t 20120103083322
```
Create new one
```shell
vendor/bin/phinx create MyNewMigration
```

Create an admin
```shell
php console/index.php app:user-create-root
```