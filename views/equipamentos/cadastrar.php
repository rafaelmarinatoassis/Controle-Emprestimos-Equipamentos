<?php
require_once '../../models/Equipamento.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipamento = new Equipamento();
    $resultado = $equipamento->cadastrar(
        $_POST['nome'],
        $_POST['categoria'],
        $_POST['numero_patrimonio'],
        $_POST['estado_conservacao']
    );

    if ($resultado) {
        header('Location: listar.php');
        exit;
    } else {
        $erro = "Erro ao cadastrar equipamento.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Equipamento - Sistema de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .form-label {
            font-weight: 500;
            color: #344767;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.02);
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.1);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .invalid-feedback {
            font-size: 0.813rem;
            color: #dc2626;
        }
    </style>
</head>
<body class="bg-light">
    <?php include '../../includes/header.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Novo Equipamento</h1>
                <p class="text-muted">Cadastre um novo equipamento no sistema</p>
            </div>
            <div>
                <a href="listar.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Voltar
                </a>
            </div>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">
                                <i class="bi bi-laptop me-1"></i>
                                Nome do Equipamento
                            </label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                            <div class="invalid-feedback">
                                Por favor, informe o nome do equipamento.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">
                                <i class="bi bi-tag me-1"></i>
                                Categoria
                            </label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Informática">Informática</option>
                                <option value="Laboratório">Laboratório</option>
                                <option value="Musicais">Musicais</option>
                                <option value="Esportes">Esportes</option>
                                <option value="Segurança">Segurança</option>
                                <option value="Audiovisual">Audiovisual</option>
                                <option value="Brinquedos Educativos">Brinquedos Educativos</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione uma categoria.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_patrimonio" class="form-label">
                                <i class="bi bi-upc me-1"></i>
                                Número de Patrimônio
                            </label>
                            <input type="text" class="form-control" id="numero_patrimonio" name="numero_patrimonio" required>
                            <div class="invalid-feedback">
                                Por favor, informe o número de patrimônio.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="estado_conservacao" class="form-label">
                                <i class="bi bi-stars me-1"></i>
                                Estado de Conservação
                            </label>
                            <select class="form-select" id="estado_conservacao" name="estado_conservacao" required>
                                <option value="">Selecione o estado</option>
                                <option value="novo">Novo</option>
                                <option value="otimo">Ótimo</option>
                                <option value="bom">Bom</option>
                                <option value="regular">Regular</option>
                                <option value="ruim">Ruim</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione o estado de conservação.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            Cadastrar Equipamento
                        </button>
                        <a href="listar.php" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../../includes/footer.php'; ?>
</body>
</html> 