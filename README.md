# Introducción

Esta es mi solución a la Kata SnakesAndLadders de VoxelGroup (https://github.com/VoxelGroup/Katas.Code.SnakesAndLadders)

En esta Kata se trata de resolver de manera progresiva 3 historias de usuario a partir de sus correspondientes test de aceptación.

# Enfoque tecnológico adoptado

Lo he desarrollado con **PHP** porque es el lenguaje en que en estos momentos me siento más cómodo y actualmente da un buen soporte a OOP. Aunque llevo unos meses trasteando con C# he preferido plantearlo con PHP porque a nivel de testing conozco mucho más su ecosistema. 

Teniendo en cuenta que mandan las historias de usuario y que estas ya están descritas usando lenguaje **Gherkin** me ha parecido que lo más eficaz era usar un enfoque de testing basado en **BDD (Behavior Drive Development)** en el que las necesidades de negocio marcan el flujo de tests con los que se va diseñando la aplicación.

En PHP esto se puede llevar a cabo usando el framework **Behat,** que trabaja a partir de historias de usuario (Features) y test de aceptación (Scenarios) descritos con Gherkin.

También he usado **PHPUnit** que es el framework habitual para test unitarios y para realizar TDD. En este caso Behat guía el desarrollo de cada Feature y PHPUnit me da soporte en aplicar tests a determinados elementos.

Investigando por el lado de C# he visto que hay algunos frameworks similares como por ejemplo SpecFlow, pero en este momento me sentía más cómodo en PHP asi que ido adelante con PHP, Behat y PHPUnit.

He realizado un commit de cada historia de usuario, aunque para ser sinceros, visto ahora en perspectiva quizás hubiera sido mejor ser más atómico con los commits para tener cada test de aceptación en uno de ellos.

La solución usa además Composer que es un gestor de dependencias con el que se instalan todos los frameworks necesarios y se facilita el enrutamiento de todos los componentes. Una especie de Nuget del mundo C#
