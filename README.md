# Sistema de Empréstimos

Sistema web para gerenciamento de empréstimos de equipamentos, desenvolvido em PHP com MySQL.

## 🚀 Funcionalidades

- **Gestão de Usuários**
  - Cadastro de alunos e professores
  - Gerenciamento de informações pessoais
  - Controle de matrículas/SIAPE

- **Gestão de Equipamentos**
  - Cadastro por categorias:
    - Informática (laptops, desktops, etc.)
    - Laboratório (equipamentos científicos)
    - Musicais (instrumentos)
    - Esportes (materiais esportivos)
    - Segurança (equipamentos de proteção)
    - Audiovisual (câmeras, projetores)
    - Brinquedos Educativos
  - Controle de patrimônio
  - Status de conservação
  - Disponibilidade

- **Gestão de Empréstimos**
  - Registro de empréstimos
  - Controle de devoluções
  - Histórico completo
  - Status em tempo real

## 💻 Tecnologias Utilizadas

- **Frontend**
  - HTML5
  - CSS3
  - Bootstrap 5.3.0
  - Bootstrap Icons
  - JavaScript
  - Font Inter (Google Fonts)

- **Backend**
  - PHP
  - MySQL
  - PDO para conexão com banco de dados

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)
- Extensão PDO PHP habilitada

## 🔧 Instalação

1. Clone o repositório:
```bash
git clone [url-do-repositorio]
```

2. Importe o banco de dados:
```bash
mysql -u seu_usuario -p sistema_emprestimos < database.sql
```

3. Configure a conexão com o banco em `config/Database.php`:
```php
private $host = 'localhost';
private $db_name = 'sistema_emprestimos';
private $username = 'seu_usuario';
private $password = 'sua_senha';
```

4. (Opcional) Importe dados de exemplo:
```bash
mysql -u seu_usuario -p sistema_emprestimos < Exemplos.sql
```

## 📱 Interface

- Design responsivo
- Interface moderna e intuitiva
- Ícones consistentes por categoria:
  - 💻 Informática: `bi-laptop`
  - 🧪 Laboratório: `bi-eyedropper`
  - 🎵 Musicais: `bi-music-note-beamed`
  - 🏆 Esportes: `bi-trophy`
  - 🛡️ Segurança: `bi-shield-check`
  - 🎥 Audiovisual: `bi-camera-video`
  - 🧩 Brinquedos Educativos: `bi-puzzle`
  - 📦 Outros: `bi-box`

## 🔒 Segurança

- Validação de dados
- Prevenção contra SQL Injection
- Sanitização de entrada de dados
- Escape de saída HTML

## 📊 Dashboard

- Visão geral do sistema
- Estatísticas em tempo real
- Empréstimos recentes
- Ações rápidas

## 🤝 Contribuição

1. Faça um Fork do projeto
2. Crie uma Branch para sua Feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## ✒️ Autor

* **Rafael Marinato Assis**

## 📄 Licença

Este projeto está sob a licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes 