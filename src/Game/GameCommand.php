<?php
/**
 * Console Command to SnakesAndLadders Game
 * 
 */

namespace SnakesAndLadders\Game;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use SnakesAndLadders\Game\DisplayStatus;
use SnakesAndLadders\Lib\GameEngine;

class GameCommand extends Command
{
    private $game;

    /**
     * configure command
     */
    protected function configure()
    {
        $this->setName('SnakesAndLadders Game')
             ->setDescription('SnakesAndLadders game frontend to play and test the SnakesAndLadders Library from Voxel Kata')
             ->setHelp("Usage: see README.md\n")
             ->addOption(
                'dicerolls',
                null,
                InputOption::VALUE_NONE,
                'Dice rolls and move the player'
             )
             ->addOption(
                'moveto',
                null,
                InputOption::VALUE_REQUIRED,
                'Move Token Across the Board'
             );
    }


    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    { 
        $this->game = new GameEngine();
        $status = new DisplayStatus($this->game, $output);
        $status->addMessage('Player at square: 1');

        // Manage --moveto option
        if($input->getOption('moveto'))
        {
            $squares = $input->getOption('moveto');
            $this->updatePlayer($status, $squares);
        }

        // Manage --dicerolls option
        if($input->getOption('dicerolls'))
        {
            $squares = $this->game->player->rollsADie();
            $status->addMessage("Dice show: $squares");
            $this->updatePlayer($status, $squares);
        }

        $this->game->checkPlayer();
        $status->show();

        return 0;
    }


    /**
     * Helper function to update player position
     *
     * @param DisplayStatus $status
     * @param [type] $squares
     * @return void
     */
    private function updatePlayer(DisplayStatus $status, $squares)
    {
        $status->addMessage("Player move token $squares squares");
        $position = $this->game->player->moveTo($squares);
        $status->addMessage("Player at square: $position");
    }

}