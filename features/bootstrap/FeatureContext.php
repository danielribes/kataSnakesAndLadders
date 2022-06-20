<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

use SnakesAndLadders\Game;
use SnakesAndLadders\Token;
use SnakesAndLadders\Player;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $game;

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
        Assert::assertInstanceOf("SnakesAndLadders\\Token", $this->game->player->getToken());
    }

    /**
     * @Then the token is on square :arg1
     */
    public function theTokenIsOnSquare($arg1)
    {
        if(!isset($this->game))
        {
            $this->game = new Game();
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
}
