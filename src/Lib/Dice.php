<?php


namespace SnakesAndLadders\Lib;

class Dice 
{

    public function roll()
    {
        return random_int(1,6);
    }

}