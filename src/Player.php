<?php
/**
 * This class has the player state
 */
 
namespace SnakesAndLadders;

use SnakesAndLadders\Token;
use SnakesAndLadders\Dice;

class Player
{
    private $token;
    private $hasWin;
    private $dice;

    public function __construct()
    {
        $this->token = new Token(1);
        $this->hasWin = false;
        $this->dice = new Dice(); 
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getActualSquare()
    {
        return $this->token->getPosition();
    }

    public function moveTo($positions)
    {
        $newposition = $this->token->getPosition() + $positions;
        $this->token->setPosition($newposition);
    }

    public function setWin()
    {
        $this->hasWin = true;
    }

    public function getWin()
    {
        return $this->hasWin;
    }

    public function moveToSquare($square)
    {
        $this->token->setPosition($square);
    }

    public function getOldPosition()
    {
        return $this->token->getOldPosition();
    }

    public function getDice()
    {
        return $this->dice;
    }

    public function rollsADie()
    {
        return $this->dice->roll();
    }

}