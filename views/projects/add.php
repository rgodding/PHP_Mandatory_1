<?php
require_once '../../initialise.php';
$pageTitle = 'Projects';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Project.php';

$projectDb = new Project($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    try {
        if ($action === 'add') {
            $name = $_POST['name'] ?? null;
            $result = $projectDb->add($name);
            if ($result) {
                echo "<p class='success'>Project added successfully.</p>";
                header('Location: index.php');
                exit;
            } else {
                throw new Exception('Failed to add project.');
            }            
        } else {
            throw new Exception('Invalid action.');
        }   
    } catch (Exception $e) {
        echo "<p class='error'>Error: {$e->getMessage()}</p>";
    }
}

echo <<<HTML
<h1>Add Project</h1>
<form action="add.php" method="POST">
    <div>
        <label for="name">Project Name</label>
        <input type="text" name="name" id="name" required>
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Project</button>
    </div>
</form>
HTML;