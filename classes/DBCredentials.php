<?php
class DBCredentials {
    protected string $host;
    protected string $dbname;
    protected string $user;
    protected string $password;

    public function __construct() {
        $config = require __DIR__ . '/../config.php';

        $this->host = $config['DB_HOST'];
        $this->dbname = $config['DB_NAME'];
        $this->user = $config['DB_USER'];
        $this->password = $config['DB_PASSWORD'];
    }
}
?>