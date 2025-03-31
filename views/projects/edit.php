<?php
require_once '../../initialise.php';
$pageTitle = 'Projects';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Project.php';
require_once ROOT_PATH . '/classes/Employee.php';

$projectId = $_GET['id'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if($action==='update'){
            $result = (new Project())->update($projectId, 
                $_POST['name']
            );
            if($result){
                // Echo success message for 3 seconds
                echo '<p class="success-message">Project updated successfully.</p>';
                header("refresh:3;url=edit.php?id=" . $projectId);
            } else {
                echo 'Failed to update project.';
            }
        } elseif($action==='add_employee'){
            $employeeId = $_POST['employeeId'] ?? null;
            if($employeeId){
                $result = (new Employee())->addEmployeeToProject($employeeId, $projectId);
                if($result){
                    // Echo success message for 3 seconds
                    echo '<p class="success-message">Employee added successfully.</p>';
                    header("refresh:3;url=edit.php?id=" . $projectId);
                } else {
                    echo 'Failed to add employee.';
                }
            } else {
                echo 'No employee ID provided.';
            }
        } elseif ($action==='remove_employee'){
            $employeeId = $_POST['employeeId'] ?? null;
            if($employeeId){
                $result = (new Employee())->removeFromProject($employeeId, $projectId);
                if($result){
                    // Echo success message for 3 seconds
                    echo '<p class="success-message">Employee removed successfully.</p>';
                    header("refresh:3;url=edit.php?id=" . $projectId);
                } else {
                    echo 'Failed to remove employee.';
                }
            } else {
                echo 'No employee ID provided.';
            }
        } else {
            echo 'Invalid action.';
            exit;
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
    } 
}

if(!$projectId) {
    echo 'No project ID provided.';
    exit;
}
$project = (new Project())->getById($projectId);
if(!$project) {
    echo 'Project not found.';
    exit;
}
$projectEmployees = (new Employee())->getByProjectId($projectId);
$unassignedEmployees = (new Employee())->getProjectlessEmployees($projectId);

if(!$projectEmployees) {
    echo 'No employees found for this project.';
}

echo <<<HTML
<h1>Edit Project</h1>
<form method="POST" action="edit.php?id={$projectId}">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{$project['name']}" required>
    <input type="hidden" name="action" value="update">
    <button type="submit">Update</button>
</form>
<h2>Employees in this Project</h2>
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
foreach ($projectEmployees as $employee) {
    echo <<<HTML
            <tr class="data-menu-row">
                <td>{$employee['employeeId']}</td>
                <td>{$employee['firstName']}</td>
                <td>{$employee['lastName']}</td>
                <td>
                    <form method="POST" action="edit.php?id={$project['projectId']}">
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
<h2>Unassigned Employees</h2>
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
                    <form method="POST" action="edit.php?id={$project['projectId']}">
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
HTML;
include_once ROOT_PATH . '/public/footer.php';