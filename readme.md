<p align="center"><img src="https://raw.githubusercontent.com/infinitypaul/findyourservive/master/public/images/logo.png" /></p>
<p align="center">This web app that allows consumers to search for services close to their proximity.</p>
<p align="center"><a href="https://instagram.com/infinitypaul">Creator</a> | <a href="">Getting Started</a></p>

<p>&nbsp;</p>

## Download Instruction

1. Clone the project.

```
git clone https://github.com/infinitypaul/findyourservive.git projectname
```

2. Install dependencies via composer.

```
composer install 
```

3. Migrate and seed the Database.

```
php artisan migrate --seed
```

4. Run php server.

```
php artisan serve
```



> You can also install the Application via Docker:

## Pre-requisites

- Docker running on the host machine.
- Docker compose running on the host machine.

1. Clone the project.

```
git clone https://github.com/infinitypaul/findyourservive.git projectname
```

2. Run The testrig.sh on your terminal/Command Prompt, This script does everything you need to run your laravel project. It starts up the servers, ensures the database is booted, installs dependencies, runs database migrations, and runs database seeds. These services are exposed to your computer on the standard ports..


## Troubleshooting

- Port number might be already in use, change from `8888` to another number in your `docker-compose.yml` file.
- If you have any other issues, [report them](https://github.com/infinitypaul/findyourservive/issues).

Enjoy!
