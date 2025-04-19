<?php
require_once '../../models/Emprestimo.php';
require_once '../../models/Usuario.php';
require_once '../../models/Equipamento.php';
require_once '../../config/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$usuario = new Usuario();
$equipamento = new Equipamento();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug dos valores recebidos
    error_log("Dados do POST recebidos:");
    error_log("equipamento_id: " . $_POST['equipamento_id']);
    error_log("usuario_id: " . $_POST['usuario_id']);
    error_log("data_emprestimo: " . $_POST['data_emprestimo']);
    error_log("data_prevista_devolucao: " . $_POST['data_prevista_devolucao']);

    // Verifica se o equipamento existe e está disponível
    $equip = $equipamento->buscarPorId($_POST['equipamento_id']);
    error_log("Dados do equipamento encontrado:");
    error_log(print_r($equip, true));

    $erro = null;
    
    // Validação dos campos
    if (empty($_POST['equipamento_id'])) {
        $erro = "Por favor, selecione um equipamento.";
    } elseif (empty($_POST['usuario_id'])) {
        $erro = "Por favor, selecione um usuário.";
    } elseif (empty($_POST['data_emprestimo'])) {
        $erro = "Por favor, informe a data do empréstimo.";
    } elseif (empty($_POST['data_prevista_devolucao'])) {
        $erro = "Por favor, informe a data prevista de devolução.";
    } elseif (!$equip) {
        $erro = "Equipamento não encontrado.";
    } elseif ($equip['status'] !== 'disponivel') {
        $erro = "Este equipamento não está disponível. Status atual: " . $equip['status'];
    }

    if (!$erro) {
        $emprestimo = new Emprestimo();
        $resultado = $emprestimo->cadastrar(
            $_POST['equipamento_id'],
            $_POST['usuario_id'],
            $_POST['data_emprestimo'],
            $_POST['data_prevista_devolucao']
        );

        if ($resultado) {
            header('Location: listar.php');
            exit;
        } else {
            $erro = "Erro ao registrar empréstimo. Status do equipamento: " . ($equip ? $equip['status'] : 'não encontrado');
        }
    }
}

// Busca listas para os selects
$usuarios = $usuario->listar();
$equipamentos = $equipamento->listarDisponiveis();

error_log("Equipamentos disponíveis encontrados:");
error_log(print_r($equipamentos, true));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Empréstimo - Sistema de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
        }
        .card-body {
            padding: 2rem;
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
        .btn-primary {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }
        .btn-outline-primary {
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
        .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06);
            border: none;
            font-family: 'Inter', sans-serif;
        }
        .ui-menu-item {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .ui-menu-item:last-child {
            border-bottom: none;
        }
        .ui-state-active {
            background: #3b82f6 !important;
            border: none !important;
            color: white !important;
            margin: 0 !important;
        }
    </style>
</head>
<body class="bg-light">
    <?php include '../../includes/header.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Novo Empréstimo</h1>
                <p class="text-muted">Registre um novo empréstimo no sistema</p>
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
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="equipamento" class="form-label">
                                <i class="bi bi-tools me-1"></i>
                                Equipamento
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       id="equipamento" name="equipamento" 
                                       placeholder="Buscar equipamento..." required>
                                <input type="hidden" name="equipamento_id" id="equipamento_id" required>
                            </div>
                            <div class="invalid-feedback">
                                Por favor, selecione um equipamento.
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="usuario" class="form-label">
                                <i class="bi bi-person me-1"></i>
                                Usuário
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       id="usuario" name="usuario" 
                                       placeholder="Buscar usuário..." required>
                                <input type="hidden" name="usuario_id" id="usuario_id" required>
                            </div>
                            <div class="invalid-feedback">
                                Por favor, selecione um usuário.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="data_emprestimo" class="form-label">
                                <i class="bi bi-calendar-event me-1"></i>
                                Data do Empréstimo
                            </label>
                            <input type="date" class="form-control" 
                                   id="data_emprestimo" name="data_emprestimo" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                            <div class="invalid-feedback">
                                Por favor, selecione a data do empréstimo.
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="data_prevista_devolucao" class="form-label">
                                <i class="bi bi-calendar-check me-1"></i>
                                Data Prevista de Devolução
                            </label>
                            <input type="date" class="form-control" 
                                   id="data_prevista_devolucao" 
                                   name="data_prevista_devolucao" required>
                            <div class="invalid-feedback">
                                Por favor, selecione a data prevista de devolução.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>
                            Registrar Empréstimo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        function configurarAutocomplete(inputId, hiddenId, url) {
            $(inputId).autocomplete({
                source: url,
                minLength: 2,
                select: function(event, ui) {
                    event.preventDefault();
                    console.log('Item selecionado:', ui.item);
                    $(inputId).val(ui.item.label);
                    $(hiddenId).val(ui.item.id);
                    $(inputId).removeClass('is-invalid').addClass('is-valid');
                },
                focus: function(event, ui) {
                    event.preventDefault();
                    $(inputId).val(ui.item.label);
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<div>" + item.label + "</div>")
                    .appendTo(ul);
            };

            // Limpar ID quando o texto é alterado manualmente
            $(inputId).on('input', function() {
                if (!$(this).val()) {
                    $(hiddenId).val('');
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });
        }

        // Configurar autocomplete para equipamentos
        configurarAutocomplete("#equipamento", "#equipamento_id", "buscar_equipamentos.php");

        // Configurar autocomplete para usuários
        configurarAutocomplete("#usuario", "#usuario_id", "buscar_usuarios.php");

        // Validação do formulário
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            const equipamentoId = document.getElementById('equipamento_id').value;
            const usuarioId = document.getElementById('usuario_id').value;
            const dataEmprestimo = document.getElementById('data_emprestimo').value;
            const dataDevolucao = document.getElementById('data_prevista_devolucao').value;
            
            console.log('Dados do formulário antes do envio:', {
                equipamento_id: equipamentoId,
                usuario_id: usuarioId,
                data_emprestimo: dataEmprestimo,
                data_prevista_devolucao: dataDevolucao
            });

            if (!equipamentoId || !usuarioId) {
                event.preventDefault();
                event.stopPropagation();
                if (!equipamentoId) {
                    $('#equipamento').addClass('is-invalid');
                }
                if (!usuarioId) {
                    $('#usuario').addClass('is-invalid');
                }
                return;
            }

            if (!dataDevolucao) {
                event.preventDefault();
                event.stopPropagation();
                $('#data_prevista_devolucao').addClass('is-invalid');
                return;
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Define data mínima para devolução como hoje
        const hoje = new Date().toISOString().split('T')[0];
        document.getElementById('data_prevista_devolucao').setAttribute('min', hoje);

        // Adiciona tooltips aos campos
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
    </script>
    <?php include '../../includes/footer.php'; ?>
</body>
</html> 