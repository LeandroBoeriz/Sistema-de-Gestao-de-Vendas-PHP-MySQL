<?php
require_once 'conexao.php';

$mensagem = "";

// Processa o registro da nova venda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_venda'])) {
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $produto_id = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

    if ($cliente_id && $produto_id && $quantidade > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO vendas (cliente_id, produto_id, quantidade) VALUES (:cliente_id, :produto_id, :quantidade)");
            $stmt->execute([
                'cliente_id' => $cliente_id,
                'produto_id' => $produto_id,
                'quantidade' => $quantidade
            ]);
            $mensagem = "<p style='color: green;'>Venda registrada com sucesso!</p>";
        } catch (PDOException $e) {
            $mensagem = "<p style='color: red;'>Erro ao registrar venda: " . $e->getMessage() . "</p>";
        }
    } else {
        $mensagem = "<p style='color: red;'>Selecione um cliente, um produto e informe uma quantidade válida.</p>";
    }
}

// Busca a lista de clientes para o formulário
$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome")->fetchAll();

// Busca a lista de produtos para o formulário
$produtos = $pdo->query("SELECT id, nome, preco FROM produtos ORDER BY nome")->fetchAll();

// Busca o histórico de vendas unindo as tabelas para exibir os nomes e calcular o total (Preço x Quantidade)
$sqlVendas = "SELECT v.id, c.nome AS cliente_nome, p.nome AS produto_nome, v.quantidade, p.preco, v.data_venda, 
                    (v.quantidade * p.preco) AS total 
              FROM vendas v
              JOIN clientes c ON v.cliente_id = c.id
              JOIN produtos p ON v.produto_id = p.id
              ORDER BY v.id DESC";
$vendas = $pdo->query($sqlVendas)->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Vendas</title>
</head>

<body>
    <h2>Registrar Nova Venda</h2>
    <?= $mensagem ?>
    <form action="vendas.php" method="POST">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Produto:</label><br>
        <select name="produto_id" required>
            <option value="">Selecione um produto</option>
            <?php foreach ($produtos as $p): ?>
                <option value="<?= $p['id'] ?>">
                    <?= htmlspecialchars($p['nome']) ?> (R$
                    <?= number_format($p['preco'], 2, ',', '.') ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" min="1" value="1" required><br><br>

        <button type="submit" name="registrar_venda">Registrar Venda</button>
    </form>

    <h2>Histórico de Vendas</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Data</th>
            <th>Total</th>
        </tr>
        <?php if (count($vendas) > 0): ?>
            <?php foreach ($vendas as $v): ?>
                <tr>
                    <td>
                        <?= $v['id'] ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($v['cliente_nome']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($v['produto_nome']) ?>
                    </td>
                    <td>
                        <?= $v['quantidade'] ?>
                    </td>
                    <td>
                        <?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?>
                    </td>
                    <td>R$
                        <?= number_format($v['total'], 2, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhuma venda registrada.</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>
    <a href="clientes.php">Ir para Clientes</a> |
    <a href="produtos.php">Ir para Produtos</a>
</body>

</html>