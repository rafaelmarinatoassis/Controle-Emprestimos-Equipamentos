<?php
require_once '../../models/Usuario.php';

$usuario = new Usuario();
$termo_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$itens_por_pagina = 10;

$total_itens = $usuario->contarTotal($termo_busca);
$total_paginas = ceil($total_itens / $itens_por_pagina);

$usuarios = $usuario->listar($pagina, $itens_por_pagina, $termo_busca);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Sistema de Empréstimos</title>
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
                <h1 class="h3 mb-0">Usuários</h1>
            </div>
            <div class="d-flex gap-2">
                <form class="d-flex" method="GET" action="">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               placeholder="Buscar usuário..." 
                               name="busca"
                               value="<?php echo htmlspecialchars($termo_busca); ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <a href="cadastrar.php" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    <span class="d-none d-sm-inline">Novo Usuário</span>
                </a>
            </div>
        </div>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

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
                                <th class="border-0">Nome</th>
                                <th class="border-0">Tipo</th>
                                <th class="border-0">Matrícula</th>
                                <th class="border-0">Email</th>
                                <th class="border-0 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <?php if ($termo_busca): ?>
                                            <div class="mb-2">
                                                <i class="bi bi-search display-6"></i>
                                            </div>
                                            Nenhum usuário encontrado com o termo "<?php echo htmlspecialchars($termo_busca); ?>"
                                        <?php else: ?>
                                            <div class="mb-2">
                                                <i class="bi bi-people display-6"></i>
                                            </div>
                                            Nenhum usuário cadastrado
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <?php echo htmlspecialchars($usuario['nome_completo']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $usuario['tipo'] == 'professor' ? 'primary' : 'success'; ?>">
                                                <?php echo ucfirst(htmlspecialchars($usuario['tipo'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['matricula']); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($usuario['email']); ?>" 
                                               class="text-decoration-none">
                                                <?php echo htmlspecialchars($usuario['email']); ?>
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="editar.php?id=<?php echo $usuario['id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?php echo $usuario['id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de confirmação de exclusão -->
                                            <div class="modal fade" id="deleteModal<?php echo $usuario['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar Exclusão</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            Tem certeza que deseja excluir o usuário 
                                                            <strong><?php echo htmlspecialchars($usuario['nome_completo']); ?></strong>?
                                                            <br>
                                                            <small class="text-danger">Esta ação não pode ser desfeita.</small>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <a href="excluir.php?id=<?php echo $usuario['id']; ?>" 
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
                            Mostrando <?php echo count($usuarios); ?> de <?php echo $total_itens; ?> usuários
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