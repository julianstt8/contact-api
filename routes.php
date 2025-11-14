<?php

require_once __DIR__ . "/src/controllers/ContactController.php";

$controller = new ContactController();

switch (true) {

    /**
     * GET /contacts
     * Traer todos los contactos
     * Ejemplo en Postman:
     * GET http://localhost/contact-api/contacts
     */
    case $route === "contacts" && $_SERVER["REQUEST_METHOD"] === "GET":
        $controller->getAll();
        break;

    /**
     * GET /contacts/{id}
     * Traer contacto por id
     * Ejemplo en Postman:
     * GET http://localhost/contact-api/contacts/1
     */
    case preg_match("/^contacts\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "GET":
        $controller->getById($matches[1]);
        break;

    /**
     * POST /contacts
     * Crear contacto
     * Ejemplo en Postman:
     * POST http://localhost/contact-api/contacts
     * Body JSON:
     * {
     *   "nombre": "Carlos",
     *   "apellido": "Gomez",
     *   "email": "carlos@test.com",
     *   "telefonos": ["3001234567"]
     * }
     */
    case $route === "contacts" && $_SERVER["REQUEST_METHOD"] === "POST":
        $controller->create();
        break;

    /**
     * PUT /contacts/{id}
     * Actualizar contacto
     * Ejemplo en Postman:
     * PUT http://localhost/contact-api/contacts/1
     * Body JSON:
     * {
     *   "nombre": "Carlos",
     *   "apellido": "Gomez",
     *   "email": "nuevo@test.com",
     *   "telefonos": ["3009876543"]
     * }
     */
    case preg_match("/^contacts\/(\d+)$/", $route, $matches) &&
        $_SERVER["REQUEST_METHOD"] === "PUT":
        $controller->update($matches[1]);
        break;

    /**
     * DELETE /contacts/{id}
     * Eliminar contacto por id
     * Ejemplo en Postman:
     * DELETE http://localhost/contact-api/contacts/1
     */
    case preg_match("/^contacts\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "DELETE":
        $controller->destroy($matches[1]);
        break;

    /**
     * POST /contacts/{id}/phones
     * Añadir teléfono a contacto
     * Ejemplo en Postman:
     * POST http://localhost/contact-api/contacts/1/phones
     * Body JSON:
     * { "numero": "3009876543" }
     */
    case preg_match("/^contacts\/(\d+)\/phones$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "POST":
        $controller->addPhone($matches[1]);
        break;

    /**
     * DELETE /contacts/{id}/phones/{phoneId}
     * Eliminar teléfono de un contacto
     * Ejemplo en Postman:
     * DELETE http://localhost/contact-api/contacts/1/phones/5
     */
    case preg_match("/^contacts\/(\d+)\/phones\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "DELETE":
        $controller->deletePhone($matches[1], $matches[2]);
        break;

    /**
     * Ruta no encontrada
     */
    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}
