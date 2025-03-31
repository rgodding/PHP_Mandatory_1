<?php
require_once '../../initialise.php';
$pageTitle = 'Departments';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Department.php';

$departmentDb = new Department();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if ($action === 'add') {
            $name = $_POST['name'] ?? null;
            $result = $departmentDb->add($name);
            if ($result) {
                echo "<p class='success'>Department added successfully.</p>";
                header('Location: index.php');
                exit;
            } else {
                throw new Exception('Failed to add department.');
            }            
        } else {
            throw new Exception('Invalid action.');
        }   
    } catch (Exception $e) {
        echo "<p class='error'>Error: {$e->getMessage()}</p>";
    }
}

echo <<<HTML
<h1>Add Department</h1>
<form action="add.php" method="POST">
    <div>
        <label for="name">Department Name</label>
        <input type="text" name="name" id="name" required>
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Department</button>
    </div>
</form>
HTML;