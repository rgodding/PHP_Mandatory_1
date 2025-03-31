<?php
/* Includes path, giving access to Header, Footer & Nav */
require_once 'initialise.php';

/* Page title shown in browser tab */
$pageTitle = 'Front Page';

/* includes the files from ROOT_PATH */
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
    
?>
<main>
    <h1>Company Project</h1>
    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias vel odio deleniti, qui cum, veritatis corrupti sequi ea optio necessitatibus quam debitis explicabo, est sit quod accusamus officiis? Dolor, animi?
    </p>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>