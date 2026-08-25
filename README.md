# Almoxarifado

Sistema web de controle de estoque e inventário, desenvolvido em PHP puro, com cadastro de produtos, fornecedores e controle de movimentações de entrada e saída.

Acesse o projeto no ar: [em breve]

## Sobre o projeto
Este sistema foi desenvolvido como projeto de portfólio, aplicando na prática os principais conceitos de desenvolvimento web sem depender de um framework: modelagem de banco de dados relacional, CRUD completo, autenticação de usuários, testes automatizados e deploy em produção.

## Funcionalidades
- Cadastro, edição, listagem e exclusão de Produtos, com saldo calculado em tempo real
- Cadastro, edição, listagem e exclusão de Forneedores, vinculados aos produtos
- Controle de Movimentações, com tipo (entrada/saída), quantidade, data e responsável
- Alerta de automático de estoque abaixo do mínimo cadastrado
- Relatórios com filtro por período, categoria e tipo, e exportação em CSV
- Autenticação de usuários (login/logout) com sessão PHP e senha protegida por hash
- Testes automatizados de regras de negócio em PHPunit

## Tecnologias utilizadas
- PHP 8.2 (orientado a objetos, sem framework)
- MySQL/MariaDB (banco de dados)
- PDO com prepared statements (acesso ao banco)
- Tailwind CSS (estilização)
- JavaScript (Fetch API)
- PHPUnit (testes automatizados)
- XAMPP (ambiente local)

## Como rodar o projeto localmente
## Pré-requisitos
- XAMPP instalado (Apache + PHP + MySQL)
- Composer instalado
- Git instalado

## Passo a passo
```bash

# Clone o repositório dentro de C:\xampp\htdocs
gitclone https://github.com/winterq3/almoxarifado.git estoque
cd estoque

# Instale as dependências PHP
composer install

# Inicie o Apache e o MySQL pelo XAMPP Control Panel

# No phpMyAdmin, crie um banco chamado estoque_db e rode os srips em database/
```

Depois disso, acesse http://localhost/estoque/public/login.html no navegador e crie uma conta.

## Rodando os testes
```bash
vendo\bin\phpunit
```

## Estrutura do projeto

O sistema é organizado em três entidades principais, seguindo uma arquitetura em camadas inspirada em MVC: 

Fornecedor -> possui vários produtos
Produto -> pertence a um fornecedor, possui várias movimentações
Movimentação -> pertence a um produto, registra tipo, quantidade e responsável

O saldo de cada produto nunca é armazenado diretamente - é sempre recalculado a partir do histórico de movimentações, o que evita inconsistência.

## Capturas de tela

**Login**
[em breve]

**Dashboard**
[em breve]

**Página de produtos**
[em breve]

**Detalhes do produto**
[em breve]

**Página de movimentações**
[em breve]

**Página de fornecedores**
[em breve]

**Página de relatórios**
[em breve]

## Autor

João Pedro Tavares Oliveira

Desenvolvido como projeto de porfólio. 
