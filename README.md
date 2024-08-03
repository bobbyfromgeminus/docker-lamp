# Dockerized LAMP
Ennek a tárolónak a segítségével gyorsan készíthető egy dockerizált LAMP szerver, amely az alábbi komponenseket tartalmazza:
- Apache HTTP szerver
- PHP szerver oldali script nyelv
- MariaDB adatbázis
- PHPMyAdmin
- Wordpress

## Konténerek és szolgáltatások indítása
`docker-compose up -d`

## Konténerek és szolgáltatások leállítása
`docker-compose down`

## Rendszer takarítása
`docker system prune -a`

## Forrás
https://linuxiac.com/how-to-set-up-lamp-stack-with-docker-compose/