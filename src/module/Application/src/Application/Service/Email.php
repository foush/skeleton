<?php
namespace Application\Service;

use FzyCommon\Util\Params;

/**
 * Service used to send an SES email message
 * Class Email
 * @package Application\Service
 * Service Key: email
 */
class Email extends Base
{
    /**
     * Sends an email using the $params object
     *  Looks for the following keys:
     *      'to': to whom the email is addressed
     *      'cc': string or array of people to also get this message
     *      'from': the email address this message will appear from (NOTE: this is the email address which will be notified of read receipts, if option is specified)
     *      'subject': the message subject
     *      'message': the message body
     *      'readReceipt': whether or not to send a read receipt header
     *
     * @param  Params $params
     * @return bool
     */
    public function send(Params $params)
    {
        $transport = $this->getServiceLocator()->get('SlmMail\Mail\Transport\SesTransport');
        $readReceipt = $params->get('readReceipt');

        $htmlPart = new MimePart($params->get('message'));
        $htmlPart->type = "text/html";

        $textPart = new MimePart($params->get('message'));
        $textPart->type = "text/plain";

        $body = new MimeMessage();
        $body->setParts(array($textPart, $htmlPart));

        $message = new Message();

        $message->addFrom($params->get('from'));
        $message->addTo($params->get('to'));
        if ($params->get('cc')) {
            $message->addCc($params->get('cc'));
        }
        $message->setSubject($params->get('subject'));
        if ($params->get('readReceipt')) {
            $message->getHeaders()->addHeaderLine('X-Confirm-Reading-To', $params->get('from'));
        }

        $message->setEncoding("UTF-8");
        $message->setBody($body);
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');

        return $transport->send($message);
    }
}
