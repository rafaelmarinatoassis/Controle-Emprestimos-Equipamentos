<?php
require_once '../../models/Equipamento.php';

$equipamento = new Equipamento();
$termo_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$itens_por_pagina = 10;

$total_itens = $equipamento->contarTotal($termo_busca);
$total_paginas = ceil($total_itens / $itens_por_pagina);

$equipamentos = $equipamento->listar($pagina, $itens_por_pagina, $termo_busca);

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

// Mapeamento de cores por estado de conservação
$cores_estado = [
    'otimo' => 'success',
    'bom' => 'info',
    'regular' => 'warning',
    'ruim' => 'danger'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipamentos - Sistema de Empréstimos</title>
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
                <h1 class="h3 mb-0">Equipamentos</h1>
            </div>
            <div class="d-flex gap-2">
                <form class="d-flex" method="GET" action="">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               placeholder="Buscar equipamento..." 
                               name="busca"
                               value="<?php echo htmlspecialchars($termo_busca); ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <a href="cadastrar.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span class="d-none d-sm-inline">Novo Equipamento</span>
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
                                <th class="border-0">Categoria</th>
                                <th class="border-0">Patrimônio</th>
                                <th class="border-0">Estado</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($equipamentos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <?php if ($termo_busca): ?>
                                            <div class="mb-2">
                                                <i class="bi bi-search display-6"></i>
                                            </div>
                                            Nenhum equipamento encontrado com o termo "<?php echo htmlspecialchars($termo_busca); ?>"
                                        <?php else: ?>
                                            <div class="mb-2">
                                                <i class="bi bi-laptop display-6"></i>
                                            </div>
                                            Nenhum equipamento cadastrado
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($equipamentos as $equip): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <?php 
                                                    $categoria_normalizada = normalizar_string($equip['categoria']);
                                                    $icone = $icones_categoria[$categoria_normalizada] ?? 'bi-box';
                                                    ?>
                                                    <i class="bi <?php echo $icone; ?> text-primary"></i>
                                                </div>
                                                <?php echo htmlspecialchars($equip['nome']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <?php echo htmlspecialchars($equip['categoria']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                #<?php echo htmlspecialchars($equip['numero_patrimonio']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $cores_estado[strtolower($equip['estado_conservacao'])] ?? 'secondary'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($equip['estado_conservacao'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $equip['status'] === 'disponivel' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($equip['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="editar.php?id=<?php echo $equip['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?php echo $equip['id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de confirmação de exclusão -->
                                            <div class="modal fade" id="deleteModal<?php echo $equip['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar Exclusão</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <p>Tem certeza que deseja excluir o equipamento 
                                                            <strong><?php echo htmlspecialchars($equip['nome']); ?></strong>?</p>
                                                            <p class="mb-0"><small class="text-danger">Esta ação não pode ser desfeita.</small></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="excluir.php?id=<?php echo $equip['id']; ?>" 
                                                               class="btn btn-danger">
                                                                Excluir
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                            Mostrando <?php echo count($equipamentos); ?> de <?php echo $total_itens; ?> equipamentos
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