<?php
require_once __DIR__ . '/db.php';

function old($key) {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
}

// Funções para Clientes
function getClientes($pdo) {
    $stmt = $pdo->query('SELECT * FROM clientes ORDER BY id DESC');
    return $stmt->fetchAll();
}

function getCliente($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertCliente($pdo, $nome) {
    $stmt = $pdo->prepare('INSERT INTO clientes (nome, telefone, endereco) VALUES (?, ?, ?)');
    $telefone = null;
    $endereco = null;
    // if nome passed as array, support telefone/endereco optional (backwards compat)
    if(is_array($nome)){
        $telefone = $nome['telefone'] ?? null;
        $endereco = $nome['endereco'] ?? null;
        $nome = $nome['nome'] ?? '';
    }
    return $stmt->execute([$nome, $telefone, $endereco]);
}

function updateCliente($pdo, $id, $nome) {
    $telefone = null;
    $endereco = null;
    if(is_array($nome)){
        $telefone = $nome['telefone'] ?? null;
        $endereco = $nome['endereco'] ?? null;
        $nome = $nome['nome'] ?? '';
    }
    $stmt = $pdo->prepare('UPDATE clientes SET nome = ?, telefone = ?, endereco = ? WHERE id = ?');
    return $stmt->execute([$nome, $telefone, $endereco, $id]);
}

function deleteCliente($pdo, $id) {
    $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = ?');
    return $stmt->execute([$id]);
}

// Funções para Categorias
function getCategorias($pdo) {
    $stmt = $pdo->query('SELECT * FROM categorias ORDER BY nome_categoria ASC');
    return $stmt->fetchAll();
}

// Funções para Produtos
function getProdutos($pdo) {
    $stmt = $pdo->query('SELECT p.*, c.nome_categoria FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id ORDER BY p.id DESC');
    return $stmt->fetchAll();
}

function getProduto($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function insertProduto($pdo, $nome, $preco, $categoria_id) {
    $stmt = $pdo->prepare('INSERT INTO produtos (nome_produto, preco, categoria_id) VALUES (?, ?, ?)');
    return $stmt->execute([$nome, $preco, $categoria_id]);
}

function updateProduto($pdo, $id, $nome, $preco, $categoria_id) {
    $stmt = $pdo->prepare('UPDATE produtos SET nome_produto = ?, preco = ?, categoria_id = ? WHERE id = ?');
    return $stmt->execute([$nome, $preco, $categoria_id, $id]);
}

function deleteProduto($pdo, $id) {
    $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = ?');
    return $stmt->execute([$id]);
}

// Funções para Pedidos
function insertPedido($pdo, $cliente_id, $data_pedido, $valor_total) {
    $stmt = $pdo->prepare('INSERT INTO pedidos (cliente_id, data_pedido, valor_total) VALUES (?, ?, ?)');
    $stmt->execute([$cliente_id, $data_pedido, $valor_total]);
    return $pdo->lastInsertId();
}

function insertItemPedido($pdo, $pedido_id, $produto_id, $quantidade, $preco_unitario) {
    $stmt = $pdo->prepare('INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)');
    return $stmt->execute([$pedido_id, $produto_id, $quantidade, $preco_unitario]);
}

function getPedidosByMonth($pdo, $month, $year) {
    $stmt = $pdo->prepare("SELECT pe.*, c.nome as cliente_nome FROM pedidos pe JOIN clientes c ON pe.cliente_id = c.id WHERE MONTH(pe.data_pedido) = ? AND YEAR(pe.data_pedido) = ? ORDER BY pe.data_pedido DESC");
    $stmt->execute([$month, $year]);
    $pedidos = $stmt->fetchAll();

    foreach ($pedidos as &$p) {
        $stmt2 = $pdo->prepare('SELECT ip.*, pr.nome_produto FROM itens_pedido ip JOIN produtos pr ON ip.produto_id = pr.id WHERE ip.pedido_id = ?');
        $stmt2->execute([$p['id']]);
        $p['itens'] = $stmt2->fetchAll();
    }
    return $pedidos;
}

function getTotalVendasPeriodo($pdo, $month, $year) {
    $stmt = $pdo->prepare('SELECT SUM(valor_total) as total FROM pedidos WHERE MONTH(data_pedido) = ? AND YEAR(data_pedido) = ?');
    $stmt->execute([$month, $year]);
    $row = $stmt->fetch();
    return $row['total'] ?? 0;
}
