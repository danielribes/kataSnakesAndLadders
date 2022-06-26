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

use SnakesAndLadders\Lib\GameEngine;

class GameCommand extends Command
{
    /**
     * configure command
     */
    protected function configure()
    {
        $this->setName('SnakesAndLadders')
             ->setDescription('SnakesAndLadders game frontend')
             ->setHelp("Usage: see README.md\n");
    }


    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {

        $game = new GameEngine();
        $output->writeln("\nrun SnakesAndLadders\n");

        return 0;
    }

}