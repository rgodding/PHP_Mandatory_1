<?php
require_once 'Database.php';
/*
name
*/
class Project extends Database
{
    function getAll(): array|false
    {
        $sql = <<<SQL
        SELECT projectId, name
        FROM project
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function getById(int $projectId): array|false
    {
        $sql = <<<SQL
        SELECT projectId, name
        FROM project
        WHERE projectId = :projectId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function add(string $name): bool {
        $sql = <<<SQL
        INSERT INTO project (name)
        VALUES (:name)
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function update(int $projectId, string $name): bool
    {
        $sql = <<<SQL
        UPDATE project
        SET name = :name
        WHERE projectId = :projectId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function delete(int $projectId): bool
    {
        $sql = <<<SQL
        DELETE FROM project
        WHERE projectId = :projectId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}



?>