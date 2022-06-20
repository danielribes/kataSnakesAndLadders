<?php
/**
 * This class has the player state
 */
 
namespace SnakesAndLadders;

use SnakesAndLadders\Token;

class Player
{
    private $token;
    private $hasWin;

    public function __construct()
    {
        $this->token = new Token(1);
        $this->hasWin = false;
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
        $newposition = $this->token->getPosition() + $positions;        $this->token->setPosition($newposition);
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


}