<?php
require_once __DIR__ . '/includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// se já logado, vai para index
if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($usuario && $senha) {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = ? LIMIT 1');
        $stmt->execute([$usuario]);
        $u = $stmt->fetch();
        if ($u && password_verify($senha, $u['senha_hash'] ?? $u['senha'])) {
            $_SESSION['user'] = $u['usuario'];
            header('Location: index.php');
            exit;
        }
    }
    $error = 'Usuário ou senha incorretos.';
}

// Layout
?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Lelé da Cuca</title>
    <link rel="stylesheet" href="/Lele_da_Cuca/assets/css/style.css">
    <style>
        /* Login específico */
        .login-page{display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px;background:linear-gradient(180deg,var(--muted),#fff)}
        .login-card{width:100%;max-width:460px;background:var(--white);padding:22px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.06)}
        .brand-top{display:flex;align-items:center;gap:12px;margin-bottom:12px}
        .brand-top .brand{font-size:20px;color:var(--pink);font-weight:700}
        .login-form{display:grid;gap:12px}
        .login-form .input{padding:12px;border-radius:8px;border:1px solid #eee}
        .login-actions{display:flex;justify-content:space-between;align-items:center}
        .error-box{background:#fff0f2;color:#b00020;padding:10px;border-radius:8px}
        @media(max-width:520px){.login-card{padding:16px}}
    </style>
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="brand-top">
            <div class="brand">Lelé da Cuca</div>
            <div style="color:#666;font-size:14px;margin-left:auto">Entre com seu Login</div>
        </div>

        <?php if ($error): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="login-form" method="post" action="">
            <input class="input" type="text" name="usuario" placeholder="Usuário" required value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>">
            <input class="input" type="password" name="senha" placeholder="Senha" required>
            <div class="login-actions">
                <button type="submit">Entrar</button>
                
            </div>
        </form>
    </div>
</div>
</body>
</html>
