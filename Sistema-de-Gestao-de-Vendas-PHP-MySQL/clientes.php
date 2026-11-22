<?php
require_once 'conexao.php';

$mensagem = "";

// Processa o formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_cliente'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    if (!empty($nome) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone) VALUES (:nome, :email, :telefone)");
            $stmt->execute(['nome' => $nome, 'email' => $email, 'telefone' => $telefone]);
            $mensagem = "<p style='color: green;'>Cliente cadastrado com sucesso!</p>";
        } catch (PDOException $e) {
            $mensagem = "<p style='color: red;'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensagem = "<p style='color: red;'>Preencha todos os campos obrigatórios.</p>";
    }
}

// Busca os clientes cadastrados
$stmt = $pdo->query("SELECT * FROM clientes ORDER BY id DESC");
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Clientes</title>
</head>

<body>
    <h2>Cadastro de Clientes</h2>
    <?= $mensagem ?>
    <form action="clientes.php" method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone"><br><br>

        <button type="submit" name="cadastrar_cliente">Cadastrar Cliente</button>
    </form>

    <h2>Clientes Cadastrados</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
        </tr>
        <?php if (count($clientes) > 0): ?>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td>
                        <?= $c['id'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['email']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($c['telefone']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Nenhum cliente cadastrado.</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>
    <a href="produtos.php">Ir para Cadastro de Produtos</a>
</body>

</html>