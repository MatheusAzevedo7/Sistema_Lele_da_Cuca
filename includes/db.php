<?php
// Conexão com o banco de dados
$host = '127.0.0.1';
$db   = 'lele_da_cuca';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}

// Cria tabela de usuários (usuarios) e insere usuário padrão Manuela se não existir
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(100) NOT NULL UNIQUE,
        senha_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $check = $pdo->prepare('SELECT id FROM usuarios WHERE usuario = ? LIMIT 1');
    $check->execute(['Manuela']);
    if (!$check->fetch()) {
        $hash = password_hash('manu0704', PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO usuarios (usuario, senha_hash) VALUES (?, ?)');
        $ins->execute(['Manuela', $hash]);
    }
} catch (Exception $e) {
    // não interrompe o fluxo; em produção, envie para log
}
