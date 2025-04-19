<?php
session_start();
require_once 'models/Usuario.php';
require_once 'models/Equipamento.php';
require_once 'models/Emprestimo.php';

$usuario = new Usuario();
$equipamento = new Equipamento();
$emprestimo = new Emprestimo();

$total_usuarios = $usuario->contarAtivos();
$total_equipamentos = $equipamento->contarTotalEquipamentos();
$equipamentos_disponiveis = $equipamento->contarDisponiveis();
$emprestimos_ativos = $emprestimo->contarAtivos();

// Buscar empréstimos recentes
$emprestimos_recentes = $emprestimo->listar(1, 5); // Últimos 5 empréstimos

// Mapeamento de ícones por categoria
$icones_categoria = [
    'informatica' => 'bi-laptop',
    'laboratorio' => 'bi-eyedropper',
    'lab' => 'bi-eyedropper',
    'musicais' => 'bi-music-note-beamed',
    'esportes' => 'bi-trophy',
    'seguranca' => 'bi-shield-check',
    'audiovisual' => 'bi-camera-video',
    'brinquedos_educativos' => 'bi-puzzle',
    'brinquedos' => 'bi-puzzle',
    'outros' => 'bi-box'
];

// Função para normalizar strings (remover acentos e caracteres especiais)
function normalizar_string($str) {
    $str = strtolower(trim($str));
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    $str = str_replace(' ', '', $str);
    return $str;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container py-4">
        <!-- Cabeçalho do Dashboard -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Painel de Controle</h1>
                <p class="text-muted">Bem-vindo ao Sistema de Empréstimos</p>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="row g-4 mb-4">
            <!-- Card de Usuários -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-people text-primary fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total de Usuários</h6>
                                <h2 class="card-title mb-0"><?php echo $total_usuarios; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="views/usuarios/listar.php" class="btn btn-link text-primary p-0">
                            Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card de Equipamentos -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-box-seam text-success fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Total de Equipamentos</h6>
                                <h2 class="card-title mb-0"><?php echo $total_equipamentos; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="views/equipamentos/listar.php" class="btn btn-link text-success p-0">
                            Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card de Equipamentos Disponíveis -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-check-circle text-info fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Equipamentos Disponíveis</h6>
                                <h2 class="card-title mb-0"><?php echo $equipamentos_disponiveis; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="views/equipamentos/listar.php" class="btn btn-link text-info p-0">
                            Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card de Empréstimos Ativos -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="bi bi-clock-history text-warning fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">Empréstimos Ativos</h6>
                                <h2 class="card-title mb-0"><?php echo $emprestimos_ativos; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="views/emprestimos/listar.php" class="btn btn-link text-warning p-0">
                            Ver detalhes <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Ações Rápidas -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning-charge me-2"></i>
                            Ações Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="views/emprestimos/cadastrar.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Novo Empréstimo
                            </a>
                            <a href="views/usuarios/cadastrar.php" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i> Novo Usuário
                            </a>
                            <a href="views/equipamentos/cadastrar.php" class="btn btn-outline-primary">
                                <i class="bi bi-plus-square me-2"></i> Novo Equipamento
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empréstimos Recentes -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Empréstimos Recentes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Equipamento</th>
                                        <th>Usuário</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($emprestimos_recentes as $emp): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <?php 
                                                    $categoria = $emp['equipamento_categoria'] ?? 'outros';
                                                    $categoria_normalizada = normalizar_string($categoria);
                                                    $icone = $icones_categoria[$categoria_normalizada] ?? 'bi-box';
                                                    ?>
                                                    <i class="bi <?php echo $icone; ?> text-primary"></i>
                                                </div>
                                                <?php echo htmlspecialchars($emp['equipamento_nome']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($emp['usuario_nome']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($emp['data_emprestimo'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $emp['status'] === 'ativo' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($emp['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($emp['status'] === 'ativo'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#devolverModal<?php echo $emp['id']; ?>">
                                                    <i class="bi bi-check-circle"></i> Devolver
                                                </button>

                                                <!-- Modal de confirmação de devolução -->
                                                <div class="modal fade" id="devolverModal<?php echo $emp['id']; ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar Devolução</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <p class="mb-3">Tem certeza que deseja registrar a devolução deste equipamento?</p>
                                                                
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm">
                                                                        <tr>
                                                                            <th class="w-25">Equipamento:</th>
                                                                            <td><strong><?php echo htmlspecialchars($emp['equipamento_nome']); ?></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Categoria:</th>
                                                                            <td>
                                                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                                                    <?php echo htmlspecialchars($emp['equipamento_categoria']); ?>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Patrimônio:</th>
                                                                            <td class="text-muted">
                                                                                #<?php echo htmlspecialchars($emp['equipamento_patrimonio']); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Estado:</th>
                                                                            <td>
                                                                                <?php
                                                                                $estado_classes = [
                                                                                    'otimo' => 'success',
                                                                                    'bom' => 'info',
                                                                                    'regular' => 'warning',
                                                                                    'ruim' => 'danger'
                                                                                ];
                                                                                $estado_class = $estado_classes[strtolower($emp['equipamento_estado'])] ?? 'secondary';
                                                                                ?>
                                                                                <span class="badge bg-<?php echo $estado_class; ?>">
                                                                                    <?php echo ucfirst(htmlspecialchars($emp['equipamento_estado'])); ?>
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Emprestado para:</th>
                                                                            <td>
                                                                                <i class="bi bi-person text-muted me-1"></i>
                                                                                <?php echo htmlspecialchars($emp['usuario_nome']); ?>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <a href="views/emprestimos/devolver.php?id=<?php echo $emp['id']; ?>" 
                                                                   class="btn btn-success">
                                                                    <i class="bi bi-arrow-return-left me-1"></i>
                                                                    Confirmar Devolução
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="views/emprestimos/listar.php" class="btn btn-link text-primary p-0">
                            Ver todos os empréstimos <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>
</html> 