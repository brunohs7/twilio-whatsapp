<?php
require '/var/www/vhosts/site.com/httpdocs/twilio/vendor/autoload.php';

use Dotenv\Dotenv;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

// Carrega variáveis do arquivo .env
$dotenv = Dotenv::createImmutable('/var/www/vhosts/site.com/app');
$dotenv->load();

$sid = $_ENV['TWILIO_ACCOUNT_SID'];
$token = $_ENV['TWILIO_AUTH_TOKEN'];

// Função para inicializar o cliente Twilio
function getTwilioClient() {
    global $sid, $token;
    return new Client($sid, $token);
}