<?php declare(strict_types=1);

namespace App\Service;

use Swift_Mailer;
use Swift_Message;

class EmailNotificationService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    public function __construct(Swift_Mailer $mailer, string $from, string $to)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
    }

    public function notify(string $message): void
    {
        $message = (new Swift_Message())
            ->setSubject('Notification')
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody(
                $message,
                'text/plain'
            );

        $this->mailer->send($message);
    }
}
