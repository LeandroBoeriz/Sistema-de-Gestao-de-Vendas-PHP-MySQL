<?php
require_once 'conexao.php';

try {
    // Consulta simples para testar a comunicação com o banco
    $stmt = $pdo->query("SELECT VERSION() AS versao_mysql");
    $resultado = $stmt->fetch();

    echo "<h3>Conexão bem-sucedida com o MySQL!</h3>";
    echo "<p>Versão do Servidor: " . $resultado['versao_mysql'] . "</p>";

} catch (Exception $e) {
    echo "<p>Falha ao executar a consulta de teste: " . $e->getMessage() . "</p>";
}
?>