<?php
/**
 * This class setup the game and have the rules
 */

namespace SnakesAndLadders\Lib;

use SnakesAndLadders\Lib\Player;

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