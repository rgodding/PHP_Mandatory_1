<?php
require_once '../../initialise.php';
$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/Department.php';

$employeeId = $_GET['id'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if($action==='update'){
            $result = (new Employee())->update(
                $employeeId,
                $_POST['firstName'],
                $_POST['lastName'],
                $_POST['email'],
                $_POST['birth'],
                $_POST['departmentId']
            );
            if($result){
                // Echo success message for 3 seconds
                echo '<p class="success-message">Employee updated successfully.</p>';
                header("refresh:3;url=edit.php?id=" . $employeeId);
            } else {
                echo 'Failed to update employee.';
            }
        } elseif ($action==='delete'){
            $result = (new Employee())->delete($employeeId);
            if($result){
                // Echo success message for 3 seconds
                echo '<p class="success-message">Employee deleted successfully.</p>';
                header("refresh:3;url=index.php");
            } else {
                echo 'Failed to delete employee.';
                header("refresh:3;url=edit.php?id=" . $employeeId);
            }
        } else {
            echo 'No action provided.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

if(!$employeeId) {
    echo 'No employee ID provided.';
    exit;
}
$employee = (new Employee())->getById($employeeId);
if(!$employee) {
    echo 'No employee found.';
    exit;
}

$departments = (new Department())->getAll();

echo <<<HTML
<h1>Edit Employee</h1>
<form method="POST" action="edit.php?id={$employeeId}">
    <div class="data-form">
        <div>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="{$employee['firstName']}" required>
        </div>
        <div>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="{$employee['lastName']}" required>
        </div>    
        <div>
            <label for="birth">Birth Date:</label>
            <input type="date" id="birth" name="birth" value="{$employee['birth']}" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{$employee['email']}" required>
        </div>
        <div>
            <label for="departmentId">Department:</label>
            <select id="departmentId" name="departmentId" required>
HTML;
foreach ($departments as $department) {
    $selected = $employee['departmentId'] == $department['departmentId'] ? 'selected' : '';
    echo <<<HTML
                <option value="{$department['departmentId']}" {$selected}>{$department['name']}</option>
    HTML;
}
echo <<<HTML
            </select>
        </div>
        <div>
            <button type="submit">Update</button>
        </div>
    </div>
</form>
<h3>Delete Employee</h3>
<form method="POST" action="edit.php?id={$employeeId}">
    <input type="hidden" name="action" value="delete">
    <button type="submit">Delete</button>
</form>
HTML;
include_once ROOT_PATH . '/public/footer.php';