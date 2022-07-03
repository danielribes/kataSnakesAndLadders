<?php
/**
 * This class setup the game and have the rules
 */

namespace SnakesAndLadders\Lib;

use SnakesAndLadders\Lib\Player;
use SnakesAndLadders\Lib\Token;
use SnakesAndLadders\Lib\Dice;

class Game 
{
    /**
     * Check Player position and victory
     *
     * @return void
     */
    public function checkPlayer(Player $player)
    {
        $player->setOutOfBounds(false);
        $position = $player->getPosition();

        if($position == 100)
        {
            $player->setWin();
        }

        if($position > 100)
        {
            $player->moveToSquare($player->getOldPosition());
            $player->setOutOfBounds(true);
        }

    }


    /**
     * Create a new Player
     *
     * @return void
     */
    function addPlayer()
    {
        return new Player(new Token, new Dice);  
    }

}