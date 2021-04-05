## RPG Battle

API de jogo de RPG de turno, com escolha de class, o monstro é gerado automáticamente.
Feito de forma simples, com calculos internos.

- Crie um nick(será seu usuário para identificar batalhas)
- Inicie uma partida selecionando o seu herói
- Faça rodada e visualize as informações de acordo com os acontecimentos
- Rodada finaliza, caso seja vencedor será gerado uma classificação.
- O histórico da batalha ficará registrado
- É possivel visualizar informações de batalhas especificas
- Visualiza informações de batalha
- Visualizar heróis/monstros e seus status
- Visualizar a classificaçao

<hr>

## Requerimentos

- PHP 7.3
- MySQL/MariaDB
- Composer

<hr>

## Instalação

- Faça um git clone de: *https://github.com/carlosedurf/rpg_battle.git*
- Faça uma copia de .env-example
    - Configure com a informação de seu banco de dados
- Rode o comando: *composer install*
- Rode o comando: *php artisan migrate --seed* **(seed poi nela esta configurada os heróis e monstros iniciais)**
- Pronto. Agora é só iniciar o servidor (Seja Local do PHP ou Apache2/NginX)

<hr>

## Rotas
<br/>

### User

-   (GET)     =>  /users/{user}/
    -   Exibe Informação do usuário enviado

-   (GET)     =>  /users/{user}/battles
    -   Exibe Batalhas do usuário

-   (POST)    =>  /users/create
    -   Cria usuário com nick informado
        -   (BODY)  =>  {"nick":"meu_nick"}

<hr>

### Hero

-   (GET)       =>  /heroes
    -   Exibe lista de heróis

<hr>

### Monster

-   (GET)       =>  /monsters
    -   Exibe lista de monstros

<hr>

### Classification

-   (GET)       =>  /classifications
    -   Exibe classificação

<hr>

### Battle

-   (GET)       =>  /battles/{battle}
    -   Exibe informação da batalha

-   (GET)       =>  /battles/{battle}/history
    -   Exibe histórico da batalha

-   (POST)      =>  /battles/start
    -   Inicia a batalha(caso já tenha um batalha em progresso não pode iniciar outra)
        -   (HEADER)    =>  user_id = 1     // Envia sempre o id do nick criado para vincular as batalhas
        -   (BODY)      =>  {"hero_id":1}   // recebe o ID do herói escolhido

-   (POST)      =>  /battles/{battle}/round
    -   A parte principal do sistema aqui o turnos acontecem da seguinte forma:
        -   (HEADER)    =>  user_id = 1     // Envia sempre o id do nick criado para vincular aos passos
        -   Primeiro passo é o iniciativa - primeira requisição é feita a soma de um 1d10 + agilidade de ambos herói/monstro o mais começa, caso empate gerasse outra requisição
        -   Segundo passo caso ataque:  é feito uma requisição de 1d10 + agilidade + força 
            Caso de defesa: é feito uma requisição de 1d10 + agilidade + defesa
            Caso o ataque é maior que a defesa será feito o calculo de dano
        -   Terceiro passo é feito o calculo de dano em uma requisição feito: com o pdd + força o resulatado irá retira o pdv do oponente e volta ao passo 1 na próxima requisição
        -   Caso o valor de pdv chegue a zero do herói/monstro é finalizada a batalha
        -   Caso o Herói ganhe será enviado para a tabela de classificação
        