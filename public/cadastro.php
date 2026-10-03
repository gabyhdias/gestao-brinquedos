<?php
require_once 'config/database.php';

$erros = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome'] ?? '');
    $categoria   = trim($_POST['categoria'] ?? '');
    $faixaEtaria = trim($_POST['faixa_etaria'] ?? '');
    $preco       = filter_var(str_replace(',', '.', $_POST['preco'] ?? ''), FILTER_VALIDATE_FLOAT);
    $quantidade  = filter_var($_POST['quantidade'] ?? '', FILTER_VALIDATE_INT);

    // Validações básicas de backend
    if (empty($nome)) $erros[] = "O campo 'Nome' é obrigatório.";
    if (empty($categoria)) $erros[] = "O campo 'Categoria' é obrigatório.";
    if (empty($faixaEtaria)) $erros[] = "O campo 'Faixa Etária' é obrigatório.";
    if ($preco === false || $preco < 0) $erros[] = "Informe um preço válido maior ou igual a zero.";
    if ($quantidade === false || $quantidade < 0) $erros[] = "Informe uma quantidade em estoque válida.";

    // Se não houver erros, insere usando Prepared Statement
    if (empty($erros)) {
        try {
            $sql = "INSERT INTO brinquedos (nome, categoria, faixa_etaria, preco, quantidade) 
                    VALUES (:nome, :categoria, :faixa_etaria, :preco, :quantidade)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'         => $nome,
                ':categoria'    => $categoria,
                ':faixa_etaria' => $faixaEtaria,
                ':preco'        => $preco,
                ':quantidade'   => $quantidade
            ]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erros[] = "Erro ao cadastrar brinquedo: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Brinquedo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; }
        .form-container { width: 400px; background: white; padding: 20px; border-radius: 5px; border: 1px solid #ccc; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-save { background-color: #28a745; color: white; }
        .btn-back { background-color: #6c757d; color: white; }
        .erro-box { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Cadastrar Brinquedo</h2>

        <?php if (!empty($erros)): ?>
            <div class="erro-box">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="cadastrar.php">
            <div class="form-group">
                <label>Nome do Brinquedo:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label>Categoria:</label>
                <input type="text" name="categoria" value="<?= htmlspecialchars($_POST['categoria'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Faixa Etária:</label>
                <input type="text" name="faixa_etaria" value="<?= htmlspecialchars($_POST['faixa_etaria'] ?? '') ?>" placeholder="Ex: 3 a 5 anos" required>
            </div>

            <div class="form-group">
                <label>Preço (R$):</label>
                <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Quantidade em Estoque:</label>
                <input type="number" name="quantidade" value="<?= htmlspecialchars($_POST['quantidade'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn btn-save">Salvar</button>
            <a href="index.php" class="btn btn-back">Voltar</a>
        </form>
    </div>

</body>
</html>