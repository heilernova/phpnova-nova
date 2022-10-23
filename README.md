# Phpnova\Nova

# Requerimientos

* PHP 8.0+
* Composer

# Instalación
Aun no hay versión estable
```
composer require phpnova/nova dev-main
```
Agregar los scripts al composer.json del projecto
```json
"nv": "Phpnova\\Nova\\Bin\\Console\\Scripts::run"
```
```json
"scripts": {
    "nv": "Phpnova\\Nova\\Bin\\Console\\Scripts::run"
}
```

# Comandos de consola

Instalación del entorno de trabajo
```
composer nv i
```