<?php
require '/var/www/vhosts/site.com/httpdocs/twilio/vendor/autoload.php';

use Dotenv\Dotenv;

// Carrega variáveis do arquivo .env
$dotenv = Dotenv::createImmutable('/var/www/vhosts/site.com/app');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

// Função para obter a conexão
function getDatabaseConnection() {
    global $host, $dbname, $username, $password;
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão com o banco de dados: " . $conn->connect_error);
    }

    return $conn;
}