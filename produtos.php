<?php
require_once 'conexao.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_produto'])) {
    $nome = trim($_POST['nome']);
    $preco = str_replace(',', '.', $_POST['preco']); // Aceita vírgula como separador decimal
    $nomeFoto = null;

    // Processamento do upload da foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $pastaUpload = "uploads/";
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0755, true); // Cria a pasta caso não exista
        }

        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoesPermitidas)) {
            $nomeFoto = uniqid() . "." . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], $pastaUpload . $nomeFoto);
        } else {
            $mensagem = "<p style='color: red;'>Formato de imagem inválido. Utilize JPG, PNG ou WEBP.</p>";
        }
    }

    if (!empty($nome) && is_numeric($preco) && empty($mensagem)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, foto) VALUES (:nome, :preco, :foto)");
            $stmt->execute(['nome' => $nome, 'preco' => $preco, 'foto' => $nomeFoto]);
            $mensagem = "<p style='color: green;'>Produto cadastrado com sucesso!</p>";
        } catch (PDOException $e) {
            $mensagem = "<p style='color: red;'>Erro ao cadastrar produto: " . $e->getMessage() . "</p>";
        }
    } elseif (empty($mensagem)) {
        $mensagem = "<p style='color: red;'>Informe um nome e um preço válido.</p>";
    }
}

// Busca os produtos cadastrados
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
</head>
<body>
    <h2>Cadastro de Produtos</h2>
    <?= $mensagem ?>
    <form action="produtos.php" method="POST" enctype="multipart/form-data">
        <label>Nome do Produto:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Preço (R$):</label><br>
        <input type="text" name="preco" placeholder="0.00" required><br><br>

        <label>Foto do Produto:</label><br>
        <input type="file" name="foto" accept="image/*"><br><br>

        <button type="submit" name="cadastrar_produto">Cadastrar Produto</button>
    </form>

    <h2>Produtos Cadastrados</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>Preço</th>
        </tr>
        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td>
                        <?php if (!empty($p['foto']) && file_exists("uploads/" . $p['foto'])): ?>
                            <img src="uploads/<?= $p['foto'] ?>" width="60" alt="Foto do produto">
                        <?php else: ?>
                            <span>Sem foto</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nenhum produto cadastrado.</td></tr>
        <?php endif; ?>
    </table>
    <br>
    <a href="clientes.php">Ir para Cadastro de Clientes</a>
</body>
</html>