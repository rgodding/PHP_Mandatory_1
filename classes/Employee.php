<?php
require_once 'Database.php';

/*
firstName, lastName, email, birth, departmentID
*/
class Employee extends Database
{
    function getAll(): array|false
    {
        $sql = <<<SQL
        SELECT employeeId, firstName, lastName, email, birth, departmentId
        FROM employee
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

    function getById(int $employeeId): array|false
    {
        $sql = <<<SQL
        SELECT employeeId, firstName, lastName, email, birth, departmentId
        FROM employee
        WHERE employeeId = :employeeId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
            // No employee found
            return false;
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function add(string $firstName, string $lastName, string $email, string $birth, string $departmentId): bool {
        $sql = <<<SQL
        INSERT INTO employee (firstName, lastName, email, birth, departmentId)
        VALUES (:firstName, :lastName, :email, :birth, :departmentId)
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':firstName', $firstName);
            $stmt->bindValue(':lastName', $lastName);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':birth', $birth);
            $stmt->bindValue(':departmentId', $departmentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function update(int $employeeId, string $firstName, string $lastName, string $email, string $birth, int $departmentId): bool 
    {
        $sql = <<<SQL
        UPDATE employee
        SET firstName = :firstName, lastName = :lastName, email = :email, birth = :birth, departmentId = :departmentId
        WHERE employeeId = :employeeId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->bindValue(':firstName', $firstName);
            $stmt->bindValue(':lastName', $lastName);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':birth', $birth);
            $stmt->bindValue(':departmentId', $departmentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function delete(int $employeeId): bool
    {
        $sql = <<<SQL
        DELETE FROM employee
        WHERE employeeId = :employeeId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function getByDepartmentId(int $departmentId): array|false
    {
        $sql = <<<SQL
        SELECT employeeId, firstName, lastName, email, birth, departmentId
        FROM employee
        WHERE departmentId = :departmentId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':departmentId', $departmentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function getUnassignedEmployees(): array|false
    {
        $sql = <<<SQL
        SELECT employeeId, firstName, lastName, email, birth, departmentId
        FROM employee
        WHERE departmentId IS NULL
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

    function addEmployeeToDepartment(int $employeeId, int $departmentId): bool
    {
        $sql = <<<SQL
        UPDATE employee
        SET departmentId = :departmentId
        WHERE employeeId = :employeeId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->bindValue(':departmentId', $departmentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    function removeFromDepartment(int $employeeId): bool
    {
        $sql = <<<SQL
        UPDATE employee
        SET departmentId = NULL
        WHERE employeeId = :employeeId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>