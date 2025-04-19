<?php
require_once '../../models/Usuario.php';
require_once '../../config/Database.php';

header('Content-Type: application/json');

// Log da requisição
error_log('Busca de usuários - termo recebido: ' . print_r($_GET, true));

$termo = isset($_GET['term']) ? $_GET['term'] : ''; // Mudado de 'termo' para 'term' para compatibilidade com jQuery UI

$usuario = new Usuario();
$usuarios = $usuario->buscarParaAutocomplete($termo);

$formatados = array_map(function($item) {
    return [
        'id' => $item['id'],
        'label' => $item['nome_completo'] . ' (Matrícula: ' . $item['matricula'] . ')',
        'value' => $item['nome_completo']
    ];
}, $usuarios);

// Log dos resultados
error_log('Resultados encontrados: ' . print_r($formatados, true));

echo json_encode($formatados); 