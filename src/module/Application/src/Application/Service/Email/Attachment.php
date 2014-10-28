<?php
namespace Application\Service\Email;

use FzyCommon\Util\Params;
use Application\Service\Email;

/**
 * Service used for sending an email
 * that has an attachment
 *
 * Class Attachment
 * @package Application\Service\Email
 * Service Key: email_attachment
 */
class Attachment extends Email
{
    protected $attachmentKey;

    protected $attachmentName;

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
        if (empty($this->attachmentKey)) {
            throw new \RuntimeException('Attachment key must be specified');
        }
        if (empty($this->attachmentName)) {
            throw new \RuntimeException('Attachment name must be specified');
        }
        /* @var $s3 \Aws\S3\S3Client */
        $s3 = $this->getServiceLocator()->get('s3');
        /* @var $response \Guzzle\Service\Resource\Model */
        $response = $s3->getObject(array('Bucket' => $this->getServiceLocator()->get('s3_config')->get('bucket'), 'Key' => $this->getAttachmentKey()));
        $boundary = 'random_string_' . md5(time()) . '_boundary';
        $fileName = $this->getAttachmentName();

        $contentType = $response->get('ContentType');
        $readReceipt = $params->get('readReceipt') ? "X-Confirm-Reading-To: ".$this->currentUser()->getEmail()."\n" : '';

        $base64FileContent = (string) base64_encode($response->get('Body'));
        $message = <<<MESSAGE
To: {$params->get('to')}
From: {$params->get('from')}
Subject: {$params->get('subject')}
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary="$boundary"
$readReceipt
--$boundary
Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: 7bit
Content-Disposition: inline

{$params->get('message')}

--$boundary
Content-ID: \<$boundary@votr.com\>
Content-Type: $contentType; name="$fileName"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="$fileName"
$base64FileContent
--$boundary--

MESSAGE;
        /* @var $ses \Aws\Ses\SesClient */
        $ses = $this->getServiceLocator()->get('ses');
        $destinations = array($params->get('to'));
        $cc = $params->get('cc');
        if (!empty($cc)) {
            $destinations = array_merge(array_filter(explode(',', $cc)), $destinations);
        }
        $ses->SendRawEmail(array(
            'Source' => $params->get('from'),
            'Destinations' => $destinations,
            'RawMessage' => array(
                'Data' => (string) base64_encode($message),
            )
        ));

        return true;
    }

    /**
     * @param  mixed      $attachmentKey
     * @return Attachment
     */
    public function setAttachmentKey($attachmentKey)
    {
        $this->attachmentKey = $attachmentKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachmentKey()
    {
        return $this->attachmentKey;
    }

    /**
     * @param  mixed      $attachmentName
     * @return Attachment
     */
    public function setAttachmentName($attachmentName)
    {
        $this->attachmentName = $attachmentName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachmentName()
    {
        return $this->attachmentName;
    }

}
