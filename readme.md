Cuentas para inicio de Sesion:

Admin: 
email: admin@admin.com contraseña:admin123

Usuario sin permisos:
email: user@user.com contraseña:user123
# DOCUMENTACION API

##    ENDPOINTS

(1) listar todos los equipos ('equipos/' ,'GET'):
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.
Este endpoint funciona por GET y puede recibir o no diferentes parametros.

Los parametros que acepta por GET son:

grupo: tiene que ser numero (id), filtra los equipos por grupo.
sort: tiene que ser una columna valida de la base de datos, ordena por dicha columna.
order: tiene que ser asc o desc, la direccion en la que se ordenan los registros. Si order esta presente pero sort no, el orden se hace de la siguiente manera:
    1º puntos,
    2º partidos ganados (pg),
    3º partidos jugados (pj). los partidos jugados se ordenan de forma opuesta al resto,
        porque caunto mas partidos jugados tenga mas abajo es su posicion en la tabla,
    4º goles a favor (gf),
    5º diferencia de goles (dif).

limit: tiene que ser un numero mayor estricto que 0 y menor igual a 10, cantidad de equipos en la respuesta.
offset: tiene que ser un numero mayor o igual a 0 y es obligacion tener un limit, cantidad de equipos a ignorar antes de empezar a obtener registros.

En caso de que los parametros ingresados sean incorrectos: devuelve clave valor del parametro invalido.
Si la consulta es correcta este endpoint genera un arreglo de json con los datos de cada equipo,

    id_equipo: int.
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

Ejemplos: http://localhost/WEB2-TPE2/api/equipos/ obtiene todos los equipos
          http://localhost/WEB2-TPE2/api/equipos?sort=pais&order=DESC&limit=2&offset=1&grupo=7 obtiene 2 equipos apartir de la segunda posicion de el grupo 7, ordenado
          por pais de forma descendente 

(2) listar equipos por id ('equipos/:ID' , 'GET'):
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.
Se debe ingresar un valor numerico (id).
Si la consulta es correcta este endpoint genera un json con los datos de el equipo.

    id_equipo: int.
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

Ejemplos: http://localhost/WEB2-TPE2/api/equipos/7 obtiene el equipo con id 7.


(3) nuevo equipos ('equipos/', 'POST'):
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0 con permisos de administrador.
Este endpoint recibe la informacion que biene en el body, en formato json con las siguientes columnas:

    pais: string,
    puntos: int,
    pj: int,
    pg: int,
    pe: int,
    pp: int,
    gf: int,
    gc: int,
    dif: int,
    fk_id_grupo: int.

Si esta accion se realiza correctamente devuelve el id del nuevo equipo ingresado.

Ejemplos: http://localhost/WEB2-TPE2/api/equipos/ con body =

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


(4) borrar equipo ('equipos/:ID', 'DELETE'):
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0 con permisos de administrador.
Este endpoint elimina el equipo con el id especificado.

Ejemplos:http://localhost/WEB2-TPE2/api/equipos/7


(5) obtener grupos ('grupos/', 'GET'):
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.

Si la consulta se realiza con exito devuelve un arreglo de json con todos los grupos y su informacion.

    id_grupo: int,
    nombre: string,
    finalizado: boolean (0/1)


(6) obtener grupo por id ('grupos/:ID', 'GET')
Para utilizar este enpoint se necesita un token de auntenticacion OAuth2.0.
Lista la informacion del grupo con ese id.

Si la consulta se realiza con exito devuelve un json de el grupo y su informacion.

    id_grupo: int,
    nombre: string,
    finalizado: boolean (0/1)

Ejemplos:http://localhost/WEB2-TPE2/api/grupos/6
Responde con body = {
        "id_grupo": 6,
        "nombre": "A",
        "finalizado": 1
    }



(7) obtener token ("auth/token", 'GET'):
Este endpoint requiere un header con el par email:contrasenia en base 64
Ejemplo:http://localhost/WEB2-TPE2/api/auth/token/

headers: {
           'Authorization': 'Basic d2ViMjoxMjM0NTY='
       }




