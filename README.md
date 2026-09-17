# Almoxarifado

Sistema web de controle de estoque e inventário, desenvolvido em PHP puro, com cadastro de produtos, fornecedores e controle de movimentações de entrada e saída.

Acesse o projeto no ar: [estoque-php-production.up.railway.app](https://estoque-php-production.up.railway.app/login.html)

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
- MySQL (banco de dados)
- PDO com prepared statements (acesso ao banco)
- Tailwind CSS (estilização)
- JavaScript (Fetch API)
- PHPUnit (testes automatizados)
- Docker (containerização)
- XAMPP (deploy)

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
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/2a02771e-016f-4d75-a773-1d3bfb53ebe8" />

**Cadastro**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/88044821-376f-4674-9c07-7df9f12a2013" />

**Dashboard**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/160caf85-abbb-4b5c-b7f9-af0209c43f9d" />

**Página de produtos**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/479c219a-485a-4da7-8ab2-cd35fa00f2fe" />

**Cadastro de produto**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/7a328ee9-9b0f-4974-9669-8eae12837857" />

**Detalhes do produto**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/285fdd51-95d4-454a-9954-cda34b6f16c7" />

**Página de movimentações**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/cfc67808-bbc5-4c0b-94b7-7a9b04d9630b" />

**Página de fornecedores**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/aa8f979e-3d0a-4c70-9908-5ae2213e931d" />

**Cadastro de fornecedores**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/d539adbf-eb4c-48ef-9f92-a9b21ba84c7e" />

**Página de relatórios**
<img width="1599" height="899" alt="Image" src="https://github.com/user-attachments/assets/7b0b056b-c455-4352-8e42-332ac2736631" />

## Autor

João Pedro Tavares Oliveira

Desenvolvido como projeto de porfólio. 
