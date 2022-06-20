<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

use SnakesAndLadders\Game;
use SnakesAndLadders\Token;

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
        Assert::assertInstanceOf("SnakesAndLadders\\Token", $this->game->token);
    }

    /**
     * @Then the token is on square :arg1
     */
    public function theTokenIsOnSquare($arg1)
    {
        if(!isset($this->game))
        {
            $this->game = new Game();
            $this->game->token->moveTo($arg1-1);
        }

        Assert::assertEquals($arg1, $this->game->token->getPosition());
    }

    /**
     * @When the token is moved :arg1 spaces
     */
    public function theTokenIsMovedSpaces($arg1)
    {
        $this->game->token->moveTo($arg1);
    }

    /**
     * @When then it is moved :arg1 spaces
     */
    public function thenItIsMovedSpaces($arg1)
    {
        $this->game->token->moveTo($arg1);
    }

    /**
     * @Then the player has won the game
     */
    public function thePlayerHasWonTheGame()
    {
        throw new PendingException();
    }

    /**
     * @Then the player has not won the game
     */
    public function thePlayerHasNotWonTheGame()
    {
        throw new PendingException();
    }
}
