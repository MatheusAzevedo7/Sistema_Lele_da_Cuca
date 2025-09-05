<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$action = $_GET['action'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['nome'])){
        $nome = trim($_POST['nome']);
        $telefone = !empty(trim($_POST['telefone'] ?? '')) ? trim($_POST['telefone']) : null;
        $endereco = !empty(trim($_POST['endereco'] ?? '')) ? trim($_POST['endereco']) : null;
        if(!empty($nome)){
            $data = ['nome' => $nome, 'telefone' => $telefone, 'endereco' => $endereco];
            if(isset($_POST['id_edit']) && !empty($_POST['id_edit'])){
                updateCliente($pdo, intval($_POST['id_edit']), $data);
            } else {
                insertCliente($pdo, $data);
            }
        }
        header('Location: clientes.php'); exit;
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    deleteCliente($pdo, $_GET['id']);
    header('Location: clientes.php'); exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $clienteEdit = getCliente($pdo, $_GET['id']);
}

$clientes = getClientes($pdo);
?>

<div class="card">
    <h2>Clientes</h2>
    <form method="post">
        <div class="form-row">
            <input class="input" type="text" name="nome" placeholder="Nome completo" value="<?php echo isset($clienteEdit) ? htmlspecialchars($clienteEdit['nome']) : '';?>" required>
            <input class="input" type="text" name="telefone" placeholder="Telefone (opcional)" value="<?php echo isset($clienteEdit) ? htmlspecialchars($clienteEdit['telefone'] ?? '') : '';?>">
            <input class="input" type="text" name="endereco" placeholder="Endereço (opcional)" value="<?php echo isset($clienteEdit) ? htmlspecialchars($clienteEdit['endereco'] ?? '') : '';?>">
            <?php if(isset($clienteEdit)): ?>
                <input type="hidden" name="id_edit" value="<?php echo $clienteEdit['id']; ?>">
            <?php endif; ?>
            <button type="submit"><?php echo isset($clienteEdit) ? 'Atualizar' : 'Cadastrar'; ?></button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr><th>ID</th><th>Nome</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php foreach($clientes as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['nome']); ?><br><small><?php echo htmlspecialchars($c['telefone'] ?? ''); ?> <?php if(!empty($c['endereco'] ?? '')){ echo '<br>'.htmlspecialchars($c['endereco']); } ?></small></td>
                    <td class="actions">
                        <a href="clientes.php?action=edit&id=<?php echo $c['id']; ?>"><button>Editar</button></a>
                        <a href="clientes.php?action=delete&id=<?php echo $c['id']; ?>" onclick="return confirm('Excluir cliente?');"><button>Excluir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
