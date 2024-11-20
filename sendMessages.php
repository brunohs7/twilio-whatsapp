<?php
// Lógica do Twilio para enviar mensagens
require '/var/www/vhosts/site.com/httpdocs/twilio/config/twilio.php';
$twilio = getTwilioClient();

// Conexão com o banco de dados
require '/var/www/vhosts/site.com/httpdocs/twilio/config/database.php';
$conn = getDatabaseConnection();

// Verifica se a consulta foi bem-sucedida
$sql = "SELECT id, nome, telefone FROM contatos WHERE status = 'pendente' LIMIT 25";
$result = $conn->query($sql);

if (!$result) {
    die("Erro ao executar a consulta: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $phoneNumber = $row['telefone'];
    $name = $row['nome'];
    
    try {
        // Envia a mensagem de WhatsApp usando o template aprovado
        $message = $twilio->messages->create(
            "whatsapp:$phoneNumber",
            [
                "from" => $_ENV['TWILIO_WHATSAPP_FROM'],
                "contentSid" => "**************",
                "messagingServiceSid" => "**************" 
            ]
        );

        // Atualiza o status do contato para "enviado" e adiciona a data de envio
        $updateSql = "UPDATE contatos SET status = 'enviado', data_de_envio = NOW() WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        $stmt->close();

        echo "Mensagem enviada para $name.<br>";
    } catch (Exception $e) {
        echo "Erro ao enviar mensagem para $name: " . $e->getMessage() . "<br>";
    }
}

// Libera o resultado da consulta e fecha a conexão
$result->free();
$conn->close();
?>
