# BACKEND (PHP nativo)

Ejercicio para Backend:
• Hacer un API REST en PHP (sin usar a un framework) para manejar una lista de contactos.
• Tenemos que poder agregar nuevos contactos (nombre, apellido, email), listar los contactos y eliminar un contacto.

Recomendación: La API tiene que seguir las buenas prácticas de arquitectura en capa para separar el acceso a los datos.
• Bonus 1 : Agregar reglas de validación para no permitir de ingresar a datos vacías
• Bonus 2: Permitir agregar uno o varios números de teléfono a cada contacto.
• La API se llama desde una herramienta como Postman

Nota: Mandar ZIP o repositorio GIT de los archivos de ambos ejercicios con el tiempo que se tomó para la realización de los mismos.

# TIEMPO SOLUCION

La solución fue desarrollada en aproximadamente 3.5 horas, incluyendo diseño de la arquitectura en capas, creación del API REST en PHP nativo, validaciones, manejo de teléfonos múltiples por contacto y pruebas en Postman.

# CARACTERISTICAS

- Listar contactos
- Obtener un contacto por ID
- Crear nuevos contactos
- Agregar teléfonos a un contacto
- Eliminar contactos
- Validación de campos requeridos
- Respuestas en formato JSON
- Rutas limpias con .htaccess

# ARQUITECTURA EN CAPAS

- routes.php
- src/controllers
- src/models
- conexión a BD (`Database.php`)

# RESPUESTA ENDPOINTS

## GET /contacts

Lista todos los contactos.
[
{
"id": 1,
"nombre": "Carlos",
"apellido": "Gomez",
"email": "carlos@test.com",
"telefonos": [
"3001234567",
"3105557788",
"3001112233"
]
},
{
"id": 2,
"nombre": "Ana",
"apellido": "Martinez",
"email": "ana@test.com",
"telefonos": [
"3001234567",
"3214448899"
]
}
]

## GET /contacts/{id}

Retorna un contacto con sus teléfonos.
{
"id": 1,
"nombre": "Carlos",
"apellido": "Gomez",
"email": "carlos@test.com",
"telefonos": [
"3001112233",
"3105557788",
"3001234567"
]
}

## POST /contacts

Inserta contactos
[
{
"id": 1,
"nombre": "Carlos",
"apellido": "Gomez",
"email": "carlos@test.com",
"telefonos": [
"3001112233"
]
},
{
"id": 2,
"nombre": "Ana",
"apellido": "Martinez",
"email": "ana@test.com",
"telefonos": [
"3214448899"
]
}
]
