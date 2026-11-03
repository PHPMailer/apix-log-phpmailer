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

use PHPUnit\Framework\TestCase;

class PHPMailerTest extends TestCase
{

    protected \PHPMailer\PHPMailer\PHPMailer $mailer;

    protected function setUp(): void
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->addAddress('logs@example.com', 'Log Mailbox');
        $mail->setFrom('myapp@example.com', 'My App');
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host     = 'tls://mail.example.com:587';
        $mail->Username = 'user';
        $mail->Password = 'pass';
        $mail->isHTML(false);
        $mail->Subject = 'Error log';

        $this->mailer = $mail;
    }

    public function testThrowsTypeErrorWhenNull(): void
    {
        $this->expectException(\TypeError::class);
        new PHPMailer(null); // @phpstan-ignore argument.type (intentionally wrong type)
    }

    public function testThrowsTypeErrorWhenWrongInstance(): void
    {
        $this->expectException(\TypeError::class);
        new PHPMailer(new \stdClass()); // @phpstan-ignore argument.type (intentionally wrong type)
    }

    public function testThrowsInvalidArgumentExceptionWhenNoValidEmail(): void
    {
        $this->expectException(\Psr\Log\InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains('No valid email address set in PHPMailer');
        new PHPMailer(new \PHPMailer\PHPMailer\PHPMailer());
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(PHPMailer::class, new PHPMailer($this->mailer));
    }

    /**
     * This test relies on the mailer not being usable - for example that it uses an unreachable host
     */
    public function testThrowsExceptionOnPhpMailerError(): void
    {
        $this->expectException(\Apix\Log\Exception::class);
        $logger = new PHPMailer($this->mailer);
        $logger->info("Log me!");
    }


    public function testWriteIsCalled(): void
    {
        $mock = $this
            ->getMockBuilder('Apix\Log\Logger\PHPMailer')
            ->disableOriginalConstructor()
            ->onlyMethods(['write'])
            ->getMock();

        $mock->expects($this->exactly(2))->method('write');

        $mock->info('Log me!');
        $mock->error('Log me too!');
    }
}
