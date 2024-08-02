# Dockerized LAMP
Ennek a tárolónak a segítségével gyorsan készíthető egy dockerizált LAMP szerver, amely az alábbi komponenseket tartalmazza:
- Apache HTTP szerver
- PHP szerver oldali script nyelv
- MariaDB adatbázis
- PHPMyAdmin

## 1. Konténerek és szolgáltatások indítása
`docker-compose up -d`

## 2. Wordpress telepítése
A projekt már tartalmazza a **wordpress** adatbázist és a hozzá tartozó **sqluser** falhasználót,
így mindössze annyi a teendő, hogy a legfrissebb wordpress-t letöltve és a **www** mappába kicsomagolva konfiguráljuk azt a böngészőnkben!

## Konténerek és szolgáltatások leállítása
`docker-compose down`

## Rendszer takarítása
`docker system prune -a`

## Forrás
https://linuxiac.com/how-to-set-up-lamp-stack-with-docker-compose/