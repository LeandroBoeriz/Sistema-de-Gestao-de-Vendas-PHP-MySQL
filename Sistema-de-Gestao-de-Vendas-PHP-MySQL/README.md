# Sistema de Gestão de Vendas — PHP e MySQL

Projeto **acadêmico e experimental**, desenvolvido em aula com orientação do professor, para estudar PHP, MySQL, formulários e relacionamentos entre tabelas. Esta versão é uma base para melhorias individuais futuras, especialmente com JavaScript.

> **Status:** protótipo acadêmico. Não é indicado para uso comercial ou publicação aberta com dados reais sem reforços de segurança, testes e controle de acesso.

## Funcionalidades atuais

- Cadastro e listagem de clientes (`clientes.php`)
- Cadastro e listagem de produtos com imagem opcional (`produtos.php`)
- Registro e histórico de vendas (`vendas.php`)
- Consulta de teste de conexão (`teste.php`)
- Banco de dados MySQL com tabelas relacionadas (`database.sql`)

## Tecnologias

PHP, MySQL, PDO, HTML. JavaScript e CSS responsivo são **melhorias planejadas**, não funcionalidades desta versão.

## Como executar localmente

1. Instale PHP com extensão `pdo_mysql`, MySQL e um servidor web local (por exemplo, XAMPP).
2. Coloque a pasta do projeto no diretório servido pelo Apache (por exemplo, `htdocs`).
3. No MySQL/phpMyAdmin, execute ou importe `database.sql`. Isso cria `sistema_vendas` e as tabelas `clientes`, `produtos` e `vendas`.
4. Copie `config.example.php` para `config.local.php` e ajuste host, usuário e senha do MySQL. **Não versione `config.local.php`.**
5. Inicie o Apache e o MySQL e acesse `http://localhost/Sistema-de-Gestao-de-Vendas-PHP-MySQL/clientes.php` (ajuste o caminho conforme a instalação).
6. Cadastre um cliente e um produto antes de registrar uma venda em `vendas.php`.

A pasta `uploads/` deve permitir escrita pelo servidor web para armazenar imagens enviadas.

## Estrutura

```text
clientes.php        # cadastro/listagem de clientes
produtos.php        # cadastro/listagem de produtos
vendas.php          # registro e histórico de vendas
conexao.php         # conexão PDO usando configuração local
teste.php           # teste de conexão MySQL
database.sql        # estrutura inicial do banco
config.example.php  # modelo público de configuração
config.local.php    # arquivo local (não incluído no Git)
.gitignore          # arquivos que não devem ser versionados
uploads/            # imagens locais ignoradas pelo Git
```

## Limitações conhecidas

- Não há autenticação, autorização, proteção CSRF ou edição/exclusão de cadastros.
- Uploads precisam de validação robusta de conteúdo, tamanho e armazenamento antes de qualquer publicação.
- As mensagens de erro dos formulários ainda podem expor detalhes internos de SQL; precisam ser tratadas antes de uso público.
- O histórico calcula valores usando o preço **atual** do produto; alterações posteriores podem distorcer vendas antigas.
- Ainda faltam validações de domínio, controle de estoque, testes automatizados e layout responsivo.
- O campo `telefone` é obrigatório no esquema SQL, embora opcional no formulário; o formulário envia uma string vazia quando não preenchido.

## Próximos passos / roadmap

- [ ] Melhorar interface com CSS responsivo
- [ ] Aplicar JavaScript para validação e busca/filtros de produtos
- [ ] Implementar edição e exclusão com segurança
- [ ] Criar login e controle de acesso
- [ ] Persistir preço unitário e total no momento da venda
- [ ] Implementar relatórios e gráficos
- [ ] Testar segurança, acessibilidade e instalação
- [ ] Avaliar necessidades de pequenos comércios antes de planejar versão comercial

## Autoria e contexto

Versão inicial desenvolvida durante aulas de Banco de Dados com orientação do professor. Melhorias posteriores podem ser documentadas no histórico de commits, distinguindo atividades acadêmicas de contribuições individuais. Verifique eventuais orientações da instituição/professor sobre publicação do código.

## Licença

Nenhuma licença de reutilização foi definida nesta versão. Antes de adicionar uma licença aberta ou comercializar código derivado, confirme que você tem os direitos necessários para isso.
