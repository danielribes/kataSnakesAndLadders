<?php

namespace SnakesAndLadders;

use SnakesAndLadders\Token;

class Game 
{
    public $token;

    public function __construct() {
        $this->token = new Token();        
    }

}