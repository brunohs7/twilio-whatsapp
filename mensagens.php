<?php
// Lógica do Twilio para enviar mensagens
require '/var/www/vhosts/site.com/httpdocs/twilio/config/twilio.php';
$twilio = getTwilioClient();

// Conexão com o banco de dados
require '/var/www/vhosts/site.com/httpdocs/twilio/config/database.php';
$conn = getDatabaseConnection();

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta as mensagens não lidas
$sql = "SELECT * FROM mensagens WHERE status = 'nao lida' ORDER BY data_recebimento DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Exibe as mensagens não lidas
    while($row = $result->fetch_assoc()) {
        echo "Remetente: " . $row['remetente'] . "<br>";
        echo "Mensagem: " . $row['mensagem'] . "<br>";
        echo "Data de Recebimento: " . $row['data_recebimento'] . "<br>";
        echo "------------------------<br>";
    }
} else {
    echo "Não há mensagens não lidas.";
}

$conn->close();
?>
