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
  <style>
    body { margin:0; font-family:'Poppins', sans-serif; }
    header {
      display:flex; justify-content:space-between; align-items:center;
      padding:12px 18px; background:#f5f5f5; border-bottom:1px solid #ddd;
      position:relative; z-index:1001;
    }
    .logo .brand { font-weight:600; font-size:18px; }
    nav {
      display:flex; gap:12px; align-items:center;
    }
    nav a { text-decoration:none; color:#333; font-size:14px; }
    .menu-toggle {
      display:none; font-size:22px; cursor:pointer;
      background:none; border:none; color:#333;
    }
    /* --- Mobile --- */
    @media (max-width: 768px) {
      nav {
        position:fixed; top:0; left:-250px; bottom:0;
        width:220px; background:#fff; border-right:1px solid #ddd;
        flex-direction:column; align-items:flex-start;
        padding:20px 10px; gap:10px;
        transition:left 0.3s ease;
        z-index:1000;
      }
      nav.show { left:0; }
      nav a { padding:10px; width:100%; font-size:16px; }
      .menu-toggle { display:block; }
    }
  </style>
</head>
<body>
<div class="container">
<header>
  <div class="logo">
    <div class="brand">Lelé da Cuca</div>
  </div>

  <button class="menu-toggle" onclick="document.querySelector('nav').classList.toggle('show')">
    ☰
  </button>

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
