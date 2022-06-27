<?php
/**
 * This class setup the game and have the rules
 */

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