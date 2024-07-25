# Teste Kolmeya

## Descrição
Teste de Kolmeya para a vaga de desenvolvedor. O teste consiste em enviar um arquivo .csv com os dados de um arquivo de de cpfs e persitir em banco

## Funcionalidades Principais
- Rotas de post /documents/upload para enviar o arquivo csv

## Tecnologias Utilizadas
- PHP 8.2
- Redis
- Docker
- Laravel 10.10
- Mysql

## Como rodar o projeto
- Clone o repositório
- Crie um arquivo .env na raiz do projeto com as configurações do banco de dados
- Copie o conteúdo do arquivo .env.example para o .env
- Execute o comando `docker compose up -d --build`
- Execute o comando `docker exec -it kolmeya-php-fpm bash`
- Execute as migrations com o comando `php artisan migrate`
- Acesse a aplicação em http://localhost:8000
- Acesse o redis em http://localhost:8000/horizon
- Execute o comando `php artisan horizon` para iniciar o monitoramento do redis


## Estrutura de pastas
As pastas principais do projeto são:
- app\src - contem os arquivos do projeto desacoplados do framework
- app\jobs - contem os jobs do projeto
- app\Console\Commands - contem os comandos do projeto

## Postman
- Na raiz do projeto existe um arquivo chamado kolmeya-teste.postman_collection.json com as rotas do projeto
