<?php
require_once '../../models/Equipamento.php';
require_once '../../config/Database.php';

header('Content-Type: application/json');

// Log da requisição
error_log('Busca de equipamentos - termo recebido: ' . print_r($_GET, true));

$termo = isset($_GET['term']) ? $_GET['term'] : ''; // Mudado de 'termo' para 'term' para compatibilidade com jQuery UI

$equipamento = new Equipamento();
$equipamentos = $equipamento->listarDisponiveis();

$resultados = array_filter($equipamentos, function($item) use ($termo) {
    return empty($termo) || 
           stripos($item['nome'], $termo) !== false || 
           stripos($item['numero_patrimonio'], $termo) !== false;
});

$resultados = array_values($resultados);
$resultados = array_slice($resultados, 0, 10);

$formatados = array_map(function($item) {
    return [
        'id' => $item['id'],
        'label' => $item['nome'] . ' (Patrimônio: ' . $item['numero_patrimonio'] . ')',
        'value' => $item['nome']
    ];
}, $resultados);

// Log dos resultados
error_log('Resultados encontrados: ' . print_r($formatados, true));

echo json_encode($formatados); 