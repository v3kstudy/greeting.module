<?php

namespace Drupal\greeting\Tests;

use DefaultMailSystem;
use DrupalWebTestCase;

class GreetingMailSystem extends DefaultMailSystem
{

    public static $logs = [];

    function mail(array $message)
    {
        self::$logs[] = $message;
        return true;
    }

}

class GreetingWelcomeMailTest extends DrupalWebTestCase
{

    public function getInfo()
    {
        return array(
            'name'        => 'Greeting',
            'description' => 'Test greeting mail sending',
            'group'       => 'Examples'
        );
    }

    public function setUp()
    {
        parent::setUp('greeting');
    }

    public function testSendMail()
    {
        global $conf, $language;
        $conf['mail_system']['greeting_welcome'] = 'Drupal\greeting\Tests\GreetingMailSystem';

        $params = array('first_name' => 'Andy', 'last_name' => 'Truong');
        drupal_mail('greeting', 'welcome', 'Andy Truong <heyandy@x.v3k.net>', $language, $params);

        $this->assertEqual('greeting', GreetingMailSystem::$logs[0]['module']);
        $this->assertEqual('welcome', GreetingMailSystem::$logs[0]['key']);
        $this->assertEqual('Andy Truong <heyandy@x.v3k.net>', GreetingMailSystem::$logs[0]['to']);
        $this->assertTrue(false !== strpos(GreetingMailSystem::$logs[0]['body'], variable_get('site_name')));
    }

}
