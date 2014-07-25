<?php

namespace OpsWay\Magento\Command\Sys\Email;

use N98\Magento\Command\AbstractMagentoCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption;

class SendCommand extends AbstractMagentoCommand
{
    const EMAIL_TRANSACTIONAL = 'trans';
    const EMAIL_MAGENTO = 'magento';
    const EMAIL_PHP = 'php';

    protected $_output;

    protected function configure()
    {
      $this
          ->setName('sys:email:send')
          ->addArgument('type', InputArgument::OPTIONAL, 'Email type', null)
          ->addArgument('email', InputArgument::OPTIONAL, 'Sending to email (recipient)', null)
          ->addOption('template', null, InputOption::VALUE_OPTIONAL, 'The transactional template ID or Code', null)
          ->setDescription('Sending any email by magento or php settings')
      ;
    }

   /**
    * @param \Symfony\Component\Console\Input\InputInterface $input
    * @param \Symfony\Component\Console\Output\OutputInterface $output
    * @return int|void
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_output = $output;
        $this->detectMagento($output);
        if ($this->initMagento()) {

            $oClass = new \ReflectionClass(__CLASS__);
            $buffer = $oClass->getConstants();
            foreach (
                array($oClass->getParentClass())
                + $oClass->getInterfaces()
                as $fill
            ) {
                $buffer = array_diff_key($buffer, $fill->getConstants());
            }
            $emailTypes = array_values($buffer);
            unset($oClass);

            $dialog = $this->getHelperSet()->get('dialog');
            if (($type = $input->getArgument('type')) === null) {
                $type = $dialog->ask($output, '<question>Email type ('.implode(', ',$emailTypes).'):</question> ');
            }

            if ($type == self::EMAIL_TRANSACTIONAL){
                if (($templateId = $input->getOption('template')) === null) {
                    $templateId = $dialog->ask($output, '<question>Template ID or Code (from "sys:email:list"):</question> ');
                }
            } else {
                $templateId = null;
            }

            if (($emailTo = $input->getArgument('email')) === null) {
                $emailTo = $dialog->ask($output, '<question>Send to Email (recipient):</question> ');
            }

            $this->sendEmail(array(
                    'type' => $type,
                    'template_id' => $templateId,
                    'email_to' => $emailTo
                ));


        }
    }

    protected function sendEmail($params){

        switch ($params['type']){

            case self::EMAIL_TRANSACTIONAL: {
                // Define the sender, here we query Magento default email (in the configuration)
                // For customer support email, use : 'trans_email/ident_support/...'
                $sender = Array('name' => \Mage::getStoreConfig('trans_email/ident_general/name'),
                                'email' => \Mage::getStoreConfig('trans_email/ident_general/email'));
                // In this array, you set the variables you use in your template
                $vars = array();
                // Send your email
                \Mage::getModel('core/email_template')->sendTransactional($params['template_id'],
                                                                         $sender,
                                                                         $params['email_to'],
                                                                         'N98 Magerun',
                                                                         $vars);
            } break;

            case self::EMAIL_MAGENTO: {
                $email = \Mage::getModel('core/email')
                            ->setData('type','html')
                            ->setData('subject','Magento test email via n98-magerun')
                            ->setData('from_email',\Mage::getStoreConfig('trans_email/ident_general/email'))
                            ->setData('from_name','n98-magerun')
                            ->setData('to_email',$params['email_to'])
                            ->setData('to_name','N98 Magerun')
                            ->setData('body','<html><body><h1>Test Email</h1><hr><p>This is test email sent via <b>n98-magerun</b> OpsWay addons (<a href="https://github.com/opsway/opsway-magerun-addons">GitHub repo</a>)</p></body></html>');
                $email->send();
            } break;

            case self::EMAIL_PHP: {
                $mail = new \Zend_Mail();
                $mail->setBodyText('This is test email sent via n98-magerun OpsWay addons use PHP settings.');
                $mail->setFrom(\Mage::getStoreConfig('trans_email/ident_general/email'), 'n98-magerun')
                    ->addTo($params['email_to'], 'N98 Magerun')
                    ->setSubject('PHP test email via n98-magerun');
                $mail->send();
            } break;

            default:
                $this->_output->writeln('<error>"'.$params['type'].'" type of email not found.</error>');
                return;
        }
        $this->_output->writeln('<info>'.$params['type'].' email was sent to '.$params['email_to'].'.</info>');
    }
}