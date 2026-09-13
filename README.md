# MyPocket

Aplicação web simples de controle financeiro pessoal, desenvolvida em PHP puro (orientado a objetos) com MySQL. Permite cadastrar transações de entrada (receitas) e saída (despesas), acompanhar o saldo atual e visualizar um extrato completo.

## Funcionalidades

- Cadastro e login de usuários (senha armazenada com hash via `password_hash`)
- Criação automática de uma carteira para cada novo usuário
- Cadastro de transações (receita/despesa) vinculadas a categorias
- Cálculo automático de saldo, total de entradas e total de saídas
- Resumo mensal por carteira (entradas, saídas, saldo final e desempenho)
- Extrato de transações com opção de exclusão
- Layout responsivo (adapta-se a telas menores)

## Tecnologias

- PHP (PDO para acesso ao banco de dados)
- MySQL
- HTML5 + CSS3

## Pré-requisitos

- [XAMPP](https://www.apachefriends.org/pt_br/index.html) instalado (inclui Apache, PHP e MySQL/MariaDB)

## Como executar no XAMPP

1. **Instale e abra o XAMPP.** No Painel de Controle do XAMPP, clique em **Start** ao lado de **Apache** e de **MySQL**.

2. **Crie o banco de dados.** Abra o navegador em `http://localhost/phpmyadmin`, vá na aba **SQL**, cole o conteúdo do arquivo `Banco.sql` e clique em **Executar**. Isso cria o banco `mypocket` e todas as tabelas.

3. **Cadastre as categorias padrão.** Ainda no phpMyAdmin, selecione o banco `mypocket` (menu lateral), abra a aba **SQL** novamente, cole o conteúdo do arquivo `Categorias.sql` e execute.

4. **Confira os dados de acesso ao banco.** Por padrão, o XAMPP usa usuário `root` sem senha, que já é o que está configurado em `classes/Conexao.php`:

   ```php
   private $host = 'localhost';
   private $db = 'mypocket';
   private $user = 'root';
   private $pass = '';
   ```

   Só altere esses valores se o seu MySQL do XAMPP tiver usuário/senha diferentes.

5. **Acesse a aplicação pelo navegador:**

   ```
   http://localhost/mypocket/cadastro.php
   ```

## Uso

1. Crie uma conta em **Criar Conta**.
2. Faça login com o e-mail e senha cadastrados.
3. Na tela principal, registre uma nova transação escolhendo categoria, descrição, valor e data.
4. Acompanhe o saldo atual, total de entradas, total de saídas e o extrato completo.
5. Exclua uma transação clicando em **Excluir** na linha correspondente do extrato.

## Categorias

As categorias ficam na tabela `categoria` e cada uma precisa ter o campo `tipo` definido exatamente como `Receita` ou `Despesa`, pois é esse valor que o sistema usa para calcular entradas, saídas e saldo. Novas categorias podem ser adicionadas diretamente pelo phpMyAdmin.

## Solução de problemas

- **Erro "Erro ao conectar com o banco"**: confira se o MySQL está iniciado no painel do XAMPP e se os dados em `classes/Conexao.php` (host, banco, usuário, senha) estão corretos.
- **Página sem estilo (CSS não carrega)**: confirme se o arquivo `style.css` está na raiz do projeto, na mesma pasta dos arquivos `.php`.
- **Select de categoria vazio**: confirme se o script `Categorias.sql` foi executado com sucesso no banco `mypocket`.