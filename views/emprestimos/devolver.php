<?php
require_once '../../models/Emprestimo.php';

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}

$emprestimo = new Emprestimo();
$resultado = $emprestimo->devolver($_GET['id']);

if ($resultado) {
    header('Location: listar.php');
    exit;
} else {
    $erro = "Erro ao registrar devolução.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolução de Equipamento - Sistema de Empréstimos</title>
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
                        <a class="nav-link" href="../equipamentos/listar.php">Equipamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="listar.php">Empréstimos</a>
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
                        <h3 class="card-title">Devolução de Equipamento</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger"><?php echo $erro; ?></div>
                        <?php endif; ?>

                        <p>Processando a devolução do equipamento...</p>
                        <a href="listar.php" class="btn btn-primary">Voltar para Listagem</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 