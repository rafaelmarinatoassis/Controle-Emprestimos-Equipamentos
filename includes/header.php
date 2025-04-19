<?php
$pagina_atual = basename($_SERVER['PHP_SELF']);
$is_index = $pagina_atual === 'index.php';
$base_path = $is_index ? '' : '../../';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../../index.php">
            <i class="bi bi-box-seam me-2"></i>
            Sistema de Empréstimos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/index.php') !== false ? 'active' : ''; ?>" 
                       href="../../index.php">
                        <i class="bi bi-house-fill me-1"></i>
                        Página Inicial
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/usuarios/') !== false ? 'active' : ''; ?>" 
                       href="../../views/usuarios/listar.php">
                        <i class="bi bi-people me-1"></i>
                        Usuários
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/equipamentos/') !== false ? 'active' : ''; ?>" 
                       href="../../views/equipamentos/listar.php">
                        <i class="bi bi-box-seam me-1"></i>
                        Equipamentos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/emprestimos/') !== false ? 'active' : ''; ?>" 
                       href="../../views/emprestimos/listar.php">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Empréstimos
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav> 