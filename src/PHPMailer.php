<?php
/**
 * Apix PHPMailer logger.
 * @license     http://opensource.org/licenses/BSD-3-Clause  New BSD License
 * @author Marcus Bointon <phpmailer@synchromedia.co.uk>
 */

namespace Apix\Log\Logger;

use Apix\Log\Exception;
use Apix\Log\LogEntry;
use Psr\Log\InvalidArgumentException;

/**
 * Apix logger for sending logs via PHPMailer.
 *
 * @author Marcus Bointon <phpmailer@synchromedia.co.uk>
 */
class PHPMailer extends AbstractLogger implements LoggerInterface
{
    /**
     * A PHPMailer instance to use for sending.
     */
    protected \PHPMailer\PHPMailer\PHPMailer $phpmailer;

    /**
     * Constructor.
     * @param \PHPMailer\PHPMailer\PHPMailer $phpmailer A preconfigured PHPMailer instance to use for sending.
     */
    public function __construct(\PHPMailer\PHPMailer\PHPMailer $phpmailer)
    {
        if (count($phpmailer->getToAddresses()) < 1) {
            throw new InvalidArgumentException(
                'No valid email address set in PHPMailer'
            );
        }

        $this->phpmailer = $phpmailer;
    }

    /**
     * {@inheritDoc}
     */
    public function write(LogEntry $log): bool
    {
        $this->phpmailer->Body = (string)$log;
        try {
            return $this->phpmailer->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}
