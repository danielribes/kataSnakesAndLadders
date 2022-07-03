<?php
/**
 * Display status messages
 * 
 */

namespace SnakesAndLadders\Game;

use Symfony\Component\Console\Output\OutputInterface;
use SnakesAndLadders\Lib\Game;
use SnakesAndLadders\Lib\Player;

class DisplayStatus
{
    private $messages;
    private $game;
    private $output;

    public function __construct(Game $game, OutputInterface $output)
    {
        $this->messages = [];
        $this->game = $game;
        $this->output = $output;
    }


    /**
     * Add messages 
     *
     * @param [type] $message
     * @return void
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }
    

    /**
     * Render status messages
     *
     * @return void
     */
    public function show(Player $player)
    {
        foreach($this->messages as $message)
        {
            $this->output->writeln($message);
        }

        if($player->getWin())
        {
            $this->output->writeln("Player WIN!!!!");
        }
    }

}