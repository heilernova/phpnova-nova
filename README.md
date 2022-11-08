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

# Rutas
Las rutas las podremos definir en el archivo app.router.php, el cual se encuentra alojado normalmente en la carpeta "src" de la raiz del projecto, para definirlas utilizaremos a clase `Phpnova\Nova\Router\Route` la cual contiene métodos estaticos las métodos HTTP para acceso a la API

## Ejemplos de rutas
```php
use Phpnova\Nova\Router\Route;

Route::get('', fn($req) => 'Hola mundo');
Route::post('', fn($req) => 'Hola mundo POST');
Route::put('', fn($req) => 'Hola mundo PUT');

```

## Rutas con parametros
Para enviar parametros en la ruta de debe inicioa con ":" seguido del nombre que asignado al parametro.
```php
use Phpnova\Nova\Router\Route;
use Phpnova\Nova\Http\Request;

Route::get('saludar/:name', function(Request $req){
    $name = $req->params['name'];
    return "Hola $name";
});
```

### Parametros de consulta
Los parametros de consulta son los enviados por URL mediante "?": www.midominio.com`?nombre_parametro=valor`

````php
use Phpnova\Nova\Router\Route;
use Phpnova\Nova\Http\Request;

# RUTA: perosnas?sexo=M
Route::get('personas', function(Request $req){
    $sex = $req->queryParams['sex'];
    return $sex;
});
````

## Middlwares
Los meddleware los vamos ha defirnor mediante el método Route::use, pasandole una función la cual retorna null para dejar continuar
```php
use Phpnova\Nova\Router\Route;
use Phpnova\Nova\Http\Request;
use Phpnova\Nova\Http\res;

Route::use(function(){
    if (1 == 1) return res::json(['message' => 'Sin acceso'], 401);
});

# RUTA: perosnas?sexo=M
Route::get('personas', function(Request $req){
    $sex = $req->queryParams['sex'];
    return $sex;
});
```