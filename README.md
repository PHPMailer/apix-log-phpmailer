# PHPMailer logger for Apix Log

An extension for the [Apix/Log](https://github.com/frqnck/apix-log) PSR-3 logger that sends log messages via email using [PHPMailer](https://github.com/PHPMailer/PHPMailer).

The home repo is [on github](https://github.com/PHPMailer/apix-log-phpmailer), and the composer package is [on packagist](https://packagist.org/packages/phpmailer/apix-log-phpmailer).

Apix Log was written by Franck Cassedanne (@frqnck). This extension is by Marcus Bointon (@Synchro) and is published via the [PHPmailer organisation](https://github.com/PHPMailer) under the BSD license (though note that PHPMailer itself uses the LGPL).

## Installation

Install the logger via composer:

    composer require phpmailer/apix-log-phpmailer

You require at least PHP 5.5.

## Usage

Create an Apix PhpMailer Log instance, providing a pre-configured PHPMailer instance to the constructor.
This instance will be used for all subsequent messages.

By default the logger sends an email for each individual log message received, which can be quite inefficient, so call `$logger->setDeferred(true)` to save up the log messages and send them all in one message on `__destruct`.

We suggest you enable exceptions in your PHPMailer instance (by passing `true` to the constructor) otherwise you may not be told about problems sending your log messages.

## Example

```php
use PHPMailer\PHPMailer\PHPMailer;
// Create a PHPMailer instance with exceptions enabled
$mailer = new PHPMailer(true);
$mailer->addAddress('logs@example.com', 'Log Mailbox');
$mailer->setFrom('myapp@example.com', 'My App');
$mailer->isSMTP();
$mailer->SMTPAuth = true;
$mailer->Host = 'tls://mail.example.com:587';
$mailer->Username = 'user';
$mailer->Password = 'pass';
$mailer->isHTML(false);
$mailer->Subject = 'Error log';

$logger = new Apix\Logger\PhpMailer($mailer);
$logger->setDeferred(true);
$logger->info('Log me!');
$logger->error('Log me too!');
```
