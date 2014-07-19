<?php

namespace OpsWay\Magento\Command\Sys\Email;

use N98\Magento\Command\AbstractMagentoCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

class ListCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
      $this
          ->setName('sys:email:list')
          ->setDescription('Showing list transactional email template')
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