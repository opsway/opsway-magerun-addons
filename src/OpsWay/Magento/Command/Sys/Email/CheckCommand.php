<?php

namespace OpsWay\Magento\Command\Sys\Email;

use N98\Magento\Command\AbstractMagentoCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

class CheckCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
          ->setName('sys:email:check')
          ->setDescription('Checking and showing magento settings for sending emails')
      ;
    }

   /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output);
        if ($this->initMagento()) {
            $output->writeln("Hello world!\n");
        }
    }
}