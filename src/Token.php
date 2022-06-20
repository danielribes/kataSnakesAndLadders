<?php

namespace SnakesAndLadders;

class Token
{
    private $position;

    public function __construct()
    {
        $this->position = 1;
    }

    public function moveTo($squares)
    {
        $this->position += $squares;
    }

    public function getPosition()
    {
        return $this->position;
    }


}