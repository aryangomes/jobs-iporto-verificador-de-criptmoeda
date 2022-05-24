# Verificador de preços de criptomoedas

## Desenvolvimento da tarefa

A tarefa foi desenvolvida utilizando as tecnologias:

- Laravel 9
- PHP 8.1
- MySQL 8.0
- Git
- Docker
- Docker-compose

Foi utilizado a ferramenta *Sail* do Laravel para a criação do desenvolvimento local da tarefa.

Foram criados dois comandos:

- [SaveBidPriceOnDataBase](src/app/Console/Commands/SaveBidPriceOnDataBase.php): Salva o preço da criptomoeda recuperada da API da Binance no banco de dados

  - Comando: `php artisan c:saveBidPriceOnDataBase {criptomoeda}`
  - Argumento: `criptomoeda`  **(opcional)**  
    - Informar "symbol" ou "ticket" da criptomoeda, caso queira guardar somente o preço dela
  
- [CheckAvgBigPrice](src/app/Console/Commands/CheckAvgBigPrice.php): Recupera os preços de uma determinada criptomoeda no banco de dados e calcula o preço médio dela. Caso o preço atual da criptomoeda estiver menor que 0.5% do que o preço médio dela, um alerta é exibido na impressão do comando

  - Comando : `php artisan c:checkAvgBigPrice {criptomoeda}`
  - Argumento: `criptomoeda` **(obrigatório)**
    - Informar "symbol" ou "ticket" da criptomoeda

Seguindo o *Single Responsibility Principle* (Princípio da Responsabilidade Única) foram criadas classes (localizada na pasta [Actions](src/app/Http/Actions/)) para separar a lógica das tarefas:

- Recuperar os preços das criptomoedas ([RecuperarPrecoDeUmaCriptomoedaEspecifica](src/app/Http/Actions/RecuperarPrecoDeUmaCriptomoedaEspecifica.php) e [RecuperarPrecosDaCriptomoedas](src/app/Http/Actions/RecuperarPrecosDaCriptomoedas.php))
- Guardar os preços no banco de dados ([GuardarPrecoDaCriptomoeda](src/app/Http/Actions/GuardarPrecoDaCriptomoeda.php))  

- Calcular o preço médio ([CalcularPrecoMedioDaCriptomoeda](src/app/Http/Actions/CalcularPrecoMedioDaCriptomoeda.php)).

Para gravar e recuperar o preço e o nome da criptomoeda no banco dedados foi criada a classe [PrecoCriptomoeda](src/app/Models/PrecoCriptomoeda.php). Nela, há um escopo local que faz a busca dos preços das criptomoedas e filtra de acordo com a criptomoeda informada.

Por fim foi criada um [arquivo](src/lang/pt_br/comandos.php) para guardar algumas mensagens que são exibidas ao se executarem os dois comandos. Assim como, classes de testes ([SaveBidPriceOnDataBaseTest](src/tests/Feature/Commands/SaveBidPriceOnDataBaseTest.php) e [CheckAvgBigPriceTest](src/tests/Feature/Commands/CheckAvgBigPriceTest.php)), para assegurar que os comandos terão os resultados esperados.

## Instalação

1. Execute o comando `cd jobs-iporto-verificador-de-preco-de-criptomoeda/src` para acessar a pasta do projeto

2. Execute o comando `composer install` para instalar as dependências do projeto

3. Copie o arquivo `.env.example` e renomeie para `.env` (comando `cp .env.example .env`)

4. Edite o arquivo `.env` e preencha com essas informações:

- Ambiente de desenvolvimento

```
APP_ENV=local
```

- Dados do banco de dados

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE= INFORME_O_NOME_DA_TABELA
DB_USERNAME= INFORME_O_USUARIO
DB_PASSWORD= INFORME_A_SENHA
```

- Url da API da BINANCE

```
URL_API_BINANCE=https://testnet.binancefuture.com
```

5. Execute o comando `./vendor/bin/sail up -d` para subir os serviços necessários para que o projeto seja executado

6. Execute o comando `sail php artisan migrate` para executar os comandos de criação de banco de dados

- Caso o comando de migração apresente algum erro, execute os seguintes comandos nessa ordem e execute o passo 6 novamente:

  - sail artisan config:clear
  - sail artisan config:cache
  - sail artisan cache:clear

7. O projeto está pronto para ter os comandos da tarefa executados!
