<?php

class Phone
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addPhones($contactId, $phones)
    {
        $checkStmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM phones WHERE contact_id = ? AND telefono = ?"
        );
        $insertStmt = $this->conn->prepare(
            "INSERT INTO phones (contact_id, telefono) VALUES (?, ?)"
        );
        $added = [];
        $duplicates = [];
        foreach ($phones as $tel) {
            $checkStmt->execute([$contactId, $tel]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                $duplicates[] = $tel;
                continue;
            }
            $insertStmt->execute([$contactId, $tel]);
            $added[] = $tel;
        }
        return [
            'added' => $added,
            'duplicates' => $duplicates
        ];
    }


    public function deletePhone($contactId, $phoneId)
    {
        $stmt = $this->conn->prepare("
        DELETE FROM phones 
        WHERE id = ? AND contact_id = ?
    ");
        $stmt->execute([$phoneId, $contactId]);
        return $stmt->rowCount() > 0;
    }
}
