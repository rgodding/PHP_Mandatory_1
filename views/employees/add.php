<?php
require_once '../../initialise.php';
$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/Department.php';

$departmentDb = new Department();
$employeeDb = new Employee();

$departments = $departmentDb->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if ($action === 'add') {
            $firstName = $_POST['firstName'] ?? null;
            $lastName = $_POST['lastName'] ?? null;
            $email = $_POST['email'] ?? null;
            $birth = $_POST['birth'] ?? null;
            $departmentId = $_POST['departmentId'] ?? null;
            
            $result = $employeeDb->add($firstName, $lastName, $email, $birth, $departmentId);
            if ($result) {
                echo "<p class='success'>Employee added successfully.</p>";
                header('Location: index.php');
                exit;
            } else {
                throw new Exception('Failed to add employee.');
            }      
        } else {
            throw new Exception('Invalid action.');
        }   
    } catch (Exception $e) {
        echo "<p class='error'>Error: {$e->getMessage()}</p>";
    }
}

echo <<<HTML
<h1>Add Employee</h1>
<form action="add.php" method="POST">
    <div>
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName" required>
    </div>
    <div>
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
    </div>
    <div>
        <label for="birth">Birth Date</label>
        <input type="date" name="birth" id="birth" required>
    </div>
    <div>
        <label for="departmentId">Department</label>
        <select name="departmentId" id="departmentId">
HTML;
foreach ($departments as $department) {
    echo <<<HTML
            <option value="{$department['departmentId']}">{$department['name']}</option>
    HTML;
}
echo <<<HTML
        </select>
    </div>
    <input type="hidden" name="action" value="add">
    <button type="submit">Add Employee</button>
</form>
HTML;
include_once ROOT_PATH . '/public/footer.php';