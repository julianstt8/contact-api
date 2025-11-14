<?php

require_once __DIR__ . "/src/controllers/ContactController.php";

$controller = new ContactController();

switch (true) {

    /**
     * Traer todos los contactos
     */
    case $route === "contacts" && $_SERVER["REQUEST_METHOD"] === "GET":
        $controller->getAll();
        break;

    /**
     * Traer contacto por id
     */
    case preg_match("/^contacts\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "GET":
        $controller->getById($matches[1]);
        break;

    /**
     * Crear contacto
     */
    case $route === "contacts" && $_SERVER["REQUEST_METHOD"] === "POST":
        $controller->create();
        break;

    case preg_match("/^contacts\/(\d+)$/", $route, $matches) &&
        $_SERVER["REQUEST_METHOD"] === "PUT":
        $controller->update($matches[1]);
        break;

    /**
     * Eliminar contacto por id
     */
    case preg_match("/^contacts\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "DELETE":
        $controller->destroy($matches[1]);
        break;

    /**
     * Añadir telefono a contacto
     */
    case preg_match("/^contacts\/(\d+)\/phones$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "POST":
        $controller->addPhone($matches[1]);
        break;

    /**
     * Eliminar telefono a un contacto segun el id del contacto y el id del telefono (/contacts/{id}/phones/{phoneId})
     */
    case preg_match("/^contacts\/(\d+)\/phones\/(\d+)$/", $route, $matches)
        && $_SERVER["REQUEST_METHOD"] === "DELETE":
        $controller->deletePhone($matches[1], $matches[2]);
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}
