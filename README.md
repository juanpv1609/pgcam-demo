# [PG-CAM]

[PG-CAM] es una plataforma para la gestion de camas e indicadores de calidad destinada a instituciones de salud, desarrollado como proyecto de titulacion de la carrera de ingenieria en sistemas en el periodo 56 de la [Universidad Politecnica Salesiana](https://www.ups.edu.ec/) EC.

## Vista Previa

## Vista Previa 🚀

[![SB Admin 2 Preview](https://startbootstrap.com/assets/img/screenshots/themes/sb-admin-2.png)](https://blackrockdigital.github.io/startbootstrap-sb-admin-2/)

**[Launch Live Preview](https://blackrockdigital.github.io/startbootstrap-sb-admin-2/)**

## Estado

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/BlackrockDigital/startbootstrap-sb-admin-2/master/LICENSE)
[![npm version](https://img.shields.io/npm/v/startbootstrap-sb-admin-2.svg)](https://www.npmjs.com/package/startbootstrap-sb-admin-2)
[![Build Status](https://travis-ci.org/BlackrockDigital/startbootstrap-sb-admin-2.svg?branch=master)](https://travis-ci.org/BlackrockDigital/startbootstrap-sb-admin-2)
[![dependencies Status](https://david-dm.org/BlackrockDigital/startbootstrap-sb-admin-2/status.svg)](https://david-dm.org/BlackrockDigital/startbootstrap-sb-admin-2)
[![devDependencies Status](https://david-dm.org/BlackrockDigital/startbootstrap-sb-admin-2/dev-status.svg)](https://david-dm.org/BlackrockDigital/startbootstrap-sb-admin-2?type=dev)

## Pre Requisitos

Configuracion incial del ambiente de soporte para el sistema PG-CAM:

- Contar con un servidor http (XAMPP, WAMP, Apache)
- Descargar libreria [Zend Framework v1.12](https://framework.zend.com/downloads/archives)
- Descomprimir la descarga y ubicar en C:
- Configurar el archivo php.ini ubicado en C:/xampp/php/php.ini
- Agregar la linea: `include_path = ".;c:\Zend\library"` "Agrega la libreria por defecto de Zend"
- Descomentar la linea: `extension=php_pdo_pgsql.dll` "Habilita las conecciones a bases de datos PostgreSQL"
- Configurar el archivo http.conf ubicado en C:/xampp/apache/conf/httpd.conf
- Cambiar las lineas `AllowOverride none` por `AllowOverride All` "Habilita el acceso a las rutas"
## Pre Requisitos Windows 📋
Configuracion incial del ambiente para el sistema PG-CAM:
-  Instalar Git, NodeJS.
-  Contar con un servidor http (XAMPP, WAMP, Apache)
-  Descargar la libreria [Zend Framework v1.12](https://framework.zend.com/downloads/archives)
-  Descomprimir la libreria descargada y ubicar en C:
-  Configurar el archivo php.ini ubicado en C:/xampp/php/php.ini
-  Agregar la linea: `include_path = ".;c:\Zend\library"` "Agrega la libreria por defecto de Zend para todos los proyectos"
-  Descomentar la linea: `extension=php_pdo_pgsql.dll`    "Habilita las conecciones a bases de datos PostgreSQL"
-  Configurar el archivo http.conf ubicado en C:/xampp/apache/conf/httpd.conf  
-  Cambiar las lineas `AllowOverride none` por `AllowOverride All` "Habilita el acceso a las rutas"

## Descarga e instalacion 🔧

Para comenzar a usar esta aplicacion, elija una de las siguientes opciones para comenzar:

- Clonar el repositorio: `git clone https://github.com/juanpv1609/zend.git`
- [Fork, Clonar, o Descargar desde GitHub](https://github.com/juanpv1609/zend)
- Ubicarlo dentro de sus servidos de aplicaciones por ejemplo: C:/xampp/htdocs/zend
-   Clonar el repositorio: `git clone https://github.com/juanpv1609/zend.git`
-   [Fork, Clonar, o Descargar desde GitHub](https://github.com/juanpv1609/zend)
-   Ubicarlo dentro de sus servidor de aplicaciones por ejemplo: C:/xampp/htdocs/


## Uso

Después de la instalación, y ubicado dentro del proyecto ejecute `npm install` y luego ejecute`npm start`, que abrirá una vista previa de la plantilla en su navegador predeterminado, estará atento a los cambios en los archivos centrales de la plantilla y volverá a cargar el navegador cuando se guarden los cambios. Puede ver el `gulpfile.js` para ver qué tareas se incluyen con el entorno de desarrollo.

### Tareas Gulp

- `gulp` la tarea predeterminada que construye todo
- `gulp watch` browserSync abre el proyecto en su navegador predeterminado y se vuelve a cargar en vivo cuando se realizan cambios
- `gulp css` compila archivos SCSS en CSS y minimiza el CSS compilado
- `gulp js` minimiza los archivos JS de temas
- `gulp vendor` copia las dependencias de node_modules en el directorio de proveedores

Debe tener npm instalado globalmente para usar este entorno de compilación. Este tema se creó con node v11.6.0 y la CLI de Gulp v2.0.1. Si Gulp no se ejecuta correctamente después de ejecutar `npm install`, es posible que deba actualizar el nodo y / o la CLI de Gulp localmente.

## About

PG-CAM fue desarrollado bajo el uso de software libre, con la opcion de ser utilizado en multiplataforma, tiene una arquitectura MVC de la version 1.12 de Zend Framework. Incluye herramientas para el Front-End como [Bootstrap](http://getbootstrap.com/), JQUERY, POPPER, SASS.

PG-CAM fue creado por y es mantenido por **[Universidad Politecnica Salesiana](https://www.ups.edu.ec/)**.

## Copyright and License
## Despliegue 📦

_Agrega notas adicionales sobre como hacer deploy_

## Construido con 🛠️

PG-CAM fue desarrollado bajo el uso de software libre, con la opcion de ser utilizado en multiplataforma, tiene una arquitectura MVC de la version 1.12 de Zend Framework. Incluye herramientas para el Front-End como [Bootstrap](http://getbootstrap.com/), JQUERY, POPPER, SASS.

* [Dropwizard](http://www.dropwizard.io/1.0.2/docs/) - El framework web usado
* [Maven](https://maven.apache.org/) - Manejador de dependencias
* [ROME](https://rometools.github.io/rome/) - Usado para generar RSS


## Autores ✒️

PG-CAM fue creado por y es mantenido por **[Universidad Politecnica Salesiana](https://www.ups.edu.ec/)**.


## Licencia 📄

Copyright 2019 Universidad Politecnica Salesiana. Código publicado bajo el [MIT](https://github.com/juanpv1609/zend/blob/master/LICENSE) license.
