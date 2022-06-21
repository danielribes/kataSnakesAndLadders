# Introducción

Esta es mi solución a la Kata SnakesAndLadders de VoxelGroup (https://github.com/VoxelGroup/Katas.Code.SnakesAndLadders)

En esta Kata se trata de resolver de manera progresiva 3 historias de usuario a partir de sus correspondientes test de aceptación.

# Enfoque adoptado

Aunque llevo unos mesos trasteando con C# por mi cuenta he preferido plantear la kata con **PHP** porque en estos momentos es mi lenguaje del día a día y me siento más comodo con él.

Teniendo en cuenta que mandan las historias de usuario y que estas ya están descritas usando lenguaje **Gherkin** me ha parecido que lo más eficaz era usar un enfoque de testing basado en **BDD (Behavior Drive Development)** en el que las necesidades de negocio marcan el flujo de tests con los que se va diseñando la aplicación.

En PHP esto se puede llevar a cabo usando el framework **Behat,** que trabaja a partir de historias de usuario (Features) y test de aceptación (Scenarios) descritos con Gherkin.

También he usado **PHPUnit** que es el framework habitual en PHP para test unitarios y TDD. En este caso Behat guía el desarrollo de cada Feature y PHPUnit me da soporte en aplicar tests a determinados elementos.

Investigando por el lado de C# he visto que hay algunos frameworks similares como por ejemplo SpecFlow, pero en este momento me sentía más cómodo en PHP asi que he ido adelante con PHP, Behat y PHPUnit.

He realizado un commit de cada historia de usuario, aunque para ser sinceros, visto ahora en perspectiva quizás hubiera sido mejor ser más atómico con los commits para tener cada test de aceptación en uno de ellos.

La solución usa además Composer que es un gestor de dependencias con el que se instalan todos los frameworks necesarios y se facilita el enrutamiento de todos los componentes. Una especie de Nuget del mundo PHP.

# Ejecutar el proyecto 

Para su funcionamiento, a parte de las dependencias especificas del proyecto y del uso de composer es necesario disponer de PHP, mínimo la versión CLI de PHP para ejecutarlo en un terminal.

Para facilitar las cosas he configurado un Dockerfile poder ejecutar un contenedor Docker que lo incluye todo. Este Dockerfile monta un contenedor con PHP 7.4, composer, vim, clona el proyecto con sus dependencias y lo deja listo para lanzar los test y evaluar su funcionamiento. Sin implicaciones en el sistema anfitrión más que disponer de _git_ y _Docker_ instalado.

Si no tienes docker en tu sistema lo puedes instalar [con estas instrucciones](https://docs.docker.com/get-docker/) 

## Ejecutar el Docker y arrancar el entorno

En tu terminal clona este repositorio con:

```
$ git clone https://github.com/danielribes/kataSnakesAndLadders.git
```

Muevete dentro del directorio _kataSnakesAndLadders_ y ejecuta:

```
$ docker compose up --build
```

Esto inicializara el contenedor, puede tardar unos minutos, luego ejecuta:

```
$ docker-compose run php /bin/bash
```
Esto te abre una sesión SSH con el contenedor y te situa en el path donde esta clonado todo el proyecto con sus dependencias instaladas, verás algo así:

```
root@3679266af703:/usr/local/src#
````

Puedes listar los contenidos de este directorio para confirmar:

````
root@3679266af703:/usr/local/src# ls -la
````

Verás algo así:

````
drwxr-xr-x  1 root root   4096 Jun 21 06:28 .
drwxr-xr-x  1 root root   4096 Jun  9 23:03 ..
-rw-r--r--  1 root root   6148 Jun 21 06:28 .DS_Store
drwxr-xr-x  8 root root   4096 Jun 21 06:28 .git
-rw-r--r--  1 root root  14196 Jun 21 06:28 README.md
drwxr-xr-x  2 root root   4096 Jun 21 06:28 bin
-rw-r--r--  1 root root    596 Jun 21 06:28 composer.json
-rw-r--r--  1 root root 139376 Jun 21 06:28 composer.lock
drwxr-xr-x  3 root root   4096 Jun 21 06:28 features
-rw-r--r--  1 root root    988 Jun 21 06:28 phpunit.xml
drwxr-xr-x  2 root root   4096 Jun 21 06:28 src
drwxr-xr-x 16 root root   4096 Jun 21 06:28 vendor
````

Aquí puedes ya ejecutar Behat para lanzar los tests y ver los resultados, con _bin/behat_:

```
root@3679266af703:/usr/local/src# bin/behat
````

Esto es lo que te mostrara:

```gherkin
Feature: US 1 - Token Can Move Across the Board
  As a player
  I want to be able to move my token
  So that I can get closer to the goal

  Scenario: UAT1 Start the game           # features/us1-move-across-board.feature:6
    Given the game is started             # FeatureContext::theGameIsStarted()
    When the token is placed on the board # FeatureContext::theTokenIsPlacedOnTheBoard()
    Then the token is on square 1         # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT2 Token on square 1   # features/us1-move-across-board.feature:11
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 4    # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT3 Token on square 8   # features/us1-move-across-board.feature:16
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    And then it is moved 4 spaces    # FeatureContext::thenItIsMovedSpaces()
    Then the token is on square 8    # FeatureContext::theTokenIsOnSquare()

Feature: US 2 - Player Can Win the Game
  As a player
  I want to be able to win the game
  So that I can gloat to everyone around

  Scenario: UAT1 Won the game        # features/us2-player-can-win-game.feature:6
    Given the token is on square 97  # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 100  # FeatureContext::theTokenIsOnSquare()
    And the player has won the game  # FeatureContext::thePlayerHasWonTheGame()

  Scenario: UAT2 Not won the game       # features/us2-player-can-win-game.feature:12
    Given the token is on square 97     # FeatureContext::theTokenIsOnSquare()
    When the token is moved 4 spaces    # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 97      # FeatureContext::theTokenIsOnSquare()
    And the player has not won the game # FeatureContext::thePlayerHasNotWonTheGame()

Feature: US 3 - Moves Are Determined By Dice Rolls
  As a player
  I want to move my token based on the roll of a die
  So that there is an element of chance in the game

  Scenario: UAT1 Dice result should be between 1-6 inclusive # features/us3-moves-determined-by-dice.feature:6
    Given the game is started                                # FeatureContext::theGameIsStarted()
    When the player rolls a die                              # FeatureContext::thePlayerRollsA()
    Then the result should be between 1-6 inclusive          # FeatureContext::theResultShouldBeBetweenInclusive()

  Scenario: UAT2 Player rolls a 4       # features/us3-moves-determined-by-dice.feature:11
    Given the player rolls a 4          # FeatureContext::thePlayerRollsA()
    When they move their token          # FeatureContext::theyMoveTheirToken()
    Then the token should move 4 spaces # FeatureContext::theTokenShouldMoveSpaces()

7 scenarios (7 passed)
24 steps (24 passed)
0m0.12s (9.36Mb)

```

Ya tienes el entorno en funcionamiento y has podido comprobar que todos los tests estan en verde! :clap:


# Desarrollo de la Kata

Los test de aceptación de cada historia de usuario guían la realización de esta kata. Las historias de usuario indican lo que se espera en cada fase y queda claro que se trata de implementar solo las funcionalidades requeridas por ellas. Ni una más ni una menos para tener en verde todos los tests.

## US 1 - Token Can Move Across the Board

Todo empieza con añadir en el fichero _us1-move-across-board.feature_ toda la descripción en Gherkin de esta primera US.

````gherkin
Feature: US 1 - Token Can Move Across the Board
  As a player
  I want to be able to move my token
  So that I can get closer to the goal

  Scenario: UAT1 Start the game
    Given the game is started
    When the token is placed on the board
    Then the token is on square 1

  Scenario: UAT2 Token on square 1
    Given the token is on square 1
    When the token is moved 3 spaces
    Then the token is on square 4

  Scenario: UAT3 Token on square 8
    Given the token is on square 1
    When the token is moved 3 spaces
    And then it is moved 4 spaces
    Then the token is on square 8
````

A partir de aquí Behat con:

````
$ behat/bin --append-snippets
````

Se generan automáticamente los métodos dentro de _bootstrap/FeatureContext.php_ que corresponden a cada linea _Given/When/And/Then_ de cada UAT. Los metodos estan vacios, solo actuan como punto de entrada, y se trata de ir un por uno aplicando el código necesario para pasar el test.

El proceso es ejecutar _bin/behat_ ver todos los test en rojo, proceder a resolver UAT por UAT implementando el mínimo código para pasar el test, tener test en verde y refactorizar. 

Estare repitiendo este ciclo durante las 3 user stories, y creando 3 ficheros _.feature_ con las user stories y los test de aceptación, para que Behat los procese para ir ejecuntando los tests.

En esta primera US veo necesario tener ya la class _Game_ que da sentido a _Given the game is started_ y será el punto de inicio de cualquier partida. Aparece también la class _Token_ con la que moverse por el tablero.

El juego y primer UAT empieza con:

````php
/**
* @Given the game is started
*/
public function theGameIsStarted()
{
    $this->game = new Game();
}

`````

Para cumplir con _When the token is placed on the board_ hago un Assert (de PHPUnit) para confirmar que _Game_ ha creado una instancia de _Token_. Si hay instancia de _Token_, el _Token_ esta listo y el juego ha empezado.

````php
/**
* @When the token is placed on the board
*/
public function theTokenIsPlacedOnTheBoard()
{
    Assert::assertInstanceOf("SnakesAndLadders\\Token", $this->game->token);
}
````
## US 2 - Player Can Win the Game

Aquí la cosa ya se pone más interesante, _Player_ cobra más importancia en los test de aceptación de esta user story, por lo que decido crear una class _Player_ que es la que mantiene el _estado_ del jugador y a su vez lo mueve por el tablero mediante _Token_ Esto implica también refactorizar _Game_ para que haga una instancia de _Player_ en vez de _Token_ A partir de este momento el juego arranca con un _Player_ que a su vez dispone de su propio _Token_

_Game_ adquiere también más importancia concentro en ella las _reglas del juego_, el check de si el jugador gana o no.

````php
namespace SnakesAndLadders;

use SnakesAndLadders\Player;

class Game 
{
    public $token;

    public function __construct() 
    {
        $this->player = new Player();          
    }

    public function checkPlayer($player)
    {
        $position = $player->getActualSquare();
        if($position == 100)
        {
            $player->setWin();
        }

        if($position > 100)
        {
            $player->moveToSquare($player->getOldPosition());
        }
    }
}
````

Esta combinación de _Game_/_Player_/_Token_ permite resolver los 2 test de aceptación y a la vez mantener responsabilidades separadas, mientras el resto de tests de la user story 1 se mantienen también en verde.

## US 3 - Moves Are Determined By Dice Rolls

En este paso creo una nueva class _Dice_. Separo de esta manera la responsabilidad de generar una tirada de dados. _Player_ en este momento es la class que asume el control de _Token_ y de _Dice_

````php
namespace SnakesAndLadders;

class Dice 
{

    public function roll()
    {
        return 4;
    }

}
````

Con esta _Dice_ donde el método _roll()_ devuelve un 4 ya se cumplen las condiciones de los tests relativas a los dados:
* Then the result should be between 1-6 inclusive
* Given the player rolls a 4

Pero decido refactorizarla a:

````php
namespace SnakesAndLadders;

class Dice 
{

    public function roll()
    {
        return random_int(1,6);
    }

}
````

Ya que le da más sentido sin implicar código extra fuera de la petición de los tests de aceptación.

Sigo usando _Asserts_ de PHPUnit para controlar resultados concretos dentro de un método que responde a una acción de un test de aceptación, por ejemplo si el valor de los dados está dentro de un rango calculado:

````php
/**
 * @Then the result should be between :arg1-:arg2 inclusive
 */
public function theResultShouldBeBetweenInclusive($arg1, $arg2)
{
    $sides = range($arg1, $arg2);
    Assert::assertContains($this->diceresult, $sides);
}
````
O para confirmar que realmente el movimiento del token ha correspondido con el número de pasos indicados por el dado:

````php
/**
 * @Then the token should move :arg1 spaces
 */
public function theTokenShouldMoveSpaces($arg1)
{
    $old = $this->game->player->getOldPosition();
    $new = $this->game->player->getActualSquare();

    $rslt = $new-$old;

    Assert::assertEquals($arg1, $rslt);
    
}
````

Finalizando esta tercera user story, todos los test de aceptación de cada una de ellas pasan en verde.

````gherkin 
Feature: US 1 - Token Can Move Across the Board
  As a player
  I want to be able to move my token
  So that I can get closer to the goal

  Scenario: UAT1 Start the game           # features/us1-move-across-board.feature:6
    Given the game is started             # FeatureContext::theGameIsStarted()
    When the token is placed on the board # FeatureContext::theTokenIsPlacedOnTheBoard()
    Then the token is on square 1         # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT2 Token on square 1   # features/us1-move-across-board.feature:11
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 4    # FeatureContext::theTokenIsOnSquare()

  Scenario: UAT3 Token on square 8   # features/us1-move-across-board.feature:16
    Given the token is on square 1   # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    And then it is moved 4 spaces    # FeatureContext::thenItIsMovedSpaces()
    Then the token is on square 8    # FeatureContext::theTokenIsOnSquare()

Feature: US 2 - Player Can Win the Game
  As a player
  I want to be able to win the game
  So that I can gloat to everyone around

  Scenario: UAT1 Won the game        # features/us2-player-can-win-game.feature:6
    Given the token is on square 97  # FeatureContext::theTokenIsOnSquare()
    When the token is moved 3 spaces # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 100  # FeatureContext::theTokenIsOnSquare()
    And the player has won the game  # FeatureContext::thePlayerHasWonTheGame()

  Scenario: UAT2 Not won the game       # features/us2-player-can-win-game.feature:12
    Given the token is on square 97     # FeatureContext::theTokenIsOnSquare()
    When the token is moved 4 spaces    # FeatureContext::theTokenIsMovedSpaces()
    Then the token is on square 97      # FeatureContext::theTokenIsOnSquare()
    And the player has not won the game # FeatureContext::thePlayerHasNotWonTheGame()

Feature: US 3 - Moves Are Determined By Dice Rolls
  As a player
  I want to move my token based on the roll of a die
  So that there is an element of chance in the game

  Scenario: UAT1 Dice result should be between 1-6 inclusive # features/us3-moves-determined-by-dice.feature:6
    Given the game is started                                # FeatureContext::theGameIsStarted()
    When the player rolls a die                              # FeatureContext::thePlayerRollsA()
    Then the result should be between 1-6 inclusive          # FeatureContext::theResultShouldBeBetweenInclusive()

  Scenario: UAT2 Player rolls a 4       # features/us3-moves-determined-by-dice.feature:11
    Given the player rolls a 4          # FeatureContext::thePlayerRollsA()
    When they move their token          # FeatureContext::theyMoveTheirToken()
    Then the token should move 4 spaces # FeatureContext::theTokenShouldMoveSpaces()

7 scenarios (7 passed)
24 steps (24 passed)
0m0.45s (9.43Mb)
````

# Posibles Mejoras

* Commits más atómicos, a nivel de cada test de aceptación para tener más visibilidad en los cambios que implica un test concreto

* Refactorizar _Player_ y _Token_ desdel punto de vista que tienen algunas cosas parecidas y _Player_ ahora puede que incluso más responsabilidades de las que debe gestionar. La gestión del movimiento es un poco reiterativa entre ambas clases esto deberia revisarse.

* Añadir test unitarios para algunos métodos en los que se realizan cálculos concretos, como por ejemplo para cubrir el _moveTo()_ de _Player_


# Pasos más adelante

* No está detallado en las historias de usuario de la Kata pero se podría terminar de implementar la lógica del juego, por ejemplo el control de si el token de un jugador cae en una casilla de escalera o de serpiente.

* Añadir la posibilidad de múltiples jugadores. Esto teniendo la class _Player_ que gestiona individualmente a un jugador y su _Token_ sería viable gestionando, por ejemplo, en _Game_ a un array de objetos _Player_.

* Explorar el uso de PHPSpec para usar la metodología de _especificación mediante ejemplos_ que encaja con el uso de Behat y permite un proceso completo de BDD. Pero para eso ya quizás mejor pasarse a C# ;) y explorar SpecFlow 


# Conclusiones finales

Me ha gustado esta kata. Obligarse a desarrollar unas funcionalidades sin salirse de lo que se pide en las historias de usuario y generando test de aceptación que dejan cubiertas todas la peticiones de negocio.

Ha sido mi primera vez con Behat y un proceso muy básico de BDD. Así que me he concentrado en el uso de Behat a nivel metodología, y en ir creando código que pasara los test de aceptación y ya está. Tenía visto Behat, Gherkin y el concepto de tests orientado a funcionalidades pero no había trabajado hasta ahora con él. Ver que la Kata apuntaba a este uso fue un reto interesante.

Cuando quieras podemos conversar en más detalle del enfoque de la Kata y ver en directo el funcionamiento del código :)

Entregada esta versión en PHP creo que fuera de este proceso de selección y con más tiempo intentaré repetirla con C# y SpecFlow, para comparar enfoques y sobretodo porque una buena manera de aprender un nuevo lenguaje es hacerlo a la vez guiado por tests. 

