<?php

class Contact
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function getAll()
    {
        $query = "
        SELECT c.id, c.nombre, c.apellido, c.email,
               GROUP_CONCAT(p.telefono) AS telefonos
        FROM contacts c
        LEFT JOIN phones p ON c.id = p.contact_id
        GROUP BY c.id
        ";

        $stmt = $this->conn->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as &$row) {
            if (!empty($row["telefonos"])) {
                $row["telefonos"] = explode(",", $row["telefonos"]);
            } else {
                $row["telefonos"] = [];
            }
        }

        return $results;
    }

    public function getById($id)
    {
        $query = "SELECT c.id, c.nombre, c.apellido, c.email,
               GROUP_CONCAT(p.telefono) AS telefonos
        FROM contacts c
        LEFT JOIN phones p ON c.id = p.contact_id
        WHERE c.id = ?
        GROUP BY c.id
        LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contact) {
            return null;
        }
        $contact["telefonos"] = $contact["telefonos"]
            ? explode(",", $contact["telefonos"])
            : [];

        return $contact;
    }

    public function create($nombre, $apellido, $email)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO contacts (nombre, apellido, email) VALUES (?, ?, ?)"
        );
        $stmt->execute([$nombre, $apellido, $email]);
        return $this->conn->lastInsertId();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $nombre, $apellido, $email)
    {
        $stmt = $this->conn->prepare("
        UPDATE contacts 
        SET nombre = ?, apellido = ?, email = ?
        WHERE id = ?");

        $stmt->execute([$nombre, $apellido, $email, $id]);

        return $stmt->rowCount() > 0;
    }

    public function exists($id): bool
    {
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
