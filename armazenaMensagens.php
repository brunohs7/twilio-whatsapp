<?php
// Recebe dados do Twilio via POST
$body = $_POST['Body']; 
$from = $_POST['From']; 
$to = $_POST['To'];

// Ignora mensagens enviadas ao próprio robô
$myNumber = "whatsapp:+5599999999999";
if ($from === $myNumber) {
    error_log("Mensagem ignorada porque foi enviada ao próprio número.");
    exit;
}

// Ignora mensagens repetidas
require '/var/www/vhosts/site.com/httpdocs/twilio/config/database.php';
$conn = getDatabaseConnection();

$stmt = $conn->prepare("SELECT COUNT(*) FROM mensagens WHERE remetente = ? AND mensagem = ? AND data_recebimento > NOW() - INTERVAL 1 MINUTE");
$stmt->bind_param("ss", $from, $body);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    error_log("Mensagem duplicada ignorada de $from.");
    exit;
}

// Grava a mensagem no banco de dados
$stmt = $conn->prepare("INSERT INTO mensagens (remetente, mensagem, data_recebimento, status) VALUES (?, ?, NOW(), 'nao lida')");
$stmt->bind_param("ss", $from, $body);
$stmt->execute();
$stmt->close();

// Dados do Twilio
require '/var/www/vhosts/site.com/httpdocs/twilio/config/twilio.php';
$twilio = getTwilioClient();
$contentSid = "**********************";
$messagingServiceSid = "*******************";

// Envia a resposta
try {
    $response = $twilio->messages->create(
        $from,
        [
            "from" => $myNumber,
            "contentSid" => $contentSid,
            "messagingServiceSid" => $messagingServiceSid
        ]
    );
} catch (TwilioException $e) {
    error_log("Erro ao enviar mensagem: " . $e->getMessage());
}

// Retorna o XML vazio
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<Response></Response>';
?>
