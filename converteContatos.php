<?php
// Lógica do Twilio para enviar mensagens
require '/var/www/vhosts/site.com/httpdocs/twilio/config/twilio.php';
$twilio = getTwilioClient();

// Conexão com o banco de dados
require '/var/www/vhosts/site.com/httpdocs/twilio/config/database.php';
$conn = getDatabaseConnection();

// Conexão com o banco de dados usando variáveis do .env
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

// Função para validar e formatar o número de telefone
function validarTelefone($telefone) {
    // Remove qualquer caractere não numérico
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verifica o número de dígitos e aplica as regras de prefixo
    if (strlen($telefone) == 11) {
        // Para números de 11 dígitos, adiciona o código do país (55)
        return '55' . $telefone;
    } elseif (strlen($telefone) == 10) {
        // Para números de 10 dígitos, adiciona o código do país (55)
        return '55' . $telefone;
    } elseif (strlen($telefone) == 9) {
        // Para números de 9 dígitos, adiciona o código do país (5531)
        return '5531' . $telefone;
    } else {
        return $telefone; // Número inválido
    }
}

// Abre o arquivo CSV
if (($handle = fopen("contatos.csv", "r")) !== FALSE) {
    // Ignora a primeira linha (cabeçalhos)
    $headers = fgetcsv($handle);

    // Prepara o SQL para inserir os dados
    $stmt = $conn->prepare("INSERT INTO contatos (nome, telefone, data_de_envio, status) VALUES (?, ?, null, 'pendente')");

    // Itera sobre as linhas do CSV
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // Encontra o índice das colunas correspondentes no CSV
        $nome = $data[array_search('Nome', $headers)];
        $telefone = $data[array_search('Telefone', $headers)];

        // Valida e formata o número de telefone
        $telefoneValido = validarTelefone($telefone);

        // Se o telefone for válido, insere no banco de dados
        if ($telefoneValido) {
            // Insere os dados na tabela
            $stmt->bind_param("ss", $nome, $telefoneValido);
            $stmt->execute();
        } else {
            // Se o telefone não for válido, exibe uma mensagem de erro
            echo "Telefone inválido para $nome: $telefone<br>";
        }
    }

    fclose($handle);
    echo "Contatos importados com sucesso!";
} else {
    echo "Erro ao abrir o arquivo CSV.";
}

$conn->close();
?>
