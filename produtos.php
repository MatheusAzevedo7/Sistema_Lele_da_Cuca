<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$action = $_GET['action'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_produto']);
    $preco = floatval(str_replace(',', '.', $_POST['preco']));
    $categoria_id = intval($_POST['categoria_id']);

    if(isset($_POST['id_edit']) && !empty($_POST['id_edit'])){
        updateProduto($pdo, $_POST['id_edit'], $nome, $preco, $categoria_id);
    } else {
        insertProduto($pdo, $nome, $preco, $categoria_id);
    }
    header('Location: produtos.php'); exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    deleteProduto($pdo, $_GET['id']);
    header('Location: produtos.php'); exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $produtoEdit = getProduto($pdo, $_GET['id']);
}

$produtos = getProdutos($pdo);
$categorias = getCategorias($pdo);
?>

<div class="card">
    <h2>Produtos</h2>
    <form method="post">
        <div class="form-row">
            <input class="input" type="text" name="nome_produto" placeholder="Nome do produto" value="<?php echo isset($produtoEdit) ? htmlspecialchars($produtoEdit['nome_produto']) : '';?>" required>
            <input class="input" type="text" name="preco" placeholder="Preço (ex: 12.50)" value="<?php echo isset($produtoEdit) ? number_format($produtoEdit['preco'], 2, ',', '.') : '';?>" required>
            <select name="categoria_id" class="input" required>
                <option value="">Selecione a categoria</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($produtoEdit) && $produtoEdit['categoria_id']==$cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['nome_categoria']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($produtoEdit)): ?>
                <input type="hidden" name="id_edit" value="<?php echo $produtoEdit['id']; ?>">
            <?php endif; ?>
            <button type="submit"><?php echo isset($produtoEdit) ? 'Atualizar' : 'Cadastrar'; ?></button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr><th>ID</th><th>Produto</th><th>Preço</th><th>Categoria</th><th>Ações</th></tr>
        </thead>
        <tbody>
            <?php foreach($produtos as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['nome_produto']); ?></td>
                    <td>R$ <?php echo number_format($p['preco'],2,',','.'); ?></td>
                    <td><?php echo htmlspecialchars($p['nome_categoria']); ?></td>
                    <td class="actions">
                        <a href="produtos.php?action=edit&id=<?php echo $p['id']; ?>"><button>Editar</button></a>
                        <a href="produtos.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Excluir produto?');"><button>Excluir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
