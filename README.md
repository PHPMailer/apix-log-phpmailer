#PHPMailer logger for Apix Log

An extension for the [Apix/Log](https://github.com/frqnck/apix-log) PSR-3 logger that sends log messages via email using [PHPMailer](https://github.com/PHPMailer/PHPMailer).

##Usage

Create an Apix PhpMailer Log instance, providing a pre-configured PHPMailer instance to the constructor.
This instance will be used for all subsequent messages.

By default the logger sends an email for each individual log message received, which can be quite inefficient, so call `$logger->setDeferred(true)` to save up the log messages and send them all in one message on `__destruct`.

We suggest you enable exceptions in your PHPMailer instance otherwise you may not be told about problems sending your log messages.

##Example

```php
// Create a PHPMailer instance with exceptions enabled
$mailer = new \PHPMailer(true);
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
