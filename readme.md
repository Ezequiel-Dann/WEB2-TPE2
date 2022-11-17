## Esta api permite gestionar los equipos y grupos del mundial Qatar 2022

### Estructura de los datos

EQUIPOS
    
    {
        id_equipo: int,
        pais: string,
        puntos: int,
        pj: int,
        pg: int,
        pe: int,
        pp: int,
        gf: int,
        gc: int,
        dif: int,
        fk_id_grupo: int
    }

GRUPOS

    {
        id_grupo:int,
        nombre: string,
        finalizado: boolean
    }


#    ENDPOINTS

## LISTAR TODOS LOS EQUIPOS

### GET api/equipos/

Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0

Los parametros opcionales que acepta son:

* **Grupo**: filtra los equipos por id de grupo, tiene que ser numero (id) 
* **Sort**: ordena por la columna especificada :
    * Pais, Puntos, PJ (partidos jugados), PG (partidos ganados), PE (partidos empatados), PP (partidos perdidos), GF (goles a favor), GC (goles en contra), Dif
* **Order**: la direccion en la que se ordenan los registros. Tiene que ser asc o desc, si order esta presente pero sort no, el orden se hace de la siguiente manera:
    
    1. Puntos,
    2. Partidos ganados (PG),
    3. Partidos jugados (PJ). Los partidos jugados se ordenan de forma opuesta al resto,
        porque cuanto mas partidos jugados tenga mas abajo es su posicion en la tabla.
    4. Poles a favor (GF),
    5. Diferencia de goles (Dif)

* **Limit**: cantidad de equipos en la respuesta. Tiene que ser un numero mayor estricto que 0 y menor igual a 10.
* **Offset**: cantidad de equipos a ignorar antes de empezar a obtener registros. Tiene que ser un numero mayor o igual a 0 y es obligacion tener un limit.


Si la consulta es correcta este endpoint genera un arreglo de json con los datos de cada equipo,


### Ejemplos
> GET /api/equipos

    Respuesta

    Code: 200
    Content:
    [
        {
            id_equipo: int,
            pais: string,
            puntos: int,
            pj: int,
            pg: int,
            pe: int,
            pp: int,
            gf: int,
            gc: int,
            dif: int,
            grupo: string
        },
        {
            id_equipo: int,
            pais: string,
            puntos: int,
            pj: int,
            pg: int,
            pe: int,
            pp: int,
            gf: int,
            gc: int,
            dif: int,
            grupo: string
        },
        {
            id_equipo: int,
            pais: string,
            puntos: int,
            pj: int,
            pg: int,
            pe: int,
            pp: int,
            gf: int,
            gc: int,
            dif: int,
            grupo: string
        }
    ]
> GET api/equipos?sort=asd&order=no&grupo=2
    
    Respuesta

    Code: 400
    Content:
    {
        sort: asd
        order: no
    }

La respuesta contiene pares clave valor con los parametros invalidos

> GET /api/equipos?sort=pais&order=DESC&limit=2&offset=1&grupo=7 

Obtiene 2 equipos apartir de la segunda posicion de el grupo 7, ordenado por pais de forma descendente
> GET /api/equipos?grupo=5&order=desc 

Obtiene los equipos del grupo 5 ordenados como en una tabla de puntuacion

## OBTENER UN EQUIPO POR ID
### GET api/equipos/:ID

Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.
:ID debe ser un valor numerico.
Si la consulta es correcta este endpoint genera un json con los datos de el equipo.

### Ejemplo
> GET /api/equipos/7

    Respuesta
    Code: 200
    Content:
    {
        id_equipo: 7.
        pais: string,
        puntos: int,
        pj: int,
        pg: int,
        pe: int,
        pp: int,
        gf: int,
        gc: int,
        dif: int,
        grupo: string.
    }



## AGREGAR UN EQUIPO
### POST api/equipos/

Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0 con permisos de administrador.
Este endpoint recibe la informacion que biene en el body, en formato json con las siguientes columnas:
Si esta accion se realiza correctamente devuelve el id del nuevo equipo ingresado.

### Ejemplo

>POST api/equipos

    Request

    Body:
    {
        "pais": "argentina",
        "puntos": 3,
        "pj": 4,
        "pg": 1,
        "pe": 2,
        "pp": 0,
        "gf": 2,
        "gc": 1,
        "dif": 2,
        "fk_id_grupo": "7"
    }

    Response

    Code: 201
    Content:
    {
        21
    }

<!-- separar -->

>POST api/equipos

    Request

    Body:
    {
        'pais': string,
        'puntos': int,
        'pj': int,
        'pg': int,
        'pe': int,
        'pp': int,
        'gf': int,
        'gc': int,
        'diferencia': int,
        'fk_id_grupo': int
    }

    Response
    Code: 400
    Content:
    {
        Datos invalidos
    }

> No se reconoce el campo "diferencia"

## BORRAR UN EQUIPO
### DELETE api/equipos/:ID

Para utilizar este endpoint se necesita un token de auntenticacion OAuth2.0 con permisos de administrador.
Este endpoint elimina el equipo con el id especificado.

### Ejemplo
> Delete api/equipos/7


## LISTAR LOS GRUPOS
### GET api/grupos/

Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.

Si la consulta se realiza con exito devuelve un arreglo de json con todos los grupos y su informacion.

> /api/grupos/
    Code: 200
    Respuesta

    [
        {
            'id_grupo': int,
            'nombre': string,
            'finalizado': boolean (0/1)
        },
        {
            'id_grupo': int,
            'nombre': string,
            'finalizado': boolean (0/1)
        }
    ]

### GET api/grupos/:ID

Para utilizar este endpoint se necesita un token de auntenticacion OAuth2.0.
Lista la informacion del grupo con ese id.

Si la consulta se realiza con exito devuelve un json de el grupo y su informacion.

### EJEMPLO
> api/grupos/3
    
    Code: 200
    Respuesta


    {
        'id_grupo': 3,
        'nombre': string,
        'finalizado': boolean (0/1)
    }


## GET /auth/token

Este endpoint requiere un header con el par email:contrasenia en base 64
### Ejemplo

    Headers: 
    {
        'Authorization': 'Basic d2ViMjoxMjM0NTY='
    }

Cuentas para generar token:

Admin

email: admin@admin.com 
contraseña: admin123

Usuario sin permisos

email: user@user.com 
contraseña: user123

# AUTORIZACION


El token de autorizacion debe ser incluido en un header Autorization con la palabra Bearer separada del token con un espacio

### EJEMPLO

    Headers:
    {
        'Authorization': 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImFkbWluQGFkbWluLmNvbSIsImFkbWluIjoxLCJleHAiOjE2Njg3MzA5ODJ9.zg4PBzc-1d3rjH7rsnWxrH5bLZDUNrIUmEVAMrwcWS4'
    }