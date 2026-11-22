<?php
// Carregue as credenciais locais sem publicá-las no repositório.
$configFile = __DIR__ . '/config.local.php';
if (!file_exists($configFile)) {
    die('Configuração ausente. Copie config.example.php para config.local.php e ajuste os dados.');
}
$config = require $configFile;

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    error_log('Falha de conexão com o banco de dados: ' . $e->getMessage());
    die('Não foi possível conectar ao banco de dados. Confira a configuração local.');
}
