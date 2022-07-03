<?php
/**
 * This class moves the player token
 */

namespace SnakesAndLadders\Lib;

final class Token
{
    private $position;
    private $oldposition;

    public function __construct()
    {
        $this->position = 1;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Undocumented function
     *
     * @param [type] $newposition
     * @return void
     */
    public function setPosition($newposition)
    {
        $this->oldposition = $this->position;
        $this->position = $newposition;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function getOldPosition()
    {
        return $this->oldposition;
    }

}