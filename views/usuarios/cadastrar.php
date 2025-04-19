<?php
require_once '../../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $resultado = $usuario->cadastrar(
        $_POST['nome_completo'],
        $_POST['tipo'],
        $_POST['matricula'],
        $_POST['email']
    );

    if ($resultado) {
        header('Location: listar.php');
        exit;
    } else {
        $erro = "Erro ao cadastrar usuário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - Sistema de Empréstimos</title>
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
                <h1 class="h3 mb-0">Novo Usuário</h1>
                <p class="text-muted">Cadastre um novo usuário no sistema</p>
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
                            <label for="nome_completo" class="form-label">
                                <i class="bi bi-person me-1"></i>
                                Nome Completo
                            </label>
                            <input type="text" class="form-control" id="nome_completo" 
                                   name="nome_completo" required 
                                   placeholder="Digite o nome completo">
                            <div class="invalid-feedback">
                                Por favor, informe o nome completo.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>
                                Tipo de Usuário
                            </label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecione o tipo</option>
                                <option value="aluno">Aluno</option>
                                <option value="professor">Professor</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor, selecione o tipo de usuário.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricula" class="form-label">
                                <i class="bi bi-card-text me-1"></i>
                                Matrícula
                            </label>
                            <input type="text" class="form-control" id="matricula" 
                                   name="matricula" required 
                                   placeholder="Digite o número da matrícula">
                            <div class="invalid-feedback">
                                Por favor, informe o número da matrícula.
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                E-mail
                            </label>
                            <input type="email" class="form-control" id="email" 
                                   name="email" required 
                                   placeholder="Digite o e-mail institucional">
                            <div class="invalid-feedback">
                                Por favor, informe um e-mail válido.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            Cadastrar Usuário
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
    <script>
        // Validação do formulário
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Formatar matrícula conforme o tipo de usuário
        document.getElementById('tipo').addEventListener('change', function() {
            const matricula = document.getElementById('matricula')
            const email = document.getElementById('email')
            
            if (this.value === 'aluno') {
                matricula.placeholder = 'Digite a matrícula do aluno (ex: 2023001)'
            } else if (this.value === 'professor') {
                matricula.placeholder = 'Digite o SIAPE do professor'
            }
            
            // Limpa os campos quando muda o tipo
            matricula.value = ''
            email.value = ''
        })
    </script>
    <?php include '../../includes/footer.php'; ?>
</body>
</html> 