# MyPocket

Sistema de controle financeiro pessoal desenvolvido em PHP e MySQL. O projeto permite cadastrar, visualizar, editar e excluir receitas e despesas, além de apresentar informações financeiras organizadas por dia e por mês.

## Sobre o projeto

O MyPocket foi desenvolvido como um projeto acadêmico para praticar conceitos de Programação Orientada a Objetos (POO), integração entre PHP e banco de dados MySQL e desenvolvimento de interfaces web.

O sistema permite acompanhar as movimentações financeiras e visualizar informações como saldo atual, entradas, gastos e saldo mensal.

## Funcionalidades

* Cadastro de receitas
* Cadastro de despesas
* Visualização do saldo atual
* Visualização das entradas do mês
* Visualização dos gastos do mês
* Visualização dos gastos do dia
* Cálculo do saldo mensal
* Cálculo da média de gastos por dia
* Resumo financeiro diário
* Histórico de transações
* Edição de transações
* Exclusão de transações
* Validação dos valores
* Verificação de saldo para realização de despesas
* Armazenamento dos dados no MySQL

## Tecnologias utilizadas

* PHP
* MySQL
* HTML5
* CSS3
* Bootstrap 5
* PDO
* XAMPP
* phpMyAdmin

## Programação Orientada a Objetos

O projeto utiliza conceitos de Programação Orientada a Objetos.

A classe `Transacao` é uma classe abstrata utilizada como base para os tipos de movimentação financeira.

As classes `Receita` e `Despesa` herdam as características da classe `Transacao`.

A classe `Carteira` é responsável pelo gerenciamento das transações e pelo cálculo do saldo.

A classe `Custo` é responsável pelos cálculos relacionados aos gastos e aos resumos financeiros.

Os principais conceitos de POO utilizados são:

* Abstração
* Herança
* Encapsulamento
* Polimorfismo
* Classes abstratas
* Métodos
* Construtores
* Tipagem de dados

## Banco de dados

O projeto utiliza o MySQL para armazenar as transações financeiras.

O banco de dados utilizado pelo sistema se chama `mypocket`.

A principal tabela do projeto é `transacoes`, responsável por armazenar as receitas e despesas cadastradas.

Cada transação possui um identificador, tipo, valor, descrição e data.

O arquivo `mypocket.sql` contém os comandos necessários para criar o banco de dados e a tabela.

## Como instalar

### 1. Instalar o XAMPP

Instale o XAMPP e inicie os serviços Apache e MySQL.

### 2. Colocar o projeto no XAMPP

Copie a pasta `Mypocket` para:

```text
C:\xampp\htdocs\
```

O projeto deverá ficar localizado em:

```text
C:\xampp\htdocs\Mypocket\
```

### 3. Criar o banco de dados

Abra o phpMyAdmin pelo navegador:

```text
http://localhost/phpmyadmin
```

Acesse a aba SQL e execute o conteúdo do arquivo:

```text
mypocket.sql
```

O arquivo irá criar o banco de dados `mypocket` e a tabela `transacoes`.

### 4. Configurar a conexão

A conexão com o banco de dados está localizada em:

```text
config/conexao.php
```

A configuração padrão utiliza:

```text
Host: localhost
Banco: mypocket
Usuário: root
Senha: vazia
```

Caso o MySQL esteja configurado com uma senha diferente, altere a configuração no arquivo `conexao.php`.

### 5. Executar o sistema

Com o Apache e o MySQL funcionando, abra o navegador e acesse:

```text
http://localhost/Mypocket/
```

## Funcionamento

Na página inicial é possível cadastrar uma nova transação informando o tipo, valor, descrição e data.

As receitas são adicionadas ao saldo, enquanto as despesas são descontadas.

O sistema também verifica se existe saldo suficiente antes de permitir o cadastro de uma despesa.

As transações cadastradas aparecem no histórico, onde podem ser editadas ou excluídas.

Além disso, o sistema apresenta informações sobre as movimentações do mês, como entradas, gastos, saldo e média de gastos por dia.

## CRUD

O sistema possui as quatro operações principais de CRUD.

O cadastro de novas transações é realizado pelo arquivo `processa.php`.

A visualização das informações acontece principalmente pelo `index.php`.

A edição utiliza os arquivos `editar.php` e `atualizar.php`.

A exclusão das transações é realizada pelo arquivo `excluir.php`.

## Resumo financeiro

O sistema calcula automaticamente os dados financeiros a partir das transações cadastradas.

São apresentados:

* Saldo atual
* Entradas do mês
* Gastos do mês
* Gasto de hoje
* Saldo do mês
* Média de gasto por dia

Também existe um resumo diário com as informações de:

* Data
* Entrada
* Saída
* Resultado diário
* Saldo acumulado

## Objetivo acadêmico

O MyPocket foi desenvolvido com o objetivo de colocar em prática conhecimentos adquiridos durante o curso Técnico em Desenvolvimento de Sistemas.

O projeto envolve conceitos de desenvolvimento web, programação orientada a objetos, banco de dados, PHP, MySQL, CRUD, HTML, CSS, Bootstrap e PDO.