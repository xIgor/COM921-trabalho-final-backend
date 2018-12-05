<?php

namespace IntecPhp\Worker;

use Mailgun\Mailgun as MailgunClient;
use IntecPhp\Model\Config;
use Pheanstalk\Pheanstalk;

class Mailgun extends Worker
{
    private $mgClient;
    private $domain;
    private $config;

    public function __construct(Pheanstalk $tube, string $domain, MailgunClient $msgClient, array $config)
    {
        $this->tube = $tube;
        $this->domain = $domain;
        $this->config = $config;
        $this->mgClient = $msgClient;
    }

    public function execute(array $emailData)
    {
        $ec = $this->config;
        $fromName = $emailData['from_name'] ?? $ec['default_from_name'];
        $fromEmail = $emailData['from_email'] ?? $ec['default_from'];
        $messageArray = array(
            'from' => $fromName . '<' . $fromEmail . '>',
            'to'   => $emailData['to_name'] . ' <' . $emailData['to_email'] . '>'
        );
        if ($ec['default_bcc']) {
            $messageArray['bcc'] = $ec['to_name'] . ' <' . $ec['to_email'] . '>';
        }
        if (isset($emailData['bcc_email'])) {
            $bccName = $emailData['bcc_name'] ?? '';
            $messageArray['bcc'] = $bccName . ' <' . $emailData['bcc_email'] . '>';
        }
        $messageArray['subject'] = $emailData['subject'];
        $messageArray['html']    = $emailData['body'];
        $result = $this->mgClient->sendMessage($this->domain, $messageArray);
    }
}
