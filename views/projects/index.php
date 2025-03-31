<?php
require_once '../../initialise.php';
$pageTitle = 'Projects';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
require_once ROOT_PATH . '/classes/Project.php';

$projects = (new Project())->getAll();

if (!$projects) {
    echo 'No projects found.';
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
foreach ($projects as $project) {
    echo <<<HTML
            <tr class="data-row" onclick="window.location.href='edit.php?id={$project['projectId']}'">
                <td>{$project['projectId']}</td>
                <td>{$project['name']}</td>
            </tr>
    HTML;
}
echo <<<HTML
    </tbody>
</table>
HTML;
include_once ROOT_PATH . '/public/footer.php';