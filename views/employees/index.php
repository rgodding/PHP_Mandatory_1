<?php
require_once '../../initialise.php';

$pageTitle = 'Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';


// Maybe put this into seperate place after understanding how to use it
require_once ROOT_PATH . '/classes/Employee.php';

$employees = (new Employee())->getAll();

if (!$employees) {
    echo 'No employees found.';
}

echo <<<HTML
<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birth Date</th>
            <th>Email</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody class="data-table-body">
HTML;
foreach ($employees as $employee) {
    echo <<<HTML
            <tr class="data-row" onclick="window.location.href='edit.php?id={$employee['employeeId']}'">
                <td>{$employee['employeeId']}</td>
                <td>{$employee['firstName']}</td>
                <td>{$employee['lastName']}</td>
                <td>{$employee['birth']}</td>
                <td>{$employee['email']}</td>
                <td>{$employee['departmentId']}</td>
            </tr>
    HTML;
}
echo <<<HTML
    </tbody>
</table>
HTML;

include_once ROOT_PATH . '/public/footer.php';