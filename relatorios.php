<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

$pedidos = getPedidosByMonth($pdo, $month, $year);
$totalPeriodo = getTotalVendasPeriodo($pdo, $month, $year);

?>

<div class="card">
    <h2>Relatórios</h2>
    <form method="get" style="margin-bottom:12px">
        <div class="form-row">
            <select name="month" class="input">
                <?php for($m=1;$m<=12;$m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo ($m==$month)?'selected':''; ?>><?php echo str_pad($m,2,'0',STR_PAD_LEFT); ?></option>
                <?php endfor; ?>
            </select>
            <input class="input" type="number" name="year" value="<?php echo $year; ?>">
            <button type="submit">Filtrar</button>
            <button type="button" onclick="window.print()">Imprimir</button>
        </div>
    </form>

    <table class="table">
        <thead><tr><th>Data</th><th>Cliente</th><th>Itens</th><th>Valor Total</th></tr></thead>
        <tbody>
            <?php foreach($pedidos as $p): ?>
                <tr>
                    <td><?php echo $p['data_pedido']; ?></td>
                    <td><?php echo htmlspecialchars($p['cliente_nome']); ?></td>
                    <td>
                        <ul>
                        <?php foreach($p['itens'] as $it): ?>
                            <li><?php echo htmlspecialchars($it['nome_produto']); ?> (x<?php echo $it['quantidade']; ?>)</li>
                        <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>R$ <?php echo number_format($p['valor_total'],2,',','.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top:12px;font-weight:700">Total no período: R$ <?php echo number_format($totalPeriodo,2,',','.'); ?></div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
