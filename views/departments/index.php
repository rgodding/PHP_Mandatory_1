<?php
require_once '../../initialise.php';
$pageTitle = 'Departments';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Department.php';

$departments = (new Department())->getAll();

if (!$departments) {
    echo 'No departments found.';
}

echo <<<HTML
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
HTML;
foreach ($departments as $department) {
    echo <<<HTML
            <tr class="data-row" onclick="window.location.href='edit.php?id={$department['departmentId']}'">
                <td>{$department['departmentId']}</td>
                <td>{$department['name']}</td>
            </tr>
    HTML;
}
echo <<<HTML
    </tbody>
</table>
HTML;
