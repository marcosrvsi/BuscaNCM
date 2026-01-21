<?php

header("Content-Type: application/json; charset=UTF-8");

// ================================
// VALIDA NCM
// ================================
$ncm = $_GET['ncm'] ?? null;

if (!$ncm) {
    echo json_encode(["erro" => "Informe o NCM"]);
    exit;
}

// ================================
// CONFIGURAÇÕES DE LOG
// ================================
$logDir = __DIR__ . '/logs';

if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Arquivo de log diário
$logFile = $logDir . '/consulta_ncm_' . date('Y-m-d') . '.txt';

// Dados da consulta
$dataHora = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONHECIDO';

// ================================
// CHAMADA DA API EXTERNA
// ================================
$url = "https://mvsistema.com/MeusPedidos/API/apidesktop/v31/TabelaNCM/getTabelaNCMCClasstrib?ncm=" . urlencode($ncm);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);

if ($response === false) {
    $erroCurl = curl_error($ch);

    // Log de erro
    $log = [];
    $log[] = str_repeat('=', 80);
    $log[] = "DATA/HORA : {$dataHora}";
    $log[] = "IP        : {$ip}";
    $log[] = "NCM       : {$ncm}";
    $log[] = "ERRO CURL : {$erroCurl}";
    $log[] = PHP_EOL;

    file_put_contents($logFile, implode(PHP_EOL, $log), FILE_APPEND);

    echo json_encode(["erro" => "Erro ao consultar API externa"]);
    exit;
}

curl_close($ch);

// ================================
// GRAVA LOG DA CONSULTA
// ================================
$log = [];
$log[] = str_repeat('=', 80);
$log[] = "DATA/HORA : {$dataHora}";
$log[] = "IP        : {$ip}";
$log[] = "NCM       : {$ncm}";
$log[] = "RETORNO API:";
$log[] = $response;
$log[] = PHP_EOL;

file_put_contents($logFile, implode(PHP_EOL, $log), FILE_APPEND);

// ================================
// RETORNA PARA O FRONT
// ================================
echo $response;
