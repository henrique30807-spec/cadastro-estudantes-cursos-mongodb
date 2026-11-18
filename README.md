# Cadastro Acadêmico — PHP e MongoDB

Sistema acadêmico simples para cadastrar estudantes, cursos e matrículas, desenvolvido para a atividade de PHP com MongoDB da UNIPAR.

## Requisitos

- PHP 8.1 ou superior
- Composer 2 ou superior
- Extensão PHP `mongodb` habilitada (versão 1.21 ou superior)
- Conta e cluster no MongoDB Atlas (ou MongoDB local)

## Instalação

1. Abra esta pasta no Visual Studio Code.
2. Confirme a extensão com `php -m` (a lista deve conter `mongodb`).
3. No terminal integrado, instale as dependências:

   ```bash
   composer install
   ```

4. Copie `.env.example` para `.env`.
5. No arquivo `.env`, informe a connection string do MongoDB Atlas em `MONGODB_URI` e mantenha `MONGODB_DATABASE="unipar_academico"`.
6. No MongoDB Atlas, crie um usuário de banco de dados e libere o IP da máquina em Network Access. Use uma senha própria para este projeto.
7. Inicie o servidor PHP:

   ```bash
   php -S localhost:8000
   ```

7. Acesse `http://localhost:8000` no navegador.

### Abrir no seu notebook

Nesta entrega, basta dar dois cliques em `INICIAR-SISTEMA.bat`. O arquivo inicia o sistema e abre a página inicial no navegador. Para isso funcionar, mantenha o arquivo `.env` local e a pasta `vendor/` no projeto; eles não devem ser enviados ao GitHub.

O endereço da interface é sempre:

```text
http://127.0.0.1:8000
```

## Configuração do MongoDB Atlas

A connection string pode ser obtida no Atlas em: Cluster > Connect > Drivers > PHP. Ela tem formato semelhante a:

```text
mongodb+srv://USUARIO:SENHA@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
```

Substitua os valores de exemplo pelos dados do seu cluster. Se a senha tiver caracteres especiais, faça a codificação adequada para uso em URI. Nunca publique o arquivo `.env` ou credenciais no GitHub.

## Estrutura do banco e relacionamento

O banco `unipar_academico` utiliza três coleções independentes:

- `estudantes`: nome, CPF, e-mail, telefone, data de nascimento, registro acadêmico e endereço (rua, número, complemento, bairro, cidade, estado e CEP).
- `cursos`: nome, código, descrição, carga horária, categoria e modalidade.
- `matriculas`: relacionamento entre estudantes e cursos, guardando `estudante_id` e `curso_id` como referências (`ObjectId`), a data e o status da matrícula.

A coleção `matriculas` implementa o relacionamento muitos-para-muitos: um estudante pode ter várias matrículas e um curso pode aparecer em várias matrículas. Os dados completos de estudantes e cursos não são copiados para dentro da matrícula.

O endereço fica como subdocumento do estudante, pois é um conjunto de informações pertencente ao próprio estudante; isso não substitui nem prejudica o relacionamento entre estudantes e cursos.

## Funcionalidades

- Cadastro, listagem, edição e exclusão de estudantes.
- Cadastro, listagem, edição e exclusão de cursos.
- Matrícula de estudantes em cursos.
- Listagem e exclusão de matrículas.
- Validação de campos obrigatórios, e-mail, CPF, registro acadêmico e código de curso duplicados.
- Exclusão das matrículas relacionadas quando um estudante ou curso é removido.

## Entrega no GitHub

Antes de publicar, confirme que `.env` não está sendo enviado. O arquivo `.gitignore` já ignora `.env` e `vendor/`.

```bash
git init
git add .
git commit -m "Projeto de cadastro acadêmico com PHP e MongoDB"
git branch -M main
git remote add origin URL_DO_REPOSITORIO
git push -u origin main
```

## Referências

- [Documentação MongoDB para PHP](https://www.mongodb.com/pt-br/docs/languages/php/)
- [Manual da biblioteca PHP do MongoDB](https://www.mongodb.com/docs/php-library/current/)
- [Manual PHP — extensão MongoDB](https://www.php.net/manual/en/book.mongodb.php)
- [Biblioteca oficial MongoDB PHP no GitHub](https://github.com/mongodb/mongo-php-library)
- [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/)

