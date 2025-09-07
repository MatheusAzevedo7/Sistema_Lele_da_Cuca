<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe POST para finalizar pedido
    $cliente_id = intval($_POST['cliente_id']);
    $data_pedido = $_POST['data_pedido'];
    $valor_total = floatval(str_replace(',', '.', $_POST['valor_total']));
    $items = json_decode($_POST['items_json'], true);

    if($cliente_id && $items && is_array($items)){
        $pedido_id = insertPedido($pdo, $cliente_id, $data_pedido, $valor_total);
        foreach($items as $it){
            insertItemPedido($pdo, $pedido_id, intval($it['produto_id']), intval($it['quantidade']), floatval($it['preco']));
        }
        echo '<script>alert("Pedido finalizado com sucesso.");window.location="pedidos.php";</script>';
        exit;
    }
}

$clientes = getClientes($pdo);
$produtos = getProdutos($pdo);

?>

<div class="card">
    <h2>Novo Pedido</h2>
    <form id="pedido-form" method="post">
        <div class="form-row">
            <select id="cliente" name="cliente_id" class="input" required>
                <option value="">Selecione o cliente</option>
                <?php foreach($clientes as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
                <?php endforeach; ?>
            </select>
            <input class="input" type="date" name="data_pedido" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <h3>Itens</h3>
        <div class="form-row">
            <select id="produto" class="input">
                <option value="">Selecione o produto</option>
                <?php foreach($produtos as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome_produto']); ?> - R$ <?php echo number_format($p['preco'],2,',','.'); ?></option>
                <?php endforeach; ?>
            </select>
            <input id="quantidade" class="input" type="number" min="1" placeholder="Quantidade">
            <button type="button" onclick='addItemToList(produtosMap)'>Adicionar</button>
        </div>

        <table class="table">
            <thead><tr><th>Produto</th><th>Quantidade</th><th>Preço</th><th>Subtotal</th><th></th></tr></thead>
            <tbody id="order-items-body"></tbody>
        </table>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
            <div class="total-box">Total: R$ <span id="valor_total">0,00</span></div>
            <div>
                <input type="hidden" id="valor_total_input" name="valor_total" value="0">
                <input type="hidden" id="items_json" name="items_json" value="">
                <button type="button" onclick="submitPedido()">Finalizar Pedido</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Disponibiliza os produtos para o JS como um objeto {id: produto}
    (function(){
        const arr = <?php echo json_encode($produtos); ?> || [];
        const map = {};
        arr.forEach(p => { map[p.id] = p; });
        window.produtosMap = map;
    })();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
