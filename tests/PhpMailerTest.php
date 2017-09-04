<?php

/**
 *
 * This file is part of the Apix Project.
 *
 * (c) Franck Cassedanne <franck at ouarz.net>
 *
 * @license     http://opensource.org/licenses/BSD-3-Clause  New BSD License
 *
 */

namespace Apix\Log\Logger;

class PhpMailerTest extends \PHPUnit_Framework_TestCase
{

    protected $mailer;

    protected function setUp()
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->addAddress('logs@example.com', 'Log Mailbox');
        $mail->setFrom('myapp@example.com', 'My App');
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'tls://mail.example.com:587';
        $mail->Username = 'user';
        $mail->Password = 'pass';
        $mail->isHTML(false);
        $mail->Subject = 'Error log';

        $this->mailer = $mail;
    }

    protected function tearDown()
    {
        unset($this->mailer);
    }

    /**
     * @expectedException Psr\Log\InvalidArgumentException
     * @expectedExceptionMessage Must be an instance of \PHPMailer
     */
    public function testThrowsInvalidArgumentExceptionWhenNull()
    {
        new PhpMailer(null);
    }

    /**
     * @expectedException Psr\Log\InvalidArgumentException
     * @expectedExceptionMessage Must be an instance of \PHPMailer
     */
    public function testThrowsInvalidArgumentExceptionWhenWrongInstance()
    {
        new PhpMailer(new \stdClass());
    }

    /**
     * @expectedException Psr\Log\InvalidArgumentException
     * @expectedExceptionMessage No valid email address set in \PHPMailer
     */
    public function testThrowsInvalidArgumentExceptionWhenNoValidEmail()
    {
        new PhpMailer(new \PHPMailer\PHPMailer\PHPMailer);
    }

    public function testConstructor()
    {
        new PhpMailer($this->mailer);
    }

    /**
     * @expectedException Apix\Log\Exception
     * This test relies on the mailer not being usable - for example that it uses an unreachable host
     */
    public function testThrowsExceptionOnPhpMailerError()
    {
        $logger = new PhpMailer($this->mailer);
        $logger->info("Log me!");
    }


    public function testWriteIsCalled()
    {
        $mock = $this->getMockBuilder('Apix\Log\Logger\PhpMailer')
                     ->disableOriginalConstructor()
                     ->setMethods(array('write'))
                     ->getMock();

        $mock->expects($this->exactly(2))->method('write');

        $mock->info('Log me!');
        $mock->error('Log me too!');
    }

}
