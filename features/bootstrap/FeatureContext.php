<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

use SnakesAndLadders\Lib\Game;
use SnakesAndLadders\Lib\Token;
use SnakesAndLadders\Lib\Player;
use SnakesAndLadders\Lib\Dice;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $game;
    private $diceresult;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the game is started
     */
    public function theGameIsStarted()
    {
        $this->game = new Game();
    }

    /**
     * @When the token is placed on the board
     */
    public function theTokenIsPlacedOnTheBoard()
    {
        Assert::assertInstanceOf("SnakesAndLadders\\Lib\\Token", $this->game->player->getToken());
    }

    /**
     * @Then the token is on square :arg1
     */
    public function theTokenIsOnSquare($arg1)
    {
        if(!isset($this->game))
        {
            $this->theGameIsStarted();
            $this->game->player->moveTo($arg1-1);
            $this->game->checkPlayer($this->game->player);
        }

        Assert::assertEquals($arg1, $this->game->player->getActualSquare());
    }

    /**
     * @When the token is moved :arg1 spaces
     */
    public function theTokenIsMovedSpaces($arg1)
    {
        $this->game->player->moveTo($arg1);
        $this->game->checkPlayer($this->game->player);
    }

    /**
     * @When then it is moved :arg1 spaces
     */
    public function thenItIsMovedSpaces($arg1)
    {
        $this->game->player->moveTo($arg1);
    }

    /**
     * @Then the player has won the game
     */
    public function thePlayerHasWonTheGame()
    {
        Assert::assertTrue($this->game->player->getWin());
    }

    /**
     * @Then the player has not won the game
     */
    public function thePlayerHasNotWonTheGame()
    {
        Assert::assertFalse($this->game->player->getWin());
    }

    /**
     * @Then the result should be between :arg1-:arg2 inclusive
     */
    public function theResultShouldBeBetweenInclusive($arg1, $arg2)
    {
        $sides = range($arg1, $arg2);
        Assert::assertContains($this->diceresult, $sides);
    }

    /**
     * @Given the player rolls a :arg1
     */
    public function thePlayerRollsA($arg1)
    {
        if(!isset($this->game))
        {
            $this->theGameIsStarted();
        }

        if($arg1 == 'die')
        {
            Assert::assertInstanceOf("SnakesAndLadders\\Lib\\Dice", $this->game->player->getDice());

            $this->diceresult = $this->game->player->rollsADie();
        }

        if($arg1 != 'die')
        {
            $this->diceresult = $arg1;
        }
 
    }

    /**
     * @When they move their token
     */
    public function theyMoveTheirToken()
    {
        $this->game->player->moveTo($this->diceresult);
    }

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
}
