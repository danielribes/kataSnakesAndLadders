# Introducción

Esta es mi solución a la Kata SnakesAndLadders de VoxelGroup (https://github.com/VoxelGroup/Katas.Code.SnakesAndLadders)

Esta Kata describe el funcionamiento del juego Snakes And Ladders con unos requerimientos muy concretos.

En la primera versión que realize de la Kata (que se puede ver _viajando_ a los commits más antiguos) me centre demasiado en resolver la parte de tests de historias de usuario con Behat.

Centrarme tanto en esta parte para mostrar mi capacidad de trabajo con este tipo de tests, con la poca experiencia que tenia en esto en ese momento, fue un error que derivo en falta de atención a otros aspectos importantes de los requerimientos de la Kata y en general de la solución aportada.

Esto se tradujo en:

* Una aplicación de consola sin la mecánica del juego esperada. Solo permitia mover el token a una posición concreta, o lanzar dados para comprobar que se realizaban los movimentos correctos en una tirada, pero sin avanzar en el juego hasta ganar.

* Un bug en la visualización del numero de casilla, en los movimientos directos a una posición, que no mostraba el valor actual si no el sumado.

* Código redundate entre la _class Player_ y la _class Token_ ademas de instanciar objetos en el constructor de _Player_ en vez de inyectar dependencias correctamente.

* Un motor de juego centrado en mostrar los movimientos detallados en los tests pero que no permitia realmente jugar según la mecanica del juego

* Incluir los binarios del vendor/bin creado por _Composer_ en vez de mantener solo un composer.lock para fijar las versiones a usar y que las dependencias se instalaran con un _composer update_ en el momento de desplegar el código para probarlo.

* Hacer un poco de _sobre ingenieria_ con una _class DisplayStatus_ para mostrar los mensajes del juego cuando se podian mostrar directamente porque la dinamica del motor de juego no tenia tampoco tanta complejidad como para requerir una _class_ especializada en los mensajes a terminal.

Visto todo esto no me gustaba que esta solución a la Kata quedase así con todos estos errores, por este motivo lo que ves aquí es una nueva versión revisada, más acorde con los requerimientos finales y con un código más cuidado.

Actualmente todos estos problemas se han resuelto:

* La aplicación de consola juega sola hasta el final, mostrando cada tirada de dado, su correspondiente movimiento y si el jugador cae en un casilla de _Serpiente_ o de _Escalera_ lo muestra en la consola y gestiona el movimento a la casilla final que le corresponda.

* No hay opciones raras com la antigua _--moveto_ para mover directamente a una casilla sin tirar el dado. En esta versión solo esta disponible el parametro _--bysteps_ para indicar que queremos que el juego vaya paso a paso, que a cada tirada de dado ejecute el movimiento y se pare a la espera de continuar. Sin esta opción activa el ordenador juega solo de golpe todos los movimientos necesarios hasta ganar y terminar la partida.

* Se ha refactorizado la _class Player_ para inyectar dependencias y eliminar código redundante entre ella y _Token_

* Se ha refactorizado la _class Game_ para añadir un metodo _factory_ que es el único responsable de crear un jugador y inyectar dependencias a _Player_, se ha refactorizado el código que gestiona la comprobación del estado y posición del jugador y se le ha añadido el control de las casillas de _serpientes_ y _escaleras_

* Todos estos cambios en el código de la Libreria tambien han significado cambios a mejor en el código que ejecuta los test.

* En la aplicación de consola tambien se ha realizado una refactorización importante, con las mejoras en la Libreria del juego y una mejor gestión de la inyección de dependencias, el código que ejecuta el juego se puede leer claramente, muy semantico, a parte que ahora permite jugar hasta finalizar la partida.

Este es el metodo _execute_ de la _class GameComman.php_ que es el motor de juego de la aplicación.

```php
protected function execute(InputInterface $input, OutputInterface $output)
    { 
        $game = new Game();
        $player = $game->addPlayer();
        $playerposition = $player->getPosition();
        $output->writeln("Player at square: $playerposition");

        if($input->getOption('bysteps'))
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Roll Dice ? [y/n] ', false, '/^(y|j)/i');
        }

        while(!$player->getWin())
        {            
            $squares = $player->rollsADie();
            $output->writeln(PHP_EOL."Dice show: $squares");
            $player->moveToken($squares);
            $game->checkPlayerStatus($player);
            if($player->checkOutOfBounds())
            {
                $output->writeln("Player can't move");
            } else {
                $output->writeln("Player move token $squares squares");
            }
            $position = $player->getPosition();
            $output->writeln("Player at square: $position");
            $square = $game->checkPlayerPosition($player);
            if($square['type'] != 'normal')
            {
                $message = 'Player at '.$square['type'].' square, moved to new position '.$square['position'];
                $output->writeln($message);
            }

            if($input->getOption('bysteps') && (!$player->getWin()))
            {
                if (!$helper->ask($input, $output, $question)) 
                {
                    exit;
                }
            }
        }
        
        if($player->getWin())
        {
            $output->writeln("Player WIN!!!!");
        }

        return 0;       
    }
```

Es fácil leer lo que hace:

* Crea un nuevo juego
* Añade un jugador al juego y muestra su posición inicial
* Entra en un bucle que se mantiene mientras el jugado no ha ganado
  * En este bucle tira los dados, mueve el token, comprueba status jugador y tipo de casilla en la que se encuentra y si el parametro _--bysteps_ esta en uso espera a que le digas continuar o cancelar el juego.
  * Esto se repite hasta que el jugador gana
  * Se van mostrando por consola los mensajes que informan del valor de los dados, movimento y tipo casilla en la que se encuentra el jugador.
* Si el jugador gana, se termina el bucle y se felicita al jugador

Todo con solo cargar la _class src/Lib/Game.php_ 

En esta _class_ se crea al jugador con el metodo _addPlayer()_:

```php
public function addPlayer()
{
    return new Player(new Token, new Dice);  
}
```

Y en _Player_ se trabaja con el _Token_ y con _Dice_ via dependencias encapsulando tambien el acceso directo a propiedades de _Token_ y de _Player_ y no como ocurria en la versión anterior de la aplicación de consola y en el cógigo de los test, en que estaba todo más acoplado como este ejemplo:

```php
if($this->game->player->getWin())
```

Pero ahora hace lo mismo con menos acoplamiento:

```php
if($player->getWin())
```
# Un apunte sobre los tests

Usando _Behat_ el código de los test va todo en _bootstrap/FeatureContext.php_, localizar las sentencias _Give/When/Then/And_ debe hacerse mirando los comentarios. _Behat_  usa _PHPDOC_ para indicar cada sentencia y su parametrización.

Luego genera nombres de metodos de acuerdo a la sentencia correspondiente.

En este aspecto es más parecido a _SpecFlow_ que a _Fluent Assertions_ donde creo que se crean metodos que encadenan los _Give/When/Then/And_. Behat se apoya más en los comentarios para identificar y controlar el comportamiento de cada sentencia.

En la salida de los tests tambien indica el nombre del metodo que resuelva cada sentencia de un escenario.

Es cierto que tambien puede configurase _Behat_ para usar _contextos_ diferentes y de esta manera no queda todo en un solo _bootstrap/FeatureContext.php_ sino que se puede repartir en varios ficheros lo que permitiria, por ejemplo, tener historias de usuario en contextos diferentes o tipos de test diferentes para diferentes contextos. Pero en esta Kata no he querido complicarme a este punto y por eso estan todos en _bootstrap/FeatureContext.php_

Por otra parte el código de alguno de los test tambien ha mejorado con las refactorizaciones en la Libreria y cosas que antes estaban así:

```php
    /**
     * @When the token is placed on the board
     */
    public function theTokenIsPlacedOnTheBoard()
    {
        Assert::assertInstanceOf("SnakesAndLadders\\Token", $this->game->token);
    }
```
Ahora no necesitan comprobar instancias y acceden a quien tiene la responsabilidad real de ese recurso:

```php
    /**
     * @When the token is placed on the board
     */
    public function theTokenIsPlacedOnTheBoard()
    {
        Assert::assertEquals('1', $this->player->getPosition());
    }
```

# Ejecutar el proyecto 

Para desplegar el código y poder ejecutar el juego y los tests, el funcionamiento no ha variado mucho, basicamente hay que añadir el _composer update_ para instalar las dependencias.

En la ejecución del juego si que encontraras más diferencias. Todas estas novedades las detallo a continuación.

Para su funcionamiento, a parte de las dependencias especificas del proyecto y del uso de composer es necesario disponer de PHP, mínimo la versión CLI de PHP para ejecutarlo en un terminal.

Para facilitar las cosas he configurado un Dockerfile poder ejecutar un contenedor Docker que lo incluye todo. Este Dockerfile monta un contenedor con PHP 7.4, composer, vim, clona el proyecto con sus dependencias y lo deja listo para lanzar los test y evaluar su funcionamiento. Sin implicaciones en el sistema anfitrión más que disponer de _git_ y _Docker_ instalado.

Si no tienes docker en tu sistema lo puedes instalar [con estas instrucciones](https://docs.docker.com/get-docker/) 

## Ejecutar el Docker y arrancar el entorno

En tu terminal clona este repositorio con:

```
$ git clone https://github.com/danielribes/kataSnakesAndLadders.git
```

Muevete dentro del directorio _kataSnakesAndLadders_ y ejecuta estos dos comandos de Docker, para evitar que use la imagen de la versión anterior, por si la tenias aun cacheada:

```
$ docker-compose build --no-cache && docker-compose up -d --force-recreate
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
drwxr-xr-x 1 root root   4096 Jul  4 00:35 .
drwxr-xr-x 1 root root   4096 Jun 23 08:43 ..
drwxr-xr-x 8 root root   4096 Jul  4 00:35 .git
-rw-r--r-- 1 root root     25 Jul  4 00:35 .gitignore
-rw-r--r-- 1 root root    387 Jul  4 00:35 Dockerfile
-rw-r--r-- 1 root root  26294 Jul  4 00:35 README.md
-rw-r--r-- 1 root root    635 Jul  4 00:35 composer.json
-rw-r--r-- 1 root root 139382 Jul  4 00:35 composer.lock
-rw-r--r-- 1 root root    120 Jul  4 00:35 docker-compose.yml
drwxr-xr-x 3 root root   4096 Jul  4 00:35 features
-rw-r--r-- 1 root root    345 Jul  4 00:35 game.php
-rw-r--r-- 1 root root    988 Jul  4 00:35 phpunit.xml
drwxr-xr-x 4 root root   4096 Jul  4 00:35 src
````

## Instalar las dependencias

Aquí debes ejecutar:
```
root@3679266af703:/usr/local/src# composer update
```

Esto instalara todas las dependencias necesarias para ejecutar el juego y los tests. Cuando finalize puedes lanzar los test para confirmar que esta todo ok.

## Lanzar los tests 

Para lanzar los tests y ver los resultados con _bin/behat_:

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

En la raiz del proyecto tienes **game.php** que es el punto de entrada a la aplicación de consola, se ejecuta como un script php.

El juego ahora funciona al completo, y solo. Cuando ejecutes el comando _php game.php_ empezara y continuara realizando lanzamientos de dados y movimiento del jugador hasta hacerlo ganar.

```
root@3679266af703:/usr/local/src# php game.php 
```

El resultado sera algo parecido a esto:

```
Dice show: 6
Player move token 6 squares
Player at square: 99
Player at snake square, moved to new position 80

Dice show: 6
Player move token 6 squares
Player at square: 86

Dice show: 4
Player move token 4 squares
Player at square: 90

Dice show: 1
Player move token 1 squares
Player at square: 91

Dice show: 6
Player move token 6 squares
Player at square: 97

Dice show: 6
Player can't move
Player at square: 97

Dice show: 4
Player can't move
Player at square: 97

Dice show: 6
Player can't move
Player at square: 97

Dice show: 5
Player can't move
Player at square: 97

Dice show: 3
Player move token 3 squares
Player at square: 100
Player WIN!!!!
```

Tambien tienes el parametro __--bysteps__. Usando este parametro el juego, por cada lanzamiento de dados y movimiento, te preguntara si deseas continuar. 

Pulsado la tecla Y + [intro] continuas, y pulsando la tecla N +[intro] el juego termina en ese punto. Esto te permite ver paso a paso com va jugando.

```
root@3679266af703:/usr/local/src# php game.php --bysteps

Player at square: 1

Dice show: 5
Player move token 5 squares
Player at square: 6
Roll Dice ? [y/n] y
```

La aplicación ahora controla si el token del jugador cae en una de las casillas de _Serpientes_ o _Escaleras_:

````
Dice show: 6
Player move token 6 squares
Player at square: 99
Player at snake square, moved to new position 80
````


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

* **src/Game** contiene la classe que forma el core de la aplicación de consola

* **src/Lib** contiene las 4 classes que forman el core del backend, de la libreria y que se ajustan a cada US y sus correspondientes UAT

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

## US 2 - Player Can Win the Game

Aquí la cosa ya se pone más interesante, _Player_ cobra más importancia en los test de aceptación de esta user story, por lo que decido crear una class _Player_ que es la que mantiene el _estado_ del jugador y a su vez lo mueve por el tablero mediante _Token_ Esto implica también refactorizar _Game_ para que haga una instancia de _Player_ en vez de _Token_ A partir de este momento el juego arranca con un _Player_ que a su vez dispone de su propio _Token_

_Game_ adquiere también más importancia concentro en ella las _reglas del juego_, el check de si el jugador gana o no.

Esta combinación de _Game_/_Player_/_Token_ permite resolver los 2 test de aceptación y a la vez mantener responsabilidades separadas, mientras el resto de tests de la user story 1 se mantienen también en verde.

## US 3 - Moves Are Determined By Dice Rolls

En este paso creo una nueva class _Dice_. Separo de esta manera la responsabilidad de generar una tirada de dados. _Player_ en este momento es la class que asume el control de _Token_ y de _Dice_

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
    $old = $this->player->getOldPosition();
    $new = $this->player->getPosition();

    $rslt = $new-$old;

    Assert::assertEquals($arg1, $rslt);
    
}
````

Finalizando esta tercera user story, todos los test de aceptación de cada una de ellas pasan en verde.

# Desarrollo de la aplicación de consola

Como ya he comentado la aplicación de consola actua como un frontend para poner a prueba la libreria. Para su desarrollo he usado el componente _Console_ del Symfony Framework, que permite disponer de los elementos basicos para crear una aplicación de consola, gestiónar input via parámetros o teclado y gestionar su output.

En _src/Game/GameCommand.php_ se encuentra el core de la aplicación de consola. Es una class que hereda del la _class_ _Command_ de Symfony y sobreescribe dos metodos: _configure_ donde especificamos los parámetros que aceptara la aplicación, instrucciones, etc. y _execute_ que es el metodo encargado de su funcionamiento.

Es importante destacar que esta class esta ya haciendo uso de la libreria con el core del juego. Fijate que en la línea 15 requiere el uso del componente principal de la libreria:

```php
use SnakesAndLadders\Lib\Game;
```
Con este componente ya puede iniciar el juego, el jugador, moverlo y lanzar dados.

He optado por este enfoque modular porque por una parte permite separar el backend del frontend (uno de los requerimientos observados), por otra parte el código queda más desacoplado, con responsabilidades muy concretas para cada componente (_class_) lo que facilita los test y el mantenimiento.

# Posibles Mejoras

* Tener multiples jugadores. Es relativamente fácil de gestionar disponiendo de class que separan responsabilidades como tenemos ahora. Por ejemplo en _src/Lib/Game.php_ en vez de añadir un único jugador podria preguntar por cuantos jugadores y añadirlos en un array. 

  Dentro del bucle del juego, y por turnos se preguntaria por cada lanzamiento de dados de cada jugador del array.

  La ventaja es que ya partimos de un código de componenetes desacoplados y con una cobertura minima de test que nos permitiria ir creciendo con un cierto orden ... pero calro siempre que tengamos una _feature_ que lo indique :)

## Otros aspectos a tener en cuenta

* Commits más atómicos, a nivel de cada test de aceptación para tener más visibilidad en los cambios que implica un test concreto

* Añadir test unitarios para algunos métodos en los que se realizan cálculos concretos, como por ejemplo para cubrir el _moveToken()_ de _Player_ y para cubrir la parte de la aplicación de consola.

# Conclusiones finales

Me ha gustado esta kata. Obligarse a desarrollar unas funcionalidades sin salirse de lo que se pide en las historias de usuario y generando test de aceptación que dejan cubiertas todas la peticiones de negocio.

Ha sido mi primera vez con Behat y un proceso muy básico de BDD. Así que me he concentrado en el uso de Behat a nivel metodología, y en ir creando código que pasara los test de aceptación y ya está. Tenía visto Behat, Gherkin y el concepto de tests orientado a funcionalidades pero no había trabajado hasta ahora con él. Ver que la Kata apuntaba a este uso fue un reto interesante.

