Lele da Cuca - Sistema de Gestão (local com XAMPP)

Coloque a pasta `Lele_da_Cuca` dentro de `C:\xampp\htdocs\` e abra no navegador: http://localhost/Lele_da_Cuca/

Passos rápidos:
1) Inicie o Apache e MySQL no XAMPP.
2) Importe o arquivo `database.sql` no phpMyAdmin (ou rode no MySQL):
   - Crie banco 'lele_da_cuca' ou rode o arquivo que já contém CREATE DATABASE
3) Acesse o sistema em: http://localhost/Lele_da_Cuca/

Credenciais de banco de dados (padrão no projeto):
- host: 127.0.0.1
- db: lele_da_cuca
- user: root
- pass: (vazio)

Arquivos importantes:
- `includes/db.php` - configuração PDO
- `includes/functions.php` - funções de acesso ao BD
- `assets/css/style.css` - estilos
- `assets/js/main.js` - JS do pedido

Observações:
- Se o PHP do terminal não estiver no PATH, use o Apache do XAMPP para rodar/validar.
- Melhorias sugeridas: autenticação, validações mais fortes, upload de imagens de produtos.
