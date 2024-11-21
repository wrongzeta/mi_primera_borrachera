# Guía de Instalación y Ejecución

Este documento proporciona una guía detallada sobre cómo instalar y ejecutar el sistema de gestión "Mi Primera Borrachera" en cualquier computadora. A continuación, se explican los pasos necesarios para poner en marcha el sistema de forma local.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes programas en tu computadora:

- **PHP** (Versión 7.4 o superior)
- **MySQL** o **MariaDB** (para la base de datos)
- **XAMPP** o **WAMP** (para ejecutar PHP y MySQL en tu máquina local)
- Un editor de texto como **Visual Studio Code**, **Sublime Text**, o **Notepad++**

## Instalación

### 1. Clonación del Repositorio

Para comenzar, clona este repositorio en tu máquina local usando Git:

```bash
git clone https://github.com/wrongzeta/mi_primera_borrachera.git
```

### 2. Configuración del Entorno Local
Instalar XAMPP (o cualquier servidor local que soporte PHP y MySQL) desde aquí.
Una vez instalado XAMPP, abre el panel de control y asegúrate de iniciar los servicios de Apache y MySQL.
Copia la carpeta del proyecto mi_primera_borrachera a la carpeta htdocs dentro de la instalación de XAMPP. Usualmente se encuentra en C:\xampp\htdocs\.
### 3. Configuración de la Base de Datos
Abre tu navegador y ve a 
###
http://localhost/phpmyadmin/.
Crea una nueva base de datos llamada mi_primera_borrachera.
Importa los archivos SQL (si los tienes) desde el panel de phpMyAdmin para crear las tablas necesarias.
### 4. Configuración del Archivo de Base de Datos
Si utilizas un archivo de configuración para la base de datos (config.php o similar), asegúrate de configurar los siguientes datos:

#### php
    $host = 'localhost'; // Dirección del servidor de base de datos
    $dbname = 'mi_primera_borrachera'; // Nombre de la base de datos
    $username = 'root'; // Usuario por defecto de MySQL en XAMPP
    $password = ''; // Contraseña, que generalmente es vacía en XAMPP

### 5. Acceso al Sistema
Una vez configurado todo lo anterior, abre tu navegador y ve a la siguiente URL:
####
    http://localhost/mi_primera_borrachera/
Ahí podrás acceder al sistema.

### Uso del Sistema
El sistema permite gestionar el funcionamiento de un bar nocturno con tres roles principales:

Mesero: Puede tomar pedidos, asignar mesas y ver el inventario.
Cajero: Puede cerrar pedidos, gestionar inventario y generar reportes.
Administrador: Tiene acceso total para gestionar usuarios, parametrizar el sistema y ver todos los reportes de las sedes.
