<?php
// header comum com proteção de páginas
if (session_status() === PHP_SESSION_NONE) session_start();

$current = basename($_SERVER['PHP_SELF']);
$allowed = ['Login.php','login.php'];
if (!in_array($current, $allowed, true)) {
    if (empty($_SESSION['user'])) {
        header('Location: /Lele_da_Cuca/Login.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lelé da Cuca</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Lele_da_Cuca/assets/css/style.css">
</head>
<body>
<div class="container">
<header>
    <div class="logo">
        <div class="brand">Lelé da Cuca</div>
    </div>
    <nav>
        <a href="/Lele_da_Cuca/index.php">Início</a>
        <a href="/Lele_da_Cuca/clientes.php">Clientes</a>
        <a href="/Lele_da_Cuca/produtos.php">Produtos</a>
        <a href="/Lele_da_Cuca/pedidos.php">Pedidos</a>
        <a href="/Lele_da_Cuca/relatorios.php">Relatórios</a>
        <?php if (!empty($_SESSION['user'])): ?>
            <span style="margin-left:12px;color:#666">Olá, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="/Lele_da_Cuca/Logout.php" style="margin-left:10px">Sair</a>
        <?php endif; ?>
    </nav>
</header>
<main>
