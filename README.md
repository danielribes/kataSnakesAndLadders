# Introducción

Esta es mi solución a la Kata SnakesAndLadders de VoxelGroup (https://github.com/VoxelGroup/Katas.Code.SnakesAndLadders)

Esta Kata describe el funcionamiento del juego Snakes And Ladders, pero expone unos requerimientos muy concretos:

* Desarrollar la lógica del juego de manera independiente al frontend que finalmente se pueda usar. Basicamente nos estan pidiendo crear una libreria backend, con la lógica del juego, que se pueda acoplar a diferentes escenarios de uso.

* Implementar la lógica del juego con una primera _Feature_ que se ha divido en 3 historias de usuario. Tener en cuenta esta única _feature_ es importante porque las 3 historias de usuario que la componen se centran solo en el movimiento del jugador en el tablero, en ningún momento contemplan nada relativo a la mecanica concreta del juego, por ejemplo a que hacer cuando el jugado cae en una casilla de _serpiente_ o en una casilla de _escalera._

  En las 3 historias de usuario tampoco se hace referencia a gestionar multiples jugadores o poder jugar contra el ordenador. Esto es importante porque nos da a entender que no deberiamos desarrollar nada más alla de lo que nos detalla la _Feature._

A parte de estos requerimientos, nos piden una aplicación de consola para poder probarla, usar un lenguaje orientado a objetos, y ponerle cariño.

Por lo tanto en esta Kata se trata basicamente de resolver de manera progresiva 3 historias de usuario a partir de sus correspondientes test de aceptación y añadir un pequeña aplicación que permita testearlas, más alla de lanzar los propios tests que acompañen a la libreria.

# Enfoque adoptado

Aunque llevo unos mesos trasteando con C# por mi cuenta he preferido plantear la kata con **PHP** porque en estos momentos es mi lenguaje del día a día y me siento más comodo con él.

Teniendo en cuenta que mandan las historias de usuario y que estas ya están descritas usando lenguaje **Gherkin** me ha parecido que lo más eficaz era usar un enfoque de testing basado en **BDD (Behavior Drive Development)** en el que las necesidades de negocio marcan el flujo de tests con los que se va diseñando la aplicación.

En PHP esto se puede llevar a cabo usando el framework **Behat,** que trabaja a partir de historias de usuario (Features) y test de aceptación (Scenarios) descritos con Gherkin.

También he usado **PHPUnit** que es el framework habitual en PHP para test unitarios y TDD. En este caso Behat guía el desarrollo de cada Feature y PHPUnit me da soporte en aplicar tests a determinados elementos.

Investigando por el lado de C# he visto que hay algunos frameworks similares como por ejemplo SpecFlow, pero en este momento me sentía más cómodo en PHP asi que he ido adelante con PHP, Behat y PHPUnit.

Mientras he desarrollado las funcionalidades basicas, guiado por los tests de aceptación, he realizado un commit de cada historia de usuario, aunque para ser sinceros, visto ahora en perspectiva quizás hubiera sido mejor ser más atómico con los commits para tener cada test de aceptación en uno de ellos.

La solución usa además Composer que es un gestor de dependencias con el que se instalan todos los frameworks necesarios y se facilita el enrutamiento de todos los componentes. Una especie de Nuget del mundo PHP.

# Estructura del proyecto

Mi solución tiene dos partes facilmente identificables viendo el código.

* La libreria que implenta las funcionalidades detalladas por las 3 historias de usuario y sus tests

* Una pequeña aplicación de consola que haciendo uso de los componentes de la libreria permite jugar simulando las acciones descritas por cada US

Todo el código se encuentra dentro de _src:_

* **src/Game** contiene las 2 classes que forman el core de la aplicación de consola

* **src/Lib** contiene las 4 classes que forman el core del backend, de la libreria y que se ajustan a cada US y sus correspondientes UAT
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
drwxr-xr-x  15 root  root     480 28 jun 01:41 .
drwxr-xr-x  13 root  root     416 28 jun 01:40 ..
drwxr-xr-x  14 root  root     448 28 jun 02:26 .git
-rw-r--r--   1 root  root       9 28 jun 01:40 .gitignore
-rw-r--r--   1 root  root     387 26 jun 20:28 Dockerfile
-rw-r--r--   1 root  root   20579 28 jun 03:10 README.md
drwxr-xr-x   6 root  root     192 26 jun 20:28 bin
-rw-r--r--   1 root  root     635 26 jun 20:43 composer.json
-rw-r--r--   1 root  root  139382 26 jun 20:43 composer.lock
-rw-r--r--   1 root  root     120 26 jun 20:28 docker-compose.yml
drwxr-xr-x   7 root  root     224 26 jun 20:28 features
-rw-r--r--   1 root  root     345 26 jun 21:04 game.php
-rw-r--r--   1 root  root     988 26 jun 20:28 phpunit.xml
drwxr-xr-x   5 root  root     160 28 jun 01:40 src
drwxr-xr-x  17 root  root     544 26 jun 20:43 vendor
````
## Lanzar los tests 

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

Indicando que la Libreria desarrollada pasa todo los test de cada US. Observa que por cada linea de cada test de aceptación nos indica a su derecha que metodo de _features/bootstrap/FeaturesContext.php_ implementa el código del test.

Ya tienes el entorno en funcionamiento y has podido comprobar que todos los tests estan en verde! :clap:

## Ejecutar la aplicación 

En la raiz del proyecto tienes **game.php** que es el punto de entrada a la aplicación de consola, se ejecuta como un script php:

```php
$ php game.php 
```

Te mostrara:

```
Player at square: 1
```

Indicando que se ha iniciado el juego

**game.php** acepta dos parametros de linea de comandos, **--diceroll** y **--moveto**:

* **--dicerolls** para indicar una tirada de dados. La aplicación genera una tirada aleatoria y indica el movimiento realizado por el jugador:

```php
$ php game.php --dicerolls 
```

Te mostrara algo así:

```
Player at square: 1
Dice show: 3
Player move token 3 squares
Player at square: 4
```

* **--moveto=[valor]** para indicar un avance concreto de casillas. Esto te permite mover el token a una casilla concreta para comprobar que se mueve correctamente y por ejemplo simular que el jugador gana el juego. Para este parametro es obligatorio indicar un _valor_:

```php
$ php game.php --moveto=43
```

Te mostrara:

```
Player at square: 1
Player move token 43 squares
Player at square: 44
```

Por ejemplo movemos el jugador 99 casillas, como sale de la 1, lo hacemos ganar:

```php
$ php game.php --moveto=99
```

Te mostrara:

```
Player at square: 1
Player move token 99 squares
Player at square: 100
Player WIN!!!!
```

Y finalmente podemos combinar los dos parametros, de manera que llevamos el jugador a un avance concreto y luego lo movemos con los dados:

```php
$ php game.php --moveto=32 --dicerolls
```

Te mostrara algo así:

```
Player at square: 1
Player move token 32 squares
Player at square: 33
Dice show: 4
Player move token 4 squares
Player at square: 37
```

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

En esta primera US veo necesario tener ya la class _GameEngine_ que da sentido a _Given the game is started_ y será el punto de inicio de cualquier partida. Aparece también la class _Token_ con la que moverse por el tablero.

El juego y primer UAT empieza con:

````php
/**
* @Given the game is started
*/
public function theGameIsStarted()
{
    $this->game = new GameEngine();
}

`````

Para cumplir con _When the token is placed on the board_ hago un Assert (de PHPUnit) para confirmar que _Game_ ha creado una instancia de _Token_. Si hay instancia de _Token_, el _Token_ esta listo y el juego ha empezado.

````php
/**
* @When the token is placed on the board
*/
public function theTokenIsPlacedOnTheBoard()
{
    Assert::assertInstanceOf("SnakesAndLadders\\Lib\\Token", $this->game->player->getToken());
}
````
## US 2 - Player Can Win the Game

Aquí la cosa ya se pone más interesante, _Player_ cobra más importancia en los test de aceptación de esta user story, por lo que decido crear una class _Player_ que es la que mantiene el _estado_ del jugador y a su vez lo mueve por el tablero mediante _Token_ Esto implica también refactorizar _GameEngine_ para que haga una instancia de _Player_ en vez de _Token_ A partir de este momento el juego arranca con un _Player_ que a su vez dispone de su propio _Token_

_GameEngine_ adquiere también más importancia concentro en ella las _reglas del juego_, el check de si el jugador gana o no.

````php
namespace SnakesAndLadders\Lib;

use SnakesAndLadders\Lib\Player;

class GameEngine 
{
    public $token;
    public $player;


    public function __construct() 
    {
        $this->player = new Player();          
    }

    public function checkPlayer()
    {
        $position = $this->player->getActualSquare();
        if($position == 100)
        {
            $this->player->setWin();
        }

        if($position > 100)
        {
            $this->player->moveToSquare($this->player->getOldPosition());
        }
    }
}
````

Esta combinación de _GameEngine_/_Player_/_Token_ permite resolver los 2 test de aceptación y a la vez mantener responsabilidades separadas, mientras el resto de tests de la user story 1 se mantienen también en verde.

## US 3 - Moves Are Determined By Dice Rolls

En este paso creo una nueva class _Dice_. Separo de esta manera la responsabilidad de generar una tirada de dados. _Player_ en este momento es la class que asume el control de _Token_ y de _Dice_

````php
namespace SnakesAndLadders\Lib;

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

Pero la refactorizo a:

```php
namespace SnakesAndLadders\Lib;

class Dice 
{

    public function roll()
    {
        return random_int(1,6);
    }

}
```

Para que tenga un funcionamiento real.

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

# Desarrollo de la aplicación de consola

Como ya he comentado la aplicación de consola actua como un frontend para poner a prueba la libreria. Para su desarrollo he usado el componente _Console_ del Symfony Framework, que permite disponer de los elementos basicos para crear una aplicación de consola, gestiónar input via parámetros o teclado y gestionar su output.

En _src/Game/GameCommand.php_ se encuentra el core de la aplicación de consola. Es una class que hereda del la _class_ _Command_ de Symfony y sobreescribe dos metodos: _configure_ donde especificamos los parámetros que aceptara la aplicación, instrucciones, etc. y _execute_ que es el metodo encargado de su funcionamiento.

Aquí tambien he añadido el metodo _updatePlayer_ a modo de _helper_ para evitar código redundante dentro de _execute_ y a la vez permitir una mejor gestión de la actualización de movimiento del jugador.

Es importante destacar que esta class esta ya haciendo uso de la libreria con el core del juego. Fijate que en la línea 15 requiere el uso del componente principal de la libreria:

```php
use SnakesAndLadders\Lib\GameEngine;
```
Con este componente ya puede iniciar el juego, jugador, moverlo y lanzar dados.

Esta parte de la aplicación se apoya tambien en la class _src/Game/DisplayStatus.php_ que tiene como única responsabilidad mostrar los mensajes en consola que va generado la aplicación. Esto nos permite una gestión separada de mensajes y juego y un codigo más ordenado.

He enfocado esta _class_ con un array que va acumulando los mensajes y que dispone de un metodo para mostrarlos finalmente todos y el resultado final de juego en función del _estado_ del jugador.

El resto del código usado que implementa todas las funcionalidades requeridas por las 3 US esta en _src/Lib_. Los test que se le aplican nos permiten confirmar que cumple con los requisitos.

He optado por este enfoque modular porque por una parte permite separar el backend del frontend (uno de los requerimientos observados), por otra parte el código queda más desacoplado, con responsabilidades muy concretas para cada componente (_class_) lo que facilita los test y el mantenimiento.

# Posibles Mejoras

* No está detallado en las historias de usuario de la Kata pero se podría terminar de implementar la lógica del juego, por ejemplo el control de si el token de un jugador cae en una casilla de escalera o de serpiente.

  Tal como esta estructurada la aplicación esto se podria hacer por ejemplo con un diccionario que almacene el valor de la casilla que es serpiente o escalera, y el valor de la casilla asociada a la que moverse. Esto podria ser un único diccionario con estos pares de valores o dos diccionarios si queremos diferenciar entre _serpientes_ y _escaleras_ a nivel de mostrar mensajes de estado acorde a cada uno de estos tipos.

  Esto con PHP seria realmente un array de arrays de dos columnas, por ejemplo para controlar casillas de _serpientes_:

  ```php
  $snakes = [
            [6,16],
            [11,49],
            [19,62],
            [25,46],
            [60,64],
            (...)
            ]
   
  ```

  La gestión de esta lógica estaria dentro de _GameEngine_ y en el metodo _updatePlayer_ de _src/Game/GameCommand.php_ gestionariamos los mensajes a mostrar si el jugador esta en una de estas casillas.

* Tampoco esta detallado el poder jugar contra el ordenador y por lo tanto que cuando lanzamos **php game.php** el ordenador vaya jugando hasta ganar o perder.

  Pero esto una vez más seria implementar un loop central en la aplicación de consola que vaya jugando hasta resolver la partida. Esto nos llevaria a poder usar más de un jugador.

  Tener multiples jugadores es relativamente fácil de gestionar disponiendo de class que separan responsabilidades como tenemos ahora. Por ejemplo en _src/LibGameEngine.php_ en vez de instancia un único _Player_ podriamos gestionar un array de objetos tipo _Player_ cada uno para un jugador.

  La ventaja es que ya partimos de un código de componenetes desacoplados y con una cobertura minima de test que nos permitiria ir creciendo con un cierto orden ... pero calro siempre que tengamos una _feature_ que lo indique :)

## Otros aspectos a tener en cuenta

* Commits más atómicos, a nivel de cada test de aceptación para tener más visibilidad en los cambios que implica un test concreto

* Añadir test unitarios para algunos métodos en los que se realizan cálculos concretos, como por ejemplo para cubrir el _moveTo()_ de _Player_ y para cubrir la parte de la aplicación de consola.

* Explorar el uso de PHPSpec para usar la metodología de _especificación mediante ejemplos_ que encaja con el uso de Behat y permite un proceso completo de BDD. Pero para eso ya quizás mejor pasarse a C# ;) y explorar SpecFlow 


# Conclusiones finales

Me ha gustado esta kata. Obligarse a desarrollar unas funcionalidades sin salirse de lo que se pide en las historias de usuario y generando test de aceptación que dejan cubiertas todas la peticiones de negocio.

Ha sido mi primera vez con Behat y un proceso muy básico de BDD. Así que me he concentrado en el uso de Behat a nivel metodología, y en ir creando código que pasara los test de aceptación y ya está. Tenía visto Behat, Gherkin y el concepto de tests orientado a funcionalidades pero no había trabajado hasta ahora con él. Ver que la Kata apuntaba a este uso fue un reto interesante.

Cuando quieras podemos conversar en más detalle del enfoque de la Kata y ver en directo el funcionamiento del código :)

Entregada esta versión en PHP creo que fuera de este proceso de selección y con más tiempo intentaré repetirla con C# y SpecFlow, para comparar enfoques y sobretodo porque una buena manera de aprender un nuevo lenguaje es hacerlo a la vez guiado por tests. 

