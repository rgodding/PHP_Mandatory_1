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

    function add(string $firstName, string $lastName, string $email, string $birth, string $departmentId): bool
    {
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

    // Department related methods
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

    // Project related methods
    function getByProjectId(int $projectId): array|false
    {
        $sql = <<<SQL
        SELECT e.employeeId, e.firstName, e.lastName
        FROM employee e
        INNER JOIN employee_project ep ON e.employeeId = ep.employeeId
        WHERE ep.projectId = :projectId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    function getProjectlessEmployees(int $projectId): array|false
    {
        $sql = <<<SQL
        SELECT e.employeeId, e.firstName, e.lastName
        FROM employee e
        LEFT JOIN employee_project ep ON e.employeeId = ep.employeeId AND ep.projectId = :projectId
        WHERE ep.projectId IS NULL
        SQL;

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['projectId' => $projectId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    function addEmployeeToProject(int $employeeId, int $projectId): bool
    {
        $sql = <<<SQL
        INSERT INTO employee_project (employeeId, projectId)
        VALUES (:employeeId, :projectId)
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    function removeFromProject(int $employeeId, int $projectId): bool
    {
        $sql = <<<SQL
        DELETE FROM employee_project
        WHERE employeeId = :employeeId AND projectId = :projectId
        SQL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
