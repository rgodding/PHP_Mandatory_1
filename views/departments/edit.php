<?php
require_once '../../initialise.php';
$pageTitle = 'Departments';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Department.php';
require_once ROOT_PATH . '/classes/Employee.php';

$departmentId = $_GET['id'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if($action==='update'){
            $result = (new Department())->update($departmentId, 
                $_POST['name']
            );
            if($result){
                // Echo success message for 3 seconds
                echo '<p class="success-message">Department updated successfully.</p>';
                header("refresh:3;url=edit.php?id=" . $departmentId);
            } else {
                echo 'Failed to update department.';
            }
        } elseif ($action==='remove_employee'){
            $employeeId = $_POST['employeeId'] ?? null;
            if($employeeId){
                $result = (new Employee())->removeFromDepartment($employeeId);
                if($result){
                    // Echo success message for 3 seconds
                    echo '<p class="success-message">Employee removed successfully.</p>';
                    header("refresh:3;url=edit.php?id=" . $departmentId);
                } else {
                    echo 'Failed to remove employee.';
                }
            } else {
                echo 'No employee ID provided.';
            }
        } elseif ($action==='add_employee'){
            $employeeId = $_POST['employeeId'] ?? null;
            if($employeeId){
                $result = (new Employee())->addEmployeeToDepartment($employeeId, $departmentId);
                if($result){
                    // Echo success message for 3 seconds
                    echo '<p class="success-message">Employee added successfully.</p>';
                    header("refresh:3;url=edit.php?id=" . $departmentId);
                } else {
                    echo 'Failed to add employee.';
                }
            } else {
                echo 'No employee ID provided.';
            }
        } elseif ($action==='delete'){
            $result = (new Department())->delete($departmentId);
            if($result){
                // Echo success message for 3 seconds
                echo '<p class="success-message">Department deleted successfully.</p>';
                header("refresh:3;url=index.php");
            } else {
                echo 'Failed to delete department.';
            }
        }
        else {
            echo 'Invalid action.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
    }
}

if (!$departmentId) {
    echo 'No department ID provided.';
    exit;
}
$department = (new Department())->getById($departmentId);

if (!$department) {
    echo 'Department not found.' . ' ID: ' . $departmentId;
    exit;
}

$employees = (new Employee())->getByDepartmentId($departmentId);
$unassignedEmployees = (new Employee())->getUnassignedEmployees();

echo <<<HTML
<h1>Edit Department</h1>
<form method="POST" action="edit.php?id={$department['departmentId']}">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{$department['name']}" required>
    <input type="hidden" name="action" value="update">
    <button type="submit">Update</button>
</form>
<h2>Employees in this Department</h2>
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
HTML;
foreach ($employees as $employee) {
    echo <<<HTML
            <tr class="data-menu-row">
                <td>{$employee['employeeId']}</td>
                <td>{$employee['firstName']}</td>
                <td>{$employee['lastName']}</td>
                <td>
                    <form method="POST" action="edit.php?id={$department['departmentId']}">
                        <input type="hidden" name="action" value="remove_employee">
                        <input type="hidden" name="employeeId" value="{$employee['employeeId']}">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
    HTML;
}
echo <<<HTML
    </tbody>
</table>
<h2>Add Unassigned Employee To Department</h2>
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
HTML;
foreach ($unassignedEmployees as $employee) {
    echo <<<HTML
            <tr class="data-menu-row">
                <td>{$employee['employeeId']}</td>
                <td>{$employee['firstName']}</td>
                <td>{$employee['lastName']}</td>
                <td>
                    <form method="POST" action="edit.php?id={$department['departmentId']}">
                        <input type="hidden" name="action" value="add_employee">
                        <input type="hidden" name="employeeId" value="{$employee['employeeId']}">
                        <button type="submit">Add</button>
                    </form>
                </td>
            </tr>
    HTML;
}
echo <<<HTML
    </tbody>
</table>
<h3>Delete Department</h3>
<form method="POST" action="edit.php?id={$department['departmentId']}">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="departmentId" value="{$department['departmentId']}">
    <button type="submit">Delete</button>
</form>
HTML;
include_once ROOT_PATH . '/public/footer.php';
?>