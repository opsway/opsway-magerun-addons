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

            $emailTemplate = \Mage::getModel('core/email_template');
            $dataTable = array();
            foreach ($emailTemplate->getCollection() as $item){
                $dataTable[] = array($item->getId(),$item->getTemplateCode());
            }

            $output->writeln('<info>Custom email template:</info>');
            $this->getHelper('table')
                        ->setHeaders(array('ID','Template Name'))
                        ->setRows($dataTable)
                        ->render($output);

            $output->writeln('<info>Default email template:</info>');
            $dataTable = $emailTemplate::getDefaultTemplatesAsOptionsArray();
            $this->getHelper('table')
                        ->setHeaders(array('Template Code','Template Label'))
                        ->setRows($dataTable)
                        ->render($output);

        }
    }
}