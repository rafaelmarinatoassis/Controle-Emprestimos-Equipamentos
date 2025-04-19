-- Inserção de Usuários
INSERT INTO usuarios (nome_completo, tipo, matricula, email) VALUES
-- Professores (15)
('Maria Silva Santos', 'professor', 'SIAPE123401', 'maria.santos@ifba.edu.br'),
('João Carlos Oliveira', 'professor', 'SIAPE123402', 'joao.oliveira@ifba.edu.br'),
('Ana Paula Ferreira', 'professor', 'SIAPE123403', 'ana.ferreira@ifba.edu.br'),
('Roberto Almeida Costa', 'professor', 'SIAPE123404', 'roberto.costa@ifba.edu.br'),
('Carla Mendes Lima', 'professor', 'SIAPE123405', 'carla.lima@ifba.edu.br'),
('Paulo Ricardo Souza', 'professor', 'SIAPE123406', 'paulo.souza@ifba.edu.br'),
('Luciana Pereira Santos', 'professor', 'SIAPE123407', 'luciana.santos@ifba.edu.br'),
('Fernando Gomes Silva', 'professor', 'SIAPE123408', 'fernando.silva@ifba.edu.br'),
('Patricia Costa Lima', 'professor', 'SIAPE123409', 'patricia.lima@ifba.edu.br'),
('Ricardo Oliveira Santos', 'professor', 'SIAPE123410', 'ricardo.santos@ifba.edu.br'),
('Amanda Ferreira Costa', 'professor', 'SIAPE123411', 'amanda.costa@ifba.edu.br'),
('Bruno Silva Mendes', 'professor', 'SIAPE123412', 'bruno.mendes@ifba.edu.br'),
('Cristina Lima Alves', 'professor', 'SIAPE123413', 'cristina.alves@ifba.edu.br'),
('Daniel Santos Pereira', 'professor', 'SIAPE123414', 'daniel.pereira@ifba.edu.br'),
('Elena Costa Silva', 'professor', 'SIAPE123415', 'elena.silva@ifba.edu.br'),

-- Alunos (15)
('Pedro Henrique Santos', 'aluno', '2023001', 'pedro.santos@ifba.edu.br'),
('Julia Oliveira Lima', 'aluno', '2023002', 'julia.lima@ifba.edu.br'),
('Lucas Ferreira Costa', 'aluno', '2023003', 'lucas.costa@ifba.edu.br'),
('Mariana Silva Alves', 'aluno', '2023004', 'mariana.alves@ifba.edu.br'),
('Gabriel Santos Lima', 'aluno', '2023005', 'gabriel.lima@ifba.edu.br'),
('Beatriz Costa Silva', 'aluno', '2023006', 'beatriz.silva@ifba.edu.br'),
('Thiago Oliveira Santos', 'aluno', '2023007', 'thiago.santos@ifba.edu.br'),
('Laura Lima Ferreira', 'aluno', '2023008', 'laura.ferreira@ifba.edu.br'),
('Rafael Alves Costa', 'aluno', '2023009', 'rafael.costa@ifba.edu.br'),
('Carolina Silva Santos', 'aluno', '2023010', 'carolina.santos@ifba.edu.br'),
('Matheus Lima Oliveira', 'aluno', '2023011', 'matheus.oliveira@ifba.edu.br'),
('Isabella Santos Silva', 'aluno', '2023012', 'isabella.silva@ifba.edu.br'),
('Leonardo Costa Lima', 'aluno', '2023013', 'leonardo.lima@ifba.edu.br'),
('Sofia Ferreira Santos', 'aluno', '2023014', 'sofia.santos@ifba.edu.br'),
('Victor Alves Silva', 'aluno', '2023015', 'victor.silva@ifba.edu.br');

-- Inserção de Equipamentos
INSERT INTO equipamentos (nome, categoria, numero_patrimonio, estado_conservacao, status) VALUES
-- Informática (8)
('Notebook Dell Latitude 5520', 'Informática', 'PAT001', 'otimo', 'disponivel'),
('Notebook HP Pavilion 15', 'Informática', 'PAT002', 'bom', 'disponivel'),
('Notebook Lenovo ThinkPad E14', 'Informática', 'PAT003', 'otimo', 'disponivel'),
('Desktop Dell OptiPlex 3090', 'Informática', 'PAT004', 'bom', 'disponivel'),
('Projetor Epson PowerLite S41', 'Informática', 'PAT005', 'bom', 'disponivel'),
('Impressora HP LaserJet Pro', 'Informática', 'PAT006', 'regular', 'disponivel'),
('Scanner Brother ADS-2200', 'Informática', 'PAT007', 'bom', 'disponivel'),
('Tablet Samsung Galaxy Tab S7', 'Informática', 'PAT008', 'otimo', 'disponivel'),

-- Laboratório (6)
('Microscópio Binocular', 'Laboratório', 'PAT009', 'otimo', 'disponivel'),
('Balança Analítica Digital', 'Laboratório', 'PAT010', 'bom', 'disponivel'),
('pHmetro Digital', 'Laboratório', 'PAT011', 'bom', 'disponivel'),
('Estufa de Cultura', 'Laboratório', 'PAT012', 'regular', 'disponivel'),
('Centrífuga de Bancada', 'Laboratório', 'PAT013', 'bom', 'disponivel'),
('Kit de Vidraria', 'Laboratório', 'PAT014', 'regular', 'disponivel'),

-- Musicais (4)
('Violão Acústico', 'Musicais', 'PAT015', 'bom', 'disponivel'),
('Teclado Musical Yamaha', 'Musicais', 'PAT016', 'otimo', 'disponivel'),
('Flauta Doce Soprano', 'Musicais', 'PAT017', 'bom', 'disponivel'),
('Kit de Percussão', 'Musicais', 'PAT018', 'regular', 'disponivel'),

-- Esportes (4)
('Kit Bolas (Futebol/Vôlei/Basquete)', 'Esportes', 'PAT019', 'bom', 'disponivel'),
('Rede de Vôlei Profissional', 'Esportes', 'PAT020', 'otimo', 'disponivel'),
('Kit Atletismo', 'Esportes', 'PAT021', 'bom', 'disponivel'),
('Mesa de Tênis de Mesa', 'Esportes', 'PAT022', 'regular', 'disponivel'),

-- Segurança (2)
('Kit Primeiros Socorros', 'Segurança', 'PAT023', 'otimo', 'disponivel'),
('Extintor de Incêndio', 'Segurança', 'PAT024', 'bom', 'disponivel'),

-- Audiovisual (3)
('Câmera DSLR Canon', 'Audiovisual', 'PAT025', 'otimo', 'disponivel'),
('Microfone Profissional', 'Audiovisual', 'PAT026', 'bom', 'disponivel'),
('Tripé para Câmera', 'Audiovisual', 'PAT027', 'bom', 'disponivel'),

-- Brinquedos Educativos (3)
('Kit Robótica Educacional', 'Brinquedos Educativos', 'PAT028', 'otimo', 'disponivel'),
('Jogo de Xadrez Profissional', 'Brinquedos Educativos', 'PAT029', 'bom', 'disponivel'),
('Kit de Matemática Lúdica', 'Brinquedos Educativos', 'PAT030', 'bom', 'disponivel');

-- Inserção de Empréstimos
INSERT INTO emprestimos (equipamento_id, usuario_id, data_emprestimo, data_prevista_devolucao, data_devolucao, status) VALUES
-- Empréstimos Ativos (5)
(1, 1, '2024-03-18', '2024-04-18', NULL, 'ativo'),
(2, 3, '2024-03-19', '2024-04-19', NULL, 'ativo'),
(3, 5, '2024-03-20', '2024-04-20', NULL, 'ativo'),
(4, 7, '2024-03-21', '2024-04-21', NULL, 'ativo'),
(5, 9, '2024-03-22', '2024-04-22', NULL, 'ativo'),

-- Empréstimos Devolvidos (5)
(6, 2, '2024-02-15', '2024-03-15', '2024-03-14', 'devolvido'),
(7, 4, '2024-02-16', '2024-03-16', '2024-03-15', 'devolvido'),
(8, 6, '2024-02-17', '2024-03-17', '2024-03-16', 'devolvido'),
(9, 8, '2024-02-18', '2024-03-18', '2024-03-17', 'devolvido'),
(10, 10, '2024-02-19', '2024-03-19', '2024-03-18', 'devolvido'); 