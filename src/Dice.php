<?php


namespace SnakesAndLadders;

class Dice 
{

    public function roll()
    {
        return random_int(1,6);
    }

}