# MTP Symfony Test
### A continuación se muestran una serie de instrucciones necesarias para instalar el proyecto:

## Instalar los paquetes de Composer:
* `composer install`

## Configurar el archivo .env:
### Se debe configurar el archivo `.env` que se encuentra en la raiz del proyecto, con los accesos a la BD.

## Crear la BD:
### Si la base de datos que declaraste en el `.env` no existe en tu ambiente local, puedes crearla a través de este comando:
* `php bin/console doctrine:database:create`

## Migrations
### Primero, se deben crear las migraciones del proyecto y esto puedes hacerlo de la siguiente manera:
* `php bin/console make:migration`

### Luego de crear las migraciones, estás mismas deben ser ejecutadas con el siguiente comando:
* `php bin/console doctrine:migrations:migrate`

## Seeds
### Primero debes ejecutar el seed del sistema para que cargue la BD con un usuario de pruebas. Esto se hace de la siguiente manera:
* `php bin/console doctrine:fixtures:load`

## Login
### Una vez ejecutado el seed, puedes acceder al login del sistema con las siguientes credenciales:
* `correo: admin@admin.com`
* `contraseña: admin`

## Servidor local
### Listo, la aplicación ya está lista para funcionar. Activa el servidor local a través el siguiente comando:
* `symfony serve`
