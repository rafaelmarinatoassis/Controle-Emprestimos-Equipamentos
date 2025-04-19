<?php
require_once '../../models/Usuario.php';

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}

$usuario = new Usuario();
$dados = $usuario->buscarPorId($_GET['id']);

if (!$dados) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $usuario->atualizar(
        $_GET['id'],
        $_POST['nome_completo'],
        $_POST['tipo'],
        $_POST['matricula'],
        $_POST['email']
    );

    if ($resultado) {
        header('Location: listar.php');
        exit;
    } else {
        $erro = "Erro ao atualizar usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Sistema de Empréstimos</title>
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
                        <a class="nav-link active" href="listar.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../equipamentos/listar.php">Equipamentos</a>
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
                        <h3 class="card-title">Editar Usuário</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger"><?php echo $erro; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="nome_completo" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome_completo" name="nome_completo" 
                                       value="<?php echo htmlspecialchars($dados['nome_completo']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="aluno" <?php echo $dados['tipo'] === 'aluno' ? 'selected' : ''; ?>>Aluno</option>
                                    <option value="professor" <?php echo $dados['tipo'] === 'professor' ? 'selected' : ''; ?>>Professor</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="matricula" class="form-label">Matrícula/SIAPE</label>
                                <input type="text" class="form-control" id="matricula" name="matricula" 
                                       value="<?php echo htmlspecialchars($dados['matricula']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($dados['email']); ?>" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Atualizar</button>
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