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

use SnakesAndLadders\Game\DataPersists;
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
        $this->showStatus($this->game, $output);
        
        // Manage --moveto option
        if($input->getOption('moveto'))
        {
            $squares = $input->getOption('moveto');
            $this->moveto($squares, $output);
        }

        // Manage --dicerolls option
        if($input->getOption('dicerolls'))
        {
            $this->diceRolls($output);
        }

        $this->game->checkPlayer();
        $this->showStatus($this->game, $output);

        return 0;
    }


    /**
     * Dice rolls and move the player
     *
     * @return void
     */
    private function diceRolls($output)
    {
        $squares = $this->game->player->rollsADie();
        $this->moveto($squares, $output);
    }


    /**
     * Move token to specific square
     * 
     * @param [type] $input
     * @param [type] $output
     * @return void
     */
    private function moveto($squares, $output)
    {
        $output->writeln("Player move token $squares squares");
        $this->game->player->moveTo($squares);
    }


    /**
     * showStatus
     *
     * @param GameEngine $game
     * @param [type] $output
     * @return void
     */
    private function showStatus(GameEngine $game, $output)
    {
        $square = $game->player->getActualSquare();
        $output->writeln("Player at square: $square");

        if($this->game->player->getWin())
        {
            $output->writeln("Player WIN!!!!");
        }
    }

}