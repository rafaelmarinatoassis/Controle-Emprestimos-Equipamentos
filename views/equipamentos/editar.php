<?php
require_once '../../models/Equipamento.php';

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}

$equipamento = new Equipamento();
$dados = $equipamento->buscarPorId($_GET['id']);

if (!$dados) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $equipamento->atualizar(
        $_GET['id'],
        $_POST['nome'],
        $_POST['categoria'],
        $_POST['numero_patrimonio'],
        $_POST['estado_conservacao']
    );

    if ($resultado) {
        header('Location: listar.php');
        exit;
    } else {
        $erro = "Erro ao atualizar equipamento.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipamento - Sistema de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Sistema de Empréstimos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../usuarios/listar.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="listar.php">Equipamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../emprestimos/listar.php">Empréstimos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Equipamento</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger"><?php echo $erro; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="informatica" <?php echo $dados['categoria'] === 'informatica' ? 'selected' : ''; ?>>Informática</option>
                                    <option value="laboratorio" <?php echo $dados['categoria'] === 'laboratorio' ? 'selected' : ''; ?>>Laboratório</option>
                                    <option value="musicais" <?php echo $dados['categoria'] === 'musicais' ? 'selected' : ''; ?>>Musicais</option>
                                    <option value="esportes" <?php echo $dados['categoria'] === 'esportes' ? 'selected' : ''; ?>>Esportes</option>
                                    <option value="seguranca" <?php echo $dados['categoria'] === 'seguranca' ? 'selected' : ''; ?>>Segurança</option>
                                    <option value="audiovisual" <?php echo $dados['categoria'] === 'audiovisual' ? 'selected' : ''; ?>>Audiovisual</option>
                                    <option value="brinquedos" <?php echo $dados['categoria'] === 'brinquedos' ? 'selected' : ''; ?>>Brinquedos Educativos</option>
                                    <option value="outros" <?php echo $dados['categoria'] === 'outros' ? 'selected' : ''; ?>>Outros</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="numero_patrimonio" class="form-label">Número de Patrimônio</label>
                                <input type="text" class="form-control" id="numero_patrimonio" name="numero_patrimonio" value="<?php echo htmlspecialchars($dados['numero_patrimonio']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="estado_conservacao" class="form-label">Estado de Conservação</label>
                                <select class="form-select" id="estado_conservacao" name="estado_conservacao" required>
                                    <option value="novo" <?php echo $dados['estado_conservacao'] === 'novo' ? 'selected' : ''; ?>>Novo</option>
                                    <option value="otimo" <?php echo $dados['estado_conservacao'] === 'otimo' ? 'selected' : ''; ?>>Ótimo</option>
                                    <option value="bom" <?php echo $dados['estado_conservacao'] === 'bom' ? 'selected' : ''; ?>>Bom</option>
                                    <option value="regular" <?php echo $dados['estado_conservacao'] === 'regular' ? 'selected' : ''; ?>>Regular</option>
                                    <option value="ruim" <?php echo $dados['estado_conservacao'] === 'ruim' ? 'selected' : ''; ?>>Ruim</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 