# pickem.watchpoint.io

## setup

#### initialize your environment

```
# copy env config & update your values
cp .env.php.default .env.php
```

#### setup and run database migrations

```
# creates migrations table in the database
bin/wpt migrate:setup
# run migrations
bin/wpt migrate
```

## migrations

Migrations have access to the app via the `$app` variable, which is an instance of `src/App.php`. You can use this variable
to access things like the database, `$app->db()`, or App specific options, `$app->option('session_name')`.

#### create new migration

```
# this command will create you a new migrations script that you 
# can then update to do whatever you would like
bin/wpt migrate:new {some name}
```

#### rollback migrations

When you create a new migration you will see a block of code like:

```
if ($rollback) {
  // rollback logic here
  return;
}
```

This is where you should put your rollback logic for that specific migration. The `$rollback` variable gets passed in when
a migration is being rolled back. It is important to keep the `return;` in there, otherwise the rest of your migration will
run. For example,

```
<?php

// Create the users table

if ($rollback) {
  $app->db()->query("DROP TABLE users");
  return;
}

// ... code here to CREATE the users table

```

Its a little funky, but I like it.

```
# this command will rollback the last migrated migration
bin/wpt migrate:rollback
```

I find it useful to test 🙉🙈🙊 my migrations by migrating it then rolling it back. For example,

```
# first migrate it
> bin/wpt migrate
* Running 20171129065736_users.php...Done
# update your rollback logic and roll it back
> bin/wpt migrate:rollback
* Rolling back 20171129065736_users.php...Done
# make your changes, rinse and repeat
```
