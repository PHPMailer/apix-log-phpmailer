<?php
/**
 * Apix PHPMailer logger.
 * @license     http://opensource.org/licenses/BSD-3-Clause  New BSD License
 * @author Marcus Bointon <phpmailer@synchromedia.co.uk>
 */

namespace Apix\Log\Logger;

use Apix\Log\Exception;
use Psr\Log\InvalidArgumentException;

/**
 * Apix logger for sending logs via PHPMailer.
 *
 * @author Marcus Bointon <phpmailer@synchromedia.co.uk>
 */
class PhpMailer extends AbstractLogger implements LoggerInterface
{
    /**
     * A PHPMailer instance to use for sending.
     * @type \PHPMailer
     */
    protected $phpmailer;

    /**
     * Constructor.
     * Note PHPMailer is *NOT* namespaced.
     * @param \PHPMailer $phpmailer A preconfigured PHPMailer instance to use for sending.
     */
    public function __construct($phpmailer)
    {
        if (!$phpmailer instanceof \PHPMailer) {
            throw new InvalidArgumentException(
                'Must be an instance of \PHPMailer', 1
            );
        }

        if (count($phpmailer->getToAddresses()) < 1) {
            throw new InvalidArgumentException(
                'No valid email address set in \PHPMailer'
            );
        }

        $this->phpmailer = $phpmailer;
    }

    /**
     * {@inheritDoc}
     */
    public function write(array $log)
    {
        $this->phpmailer->Body = $log['msg'];
        try {
            return $this->phpmailer->send();
        } catch (\phpmailerException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}
