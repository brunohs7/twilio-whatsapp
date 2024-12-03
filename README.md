# Twilio WhatsApp Messaging Integration

Este projeto é uma integração com o Twilio para envio e recebimento de mensagens via WhatsApp. Ele permite:
- Receber mensagens de usuários e armazená-las em um banco de dados.
- Responder automaticamente às mensagens recebidas usando templates aprovados no Twilio.
- Gerenciar o status das mensagens no banco de dados.

## 🚀 Funcionalidades

- Receber mensagens enviadas para um número do WhatsApp registrado no Twilio.
- Armazenar as mensagens recebidas em uma tabela no banco de dados.
- Responder automaticamente às mensagens recebidas usando templates do Twilio.
- Gerenciar mensagens pendentes e enviadas a partir do banco de dados.

## 📁 Estrutura do Projeto

```bash
.
├── twilio/
│   ├── config/
│   │   ├── twilio.php        # Configuração do cliente Twilio
│   │   ├── database.php      # Configuração da conexão com o banco de dados
├── mensagens/
│   ├── receber.php           # Script para processar mensagens recebidas
│   ├── enviar.php            # Script para enviar mensagens pendentes
├── .env                      # Arquivo de variáveis de ambiente (não incluído no repositório)
├── README.md                 # Este arquivo
```

## 🔧 Requisitos
- PHP 7.4 ou superior
- Banco de Dados MySQL
- Composer para gerenciar dependências PHP
- Conta Twilio com:
	- Número de WhatsApp registrado
	- contentSid para templates de mensagem
	- messagingServiceSid configurado

## ⚙️ Configuração
1. Faça o download do projeto para o seu ambiente de desenvolvimento.
2. Instale as dependências PHP utilizando o Composer. O arquivo composer.json contém as informações necessárias.
3. Crie um arquivo .env no diretório raiz do projeto e configure as seguintes variáveis:
```bashTWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_WHATSAPP_FROM=whatsapp:+55XXXXXXXXXX
DB_HOST=your_database_host
DB_USER=your_database_user
DB_PASSWORD=your_database_password
DB_NAME=your_database_name
```
4. Configure o webhook no Twilio para que ele aponte para o script receber.php no servidor. Este script será chamado sempre que uma mensagem for enviada para o número do WhatsApp registrado.
5. Configure o banco de dados, criando uma tabela mensagens com a seguinte estrutura:
```bash
CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remetente VARCHAR(255),
    mensagem TEXT,
    data_recebimento DATETIME,
    status ENUM('nao lida', 'enviado') DEFAULT 'nao lida'
);
```
6. Substitua as URLs de exemplo no código pelas URLs reais do seu servidor.

## 🛠️ Uso
- Receber Mensagens: O script receber.php armazena mensagens recebidas no banco de dados.
- Enviar Mensagens: O script enviar.php processa mensagens pendentes e responde automaticamente com o template configurado.

## 🛡️ Segurança
- O arquivo .env não deve ser incluído no repositório.
- Utilize conexões HTTPS para proteger os dados transmitidos.
- Nunca exponha publicamente credenciais sensíveis, como as do Twilio ou do banco de dados.

## 📝 Licença
Este projeto está sob a licença MIT. Consulte o arquivo LICENSE para mais informações.

## ✉️ Contato
Se precisar de ajuda ou quiser sugerir melhorias, entre em contato:

brunoh_123@hotmail.com
