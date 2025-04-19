<?php
require_once '../../models/Emprestimo.php';

$emprestimo = new Emprestimo();
$termo_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$itens_por_pagina = 10;

$total_itens = $emprestimo->contarTotal($termo_busca);
$total_paginas = ceil($total_itens / $itens_por_pagina);

$emprestimos = $emprestimo->listar($pagina, $itens_por_pagina, $termo_busca);

// Mapeamento de cores por status
$cores_status = [
    'ativo' => [
        'bg' => 'success',
        'icon' => 'bi-check-circle-fill'
    ],
    'devolvido' => [
        'bg' => 'secondary',
        'icon' => 'bi-arrow-return-left'
    ]
];

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

// Função auxiliar para determinar o ícone baseado no nome do equipamento
function determinarIcone($nome, $categoria, $icones_categoria) {
    $nome = strtolower($nome);
    
    if (strpos($nome, 'balança') !== false) return $icones_categoria['Balança'];
    if (strpos($nome, 'microscópio') !== false || strpos($nome, 'microscopio') !== false) return $icones_categoria['Microscópio'];
    if (strpos($nome, 'kit') !== false) return $icones_categoria['Kit'];
    if (strpos($nome, 'desktop') !== false) return $icones_categoria['Desktop'];
    if (strpos($nome, 'notebook') !== false) return $icones_categoria['Notebook'];
    if (strpos($nome, 'projetor') !== false) return $icones_categoria['Projetor'];
    if (strpos($nome, 'tablet') !== false) return $icones_categoria['Tablet'];
    
    return $icones_categoria[$categoria] ?? $icones_categoria['outros'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos - Sistema de Empréstimos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include '../../includes/header.php'; ?>

    <div class="container py-4">
        <!-- Cabeçalho da página -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-0">Empréstimos</h1>
            </div>
            <div class="d-flex gap-2">
                <form class="d-flex flex-grow-1" method="GET" action="">
                    <div class="input-group" style="min-width: 300px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               placeholder=" Buscar por equipamento ou usuário" 
                               name="busca"
                               value="<?php echo htmlspecialchars($termo_busca); ?>"
                               aria-label="Buscar empréstimos"
                               style="min-width: 300px;">
                        <?php if ($termo_busca): ?>
                            <a href="?" class="input-group-text bg-white text-decoration-none border-start-0" title="Limpar busca">
                                <i class="bi bi-x-lg text-muted"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
                <a href="cadastrar.php" class="btn btn-primary d-flex align-items-center">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span class="d-none d-sm-inline">Novo Empréstimo</span>
                </a>
            </div>
        </div>

        <!-- Card principal -->
        <div class="card border-0 shadow-sm">
            <?php if ($termo_busca): ?>
                <div class="card-header bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            Resultados para: <strong><?php echo htmlspecialchars($termo_busca); ?></strong>
                            (<?php echo $total_itens; ?> encontrados)
                        </span>
                        <a href="?" class="btn btn-link btn-sm text-decoration-none">
                            <i class="bi bi-x-lg"></i> Limpar busca
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Equipamento</th>
                                <th class="border-0">Usuário</th>
                                <th class="border-0">Data Empréstimo</th>
                                <th class="border-0">Previsão Devolução</th>
                                <th class="border-0">Data Devolução</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($emprestimos)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <?php if ($termo_busca): ?>
                                            <div class="mb-2">
                                                <i class="bi bi-search display-6"></i>
                                            </div>
                                            Nenhum empréstimo encontrado com o termo "<?php echo htmlspecialchars($termo_busca); ?>"
                                        <?php else: ?>
                                            <div class="mb-2">
                                                <i class="bi bi-inbox display-6"></i>
                                            </div>
                                            Nenhum empréstimo registrado
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($emprestimos as $emp): ?>
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <?php echo htmlspecialchars($emp['usuario_nome']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-event text-muted me-2"></i>
                                                <?php echo date('d/m/Y', strtotime($emp['data_emprestimo'])); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar-check text-muted me-2"></i>
                                                <?php echo date('d/m/Y', strtotime($emp['data_prevista_devolucao'])); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($emp['data_devolucao']): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-calendar-check-fill text-success me-2"></i>
                                                    <?php echo date('d/m/Y', strtotime($emp['data_devolucao'])); ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $status_info = $cores_status[$emp['status']] ?? ['bg' => 'secondary', 'icon' => 'bi-question-circle'];
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-<?php echo $status_info['bg']; ?> d-flex align-items-center gap-1">
                                                    <i class="bi <?php echo $status_info['icon']; ?> small"></i>
                                                    <?php echo ucfirst($emp['status']); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($emp['status'] === 'ativo'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#devolverModal<?php echo $emp['id']; ?>">
                                                    <i class="bi bi-arrow-return-left me-1"></i>
                                                    Devolver
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
                                                                <a href="devolver.php?id=<?php echo $emp['id']; ?>" 
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($total_paginas > 1): ?>
                <div class="card-footer bg-transparent">
                    <nav aria-label="Navegação de páginas" class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Mostrando <?php echo count($emprestimos); ?> de <?php echo $total_itens; ?> empréstimos
                        </small>
                        <ul class="pagination pagination-sm mb-0">
                            <?php if ($pagina > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?><?php echo $termo_busca ? '&busca=' . urlencode($termo_busca) : ''; ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php
                            $start_page = max(1, $pagina - 2);
                            $end_page = min($total_paginas, $pagina + 2);
                            
                            if ($start_page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?pagina=1' . ($termo_busca ? '&busca=' . urlencode($termo_busca) : '') . '">1</a></li>';
                                if ($start_page > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?php echo $i === $pagina ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo $termo_busca ? '&busca=' . urlencode($termo_busca) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor;

                            if ($end_page < $total_paginas) {
                                if ($end_page < $total_paginas - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $total_paginas . ($termo_busca ? '&busca=' . urlencode($termo_busca) : '') . '">' . $total_paginas . '</a></li>';
                            }
                            ?>

                            <?php if ($pagina < $total_paginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?><?php echo $termo_busca ? '&busca=' . urlencode($termo_busca) : ''; ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../../includes/footer.php'; ?>
</body>
</html> 