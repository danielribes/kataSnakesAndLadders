<?php
/**
 * This class moves the player token
 */

namespace SnakesAndLadders;

class Token
{
    private $position;
    private $oldposition;

    public function __construct($firstposition)
    {
        $this->position = $firstposition;
    }

    public function moveTo($positions)
    {
        $this->position += $positions;

    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($newposition)
    {
        $this->oldposition = $this->position;
        $this->position = $newposition;
    }

    public function getOldPosition()
    {
        return $this->oldposition;
    }

}