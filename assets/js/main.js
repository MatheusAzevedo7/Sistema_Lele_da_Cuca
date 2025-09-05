// Funções para manipular a lista temporária de itens do pedido
const orderItems = [];

function addItemToList(produtosMap) {
    produtosMap = produtosMap || window.produtosMap || {};
    const produtoSelect = document.getElementById('produto');
    const quantidadeInput = document.getElementById('quantidade');
    const produtoId = String(produtoSelect.value);
    const quantidade = parseInt(quantidadeInput.value || '0', 10);
    if (!produtoId || quantidade <= 0) return;

    const produto = produtosMap[produtoId] || produtosMap[parseInt(produtoId)];
    if(!produto) return;

    const item = {produto_id: produtoId, nome: produto.nome_produto, preco: parseFloat(produto.preco), quantidade};
    orderItems.push(item);
    renderOrderItems();
    produtoSelect.selectedIndex = 0;
    quantidadeInput.value = '';
}

function renderOrderItems() {
    const tbody = document.getElementById('order-items-body');
    tbody.innerHTML = '';
    let total = 0;
    orderItems.forEach((it, idx) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${it.nome}</td>
            <td>${it.quantidade}</td>
            <td>R$ ${it.preco.toFixed(2).replace('.',',')}</td>
            <td>R$ ${(it.preco * it.quantidade).toFixed(2).replace('.',',')}</td>
            <td><button type="button" onclick="removeItem(${idx})">Remover</button></td>
        `;
        tbody.appendChild(tr);
        total += it.preco * it.quantidade;
    });
    document.getElementById('valor_total').innerText = total.toFixed(2).replace('.',',');
    document.getElementById('valor_total_input').value = total.toFixed(2);
}

function removeItem(index) {
    orderItems.splice(index,1);
    renderOrderItems();
}

function produtosToMap(produtos) {
    const map = {};
    produtos.forEach(p => map[p.id] = p);
    return map;
}

function submitPedido() {
    if(orderItems.length === 0) { alert('Adicione pelo menos um item ao pedido.'); return; }
    const form = document.getElementById('pedido-form');
    // adicionar items como JSON em um input hidden
    const itemsInput = document.getElementById('items_json');
    itemsInput.value = JSON.stringify(orderItems);
    form.submit();
}
