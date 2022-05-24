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

- [CheckAvgBigPrice](src/app/Console/Commands/CheckAvgBigPrice.php): Recupera os preços de uma determinada criptomoeda no banco de dados e calcula o preço médio dela. Caso o preço atual da criptomoeda estiver menor que 0.5% do que o preço médio dela, um alerta é exibido na impressão do comando

Seguindo o *Single Responsibility Principle* (Princípio da Responsabilidade Única) foram criadas classes (localizada na pasta [Actions](src/app/Http/Actions/)) para separar a lógica das tarefas:

- Recuperar os preços das criptomoedas ([RecuperarPrecoDeUmaCriptomoedaEspecifica](src/app/Http/Actions/RecuperarPrecoDeUmaCriptomoedaEspecifica.php) e [RecuperarPrecosDaCriptomoedas](src/app/Http/Actions/RecuperarPrecosDaCriptomoedas.php))
- Guardar os preços no banco de dados ([GuardarPrecoDaCriptomoeda](src/app/Http/Actions/GuardarPrecoDaCriptomoeda.php))  

- Calcular o preço médio ([CalcularPrecoMedioDaCriptomoeda](src/app/Http/Actions/CalcularPrecoMedioDaCriptomoeda.php)).

Para gravar e recuperar o preço e o nome da criptomoeda no banco dedados foi criada a classe [PrecoCriptomoeda](src/app/Models/PrecoCriptomoeda.php). Nela, há um escopo local que faz a busca dos preços das criptomoedas e filtra de acordo com a criptomoeda informada.

Por fim foi criada um [arquivo](src/lang/pt_br/comandos.php) para guardar algumas mensagens que são exibidas ao se executarem os dois comandos. Assim como, classes de testes ([SaveBidPriceOnDataBaseTest](src/tests/Feature/Commands/SaveBidPriceOnDataBaseTest.php) e [CheckAvgBigPriceTest](src/tests/Feature/Commands/CheckAvgBigPriceTest.php)), para assegurar que os comandos terão os resultados esperados.
