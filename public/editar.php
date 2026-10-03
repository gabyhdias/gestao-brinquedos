<?php
require_once 'config/database.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$erros = [];

// Carregar dados atuais do brinquedo
try {
    $stmt = $pdo->prepare("SELECT * FROM brinquedos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $brinquedo = $stmt->fetch();

    if (!$brinquedo) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar brinquedo: " . $e->getMessage());
}

// Processar atualização (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome'] ?? '');
    $categoria   = trim($_POST['categoria'] ?? '');
    $faixaEtaria = trim($_POST['faixa_etaria'] ?? '');
    $preco       = filter_var(str_replace(',', '.', $_POST['preco'] ?? ''), FILTER_VALIDATE_FLOAT);
    $quantidade  = filter_var($_POST['quantidade'] ?? '', FILTER_VALIDATE_INT);

    // Validações do formulário
    if (empty($nome)) $erros[] = "O campo 'Nome' é obrigatório.";
    if (empty($categoria)) $erros[] = "O campo 'Categoria' é obrigatório.";
    if (empty($faixaEtaria)) $erros[] = "O campo 'Faixa Etária' é obrigatório.";
    if ($preco === false || $preco < 0) $erros[] = "Informe um preço válido.";
    if ($quantidade === false || $quantidade < 0) $erros[] = "Informe uma quantidade válida.";

    if (empty($erros)) {
        try {
            $sql = "UPDATE brinquedos 
                    SET nome = :nome, categoria = :categoria, faixa_etaria = :faixa_etaria, preco = :preco, quantidade = :quantidade 
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'         => $nome,
                ':categoria'    => $categoria,
                ':faixa_etaria' => $faixaEtaria,
                ':preco'        => $preco,
                ':quantidade'   => $quantidade,
                ':id'           => $id
            ]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erros[] = "Erro ao atualizar brinquedo: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Brinquedo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; }
        .form-container { width: 400px; background: white; padding: 20px; border-radius: 5px; border: 1px solid #ccc; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-save { background-color: #007bff; color: white; }
        .btn-back { background-color: #6c757d; color: white; }
        .erro-box { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Editar Brinquedo #<?= $brinquedo['id'] ?></h2>

        <?php if (!empty($erros)): ?>
            <div class="erro-box">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= $brinquedo['id'] ?>">
            <div class="form-group">
                <label>Nome do Brinquedo:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? $brinquedo['nome']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Categoria:</label>
                <input type="text" name="categoria" value="<?= htmlspecialchars($_POST['categoria'] ?? $brinquedo['categoria']) ?>" required>
            </div>

            <div class="form-group">
                <label>Faixa Etária:</label>
                <input type="text" name="faixa_etaria" value="<?= htmlspecialchars($_POST['faixa_etaria'] ?? $brinquedo['faixa_etaria']) ?>" required>
            </div>

            <div class="form-group">
                <label>Preço (R$):</label>
                <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($_POST['preco'] ?? $brinquedo['preco']) ?>" required>
            </div>

            <div class="form-group">
                <label>Quantidade em Estoque:</label>
                <input type="number" name="quantidade" value="<?= htmlspecialchars($_POST['quantidade'] ?? $brinquedo['quantidade']) ?>" required>
            </div>

            <button type="submit" class="btn btn-save">Atualizar</button>
            <a href="index.php" class="btn btn-back">Cancelar</a>
        </form>
    </div>

</body>
</html>