OpsWay n98-magerun addons
=====================

Modules for n98-magerun Magento command-line tool which add several custom commands

Installation
------------
There are a few options.  You can check out the different options in the [MageRun
docs](http://magerun.net/introducting-the-new-n98-magerun-module-system/).

Here's the easiest:

1. Create ~/.n98-magerun/modules/ if it doesn't already exist.

        mkdir -p ~/.n98-magerun/modules/

2. Clone the magerun-addons repository in there

        cd ~/.n98-magerun/modules/
        git clone git@github.com:kalenjordan/magerun-addons.git

3. It should be installed.  To see that it was installed, check to see if one of the new commands is in there, like `sys:email:check`.


New Additional Commands
--------

### Email commands ###

* sys : email : list - showing list transactional email template

    `$ n98-magerun.phar sys:email:list`


* sys : email : send - sending any transactional email through magento on test email address

     `$ n98-magerun.phar sys:email:send [--template[="..."]] [type] [email]`

    Where params:
    
      - **type** = "trans" (send transactional email) OR "magento" (send magento email) OR "php" (send email by php settings);
      
      - **email**  - where to send test email
      
      - **template** - should be value template ID or Code from sys-email-list command


* sys : email : check - checking and showing magento settings for sending emails

    `$ n98-magerun.phar sys:email:check`

