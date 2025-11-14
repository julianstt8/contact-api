<?php

require_once __DIR__ . "/../core/database.php";
require_once __DIR__ . "/../models/Contact.php";
require_once __DIR__ . "/../models/Phone.php";

class ContactController
{
    private $contact;
    private $phone;
    public function __construct()
    {
        $db = (new Database())->connect();
        $this->contact = new Contact($db);
        $this->phone = new Phone($db);
    }

    /**
     * Traer todos los contactos
     */
    public function getAll()
    {
        echo json_encode($this->contact->getAll());
    }

    /**
     * Traer contacto segun el id
     */
    public function getById($id)
    {
        $contact = $this->contact->getById($id);

        if (!$contact) {
            http_response_code(404);
            echo json_encode(["error" => "Contacto no encontrado"]);
            return;
        }

        echo json_encode($contact);
    }

    /**
     * Añadir contacto
     */
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validación 
        if (empty($data["nombre"]) || empty($data["apellido"]) || empty($data["email"])) {
            http_response_code(400);
            echo json_encode(["error" => "Nombre, apellido y email son obligatorios"]);
            return;
        }

        $id = $this->contact->create($data["nombre"], $data["apellido"], $data["email"]);

        // Agregar teléfonos
        if (!empty($data["telefonos"]) && is_array($data["telefonos"])) {
            $this->phone->addPhones($id, $data["telefonos"]);
        }

        echo json_encode(["message" => "Contacto creado", "id" => $id]);
    }

    /**
     * Actualizar contacto
     */
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            http_response_code(400);
            echo json_encode(["error" => "No se recibieron datos"]);
            return;
        }

        // Verificar si el contacto existe
        if (!$this->contact->exists($id)) {
            http_response_code(404);
            echo json_encode(["error" => "Contacto no encontrado"]);
            return;
        }

        $contactData = [
            "nombre" => $data["nombre"] ?? null,
            "apellido" => $data["apellido"] ?? null,
            "email" => $data["email"] ?? null,
            "telefonos" => $data["telefonos"] ?? [],
        ];

        // Validar al menos un dato básico o teléfonos
        if (
            !$contactData["nombre"] &&
            !$contactData["apellido"] &&
            !$contactData["email"] &&
            empty($contactData["telefonos"])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "No hay información para actualizar"]);
            return;
        }

        $conn = $this->contact->getConnection();

        try {
            $conn->beginTransaction();

            // Actualizar datos principales si vienen
            $updatedBasic = false;
            if ($contactData["nombre"] || $contactData["apellido"] || $contactData["email"]) {
                $updatedBasic = $this->contact->update(
                    $id,
                    $contactData["nombre"] ?? "",
                    $contactData["apellido"] ?? "",
                    $contactData["email"] ?? ""
                );
            }

            // Actualizar teléfonos si vienen
            $updatedPhones = false;
            if (!empty($contactData["telefonos"])) {
                // Borrar teléfonos antiguos
                $stmtDel = $conn->prepare("DELETE FROM phones WHERE contact_id = ?");
                $stmtDel->execute([$id]);

                // Insertar nuevos
                $stmtIns = $conn->prepare("INSERT INTO phones (contact_id, telefono) VALUES (?, ?)");
                foreach ($contactData["telefonos"] as $telefono) {
                    $stmtIns->execute([$id, $telefono]);
                }
                $updatedPhones = true;
            }

            $conn->commit();

            if ($updatedBasic || $updatedPhones) {
                echo json_encode(["message" => "Contacto actualizado correctamente"]);
            } else {
                echo json_encode(["message" => "No hubo cambios que actualizar"]);
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar contacto", "details" => $e->getMessage()]);
        }
    }

    /**
     * Eliminar contacto
     */
    public function destroy($id)
    {
        $this->contact->delete($id);
        echo json_encode(["message" => "Contacto eliminado"]);
    }

    /**
     * Añadir telefono a un contacto
     */
    public function addPhone($contactId)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data["telefono"])) {
            http_response_code(400);
            echo json_encode(["error" => "El número de teléfono es obligatorio"]);
            return;
        }
        $result = $this->phone->addPhones($contactId, [$data["telefono"]]);
        if (!empty($result["duplicates"])) {
            http_response_code(409);
            echo json_encode([
                "error" => "El teléfono ya existe para este contacto",
                "telefono" => $result["duplicates"][0]
            ]);
            return;
        }
        echo json_encode([
            "message" => "Teléfono agregado correctamente",
            "telefono" => $result["added"][0]
        ]);
    }

    /**
     * Eliminar telefono a un contacto
     */
    public function deletePhone($contactId, $phoneId)
    {
        $deleted = $this->phone->deletePhone($contactId, $phoneId);

        if (!$deleted) {
            http_response_code(404);
            echo json_encode(["error" => "Teléfono no encontrado"]);
            return;
        }

        echo json_encode(["message" => "Teléfono eliminado"]);
    }
}
