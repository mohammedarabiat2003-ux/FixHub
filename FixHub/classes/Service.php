<?php

class Service
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NULL
             ORDER BY id DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDeleted()
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NOT NULL
             ORDER BY deleted_at DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopular($limit = 8)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE deleted_at IS NULL
             ORDER BY id ASC
             LIMIT :limit"
        );

        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE id = :id
             AND deleted_at IS NULL
             LIMIT 1"
        );

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($name, $description, $icon, $image)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO services
            (name, description, icon, image)
            VALUES
            (:name, :description, :icon, :image)"
        );

        return $stmt->execute([
            ":name" => $name,
            ":description" => $description,
            ":icon" => $icon,
            ":image" => $image
        ]);
    }

    public function update($id, $name, $description, $icon, $image)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET name = :name,
                 description = :description,
                 icon = :icon,
                 image = :image
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":description" => $description,
            ":icon" => $icon,
            ":image" => $image
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET deleted_at = NOW()
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function restore($id)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE services
             SET deleted_at = NULL
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function permanentDelete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM services
             WHERE id = :id"
        );

        return $stmt->execute([
            ":id" => $id
        ]);
    }
}
