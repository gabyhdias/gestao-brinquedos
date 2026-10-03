<?php
require_once 'config/database.php';

$mensagem = '';
$tipoMensagem = '';

// Processar Exclusão (DELETE) com Prepared Statement
if (isset($_GET['excluir']) && filter_var($_GET['excluir'], FILTER_VALIDATE_INT)) {
    $idExcluir = (int)$_GET['excluir'];
    try {
        $stmt = $pdo->prepare("DELETE FROM brinquedos WHERE id = :id");
        $stmt->execute([':id' => $idExcluir]);

        $mensagem = "Brinquedo excluído com sucesso!";
        $tipoMensagem = "sucesso";
    } catch (PDOException $e) {
        $mensagem = "Erro ao excluir brinquedo: " . $e->getMessage();
        $tipoMensagem = "erro";
    }
}

// Buscar Brinquedos (READ)
try {
    $stmt = $pdo->query("SELECT * FROM brinquedos ORDER BY id DESC");
    $brinquedos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar registros: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Brinquedos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; }
        h1 { color: #333; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; color: white; font-weight: bold; }
        .btn-add { background-color: #28a745; margin-bottom: 15px; display: inline-block; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-delete { background-color: #dc3545; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .sucesso { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <h1>Gestão de Brinquedos</h1>

    <?php if ($mensagem): ?>
        <div class="alert <?= $tipoMensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <a href="cadastrar.php" class="btn btn-add">+ Cadastrar Novo Brinquedo</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Faixa Etária</th>
                <th>Preço (R$)</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($brinquedos) > 0): ?>
                <?php foreach ($brinquedos as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['nome']) ?></td>
                        <td><?= htmlspecialchars($b['categoria']) ?></td>
                        <td><?= htmlspecialchars($b['faixa_etaria']) ?></td>
                        <td>R$ <?= number_format($b['preco'], 2, ',', '.') ?></td>
                        <td><?= $b['quantidade'] ?></td>
                        <td>
                            <a href="editar.php?id=<?= $b['id'] ?>" class="btn btn-edit">Editar</a>
                            <a href="index.php?excluir=<?= $b['id'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este brinquedo?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhum brinquedo cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>