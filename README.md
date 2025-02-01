# ClassManager

Una herramienta para gestionar ausencias de profesorado en instituciones educativas.

ClassManager es una herramienta diseñada para facilitar la gestión de las ausencias de profesorado. Permite registrar, monitorear y notificar las ausencias, mejorando la organización y el control dentro de las instituciones educativas.

## Características

- Autenticación de usuarios: Gestión de login con Laravel Breeze. Los usuarios son registrados por el administrador mediante formulario o carga de archivo `.csv`.
- Contraseñas: Los usuarios configuran su contraseña a través de la opción "olvidé la contraseña" de Laravel Breeze o con un gestor de correo activo que les envía un link para recuperar la contraseña al ser añadidos a la base de datos.
- CRUD de ausencias: Los usuarios pueden registrar sus ausencias por día y hora. Las ausencias no se pueden editar ni borrar pasados 10 minutos.
- Vista principal: Muestra las ausencias comunicadas para la hora actual, con comentarios y departamentos, permitiendo navegar entre horas y días.
- Vista del administrador: El administrador puede ver, editar y borrar todas las ausencias, y añadir faltas en nombre de otros usuarios.
- Responsiva: La aplicación se adapta tanto a dispositivos de escritorio como móviles.

## Tecnologías Usadas

- PHP (Laravel) v.11.38.2
- JavaScript (Node.js) v.22.12.0
- XAMPP v3.3.0
- MariaDB v.10.4.32
- Tailwind v.3.4.13

### Guía de despliegue en local

1. **composer install**, para instalar las dependencias de Laravel y otros paquetes.
2. Copiar .env.example dentro del proyecto como .env
3. Desde la ruta del proyecto, ****php artisan key:generate**
4. **npm install**, para instalar todas las dependencias de frontend necesarias.
    - Si lanza un error: **Set-ExecutionPolicy RemoteSigned -Scope CurrentUser**
5. Ejecutar XAMPP → MySQL y Apache
6. **php artisan migrate --seed**
7. **php artisan serve**, en una terminal
8. **php artisan queue:listen**, en otra terminal
9. **npm run dev**, en otra terminal
