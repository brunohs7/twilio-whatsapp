<?php
// Recebe dados do Twilio via POST
$body = $_POST['Body']; 
$from = $_POST['From']; 
$to = $_POST['To'];
$mediaUrl = isset($_POST['MediaUrl0']) ? $_POST['MediaUrl0'] : ''; 

// Lógica do Twilio para enviar mensagens
require '/var/www/vhosts/site.com/httpdocs/twilio/config/twilio.php';
$twilio = getTwilioClient();

// Conexão com o banco de dados
require '/var/www/vhosts/site.com/httpdocs/twilio/config/database.php';
$conn = getDatabaseConnection();

// Conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Inserir a mensagem no banco de dados
$stmt = $conn->prepare("INSERT INTO mensagens (remetente, mensagem, data_recebimento, status) VALUES (?, ?, NOW(), 'nao lida')");
$stmt->bind_param("ss", $from, $body);
$stmt->execute();
$stmt->close();

// Fechar a conexão com o banco de dados
$conn->close();

// Dados da conta Twilio
$messagingServiceSid = "**************";
$contentSid = "**************";

// Tentar enviar a resposta usando o template
try {
    $response = $twilio->messages->create(
        $from,
        [
            "from" => "whatsapp:+5531999999999", 
            "contentSid" => $contentSid,
            "messagingServiceSid" => $messagingServiceSid 
        ]
    );
} catch (TwilioException $e) {
    // Captura o erro e exibe a mensagem
    error_log("Erro ao enviar a mensagem via Twilio: " . $e->getMessage());
    echo "Erro ao enviar a resposta. Verifique o log para detalhes.";
}

// **Aqui começa o XML, sem qualquer saída anterior!**
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response></Response>';
?>
