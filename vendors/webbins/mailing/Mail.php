<?php namespace Webbins\Mailing;

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

require('Message.php');

// @todo fix certificate
class Mail {
    private static $self;
    private static $message;
    private $host;
    private $port;
    private $username;
    private $password;

    /**
     * Construct.
     * @param   string  $host
     * @param   int     $port
     * @param   string  $from
     * @param   string  $alias
     * @param   string  $username
     * @param   string  $password
     */
    public function __construct($host, $port, $from, $alias='', $username='', $password='') {
        self::$message = new Message();

        $this->host = $host;
        $this->port = $port;
        $this->from($from, $alias);
        $this->username = $username;
        $this->password = $password;

        self::$self = $this;
    }

    public static function to($to, $alias='') {
        self::$message->setTo($to, $alias);

        return self::$self;
    }

    public function cc($to, $alias='') {
        self::$message->setCc($to, $alias);
        return $this;
    }

    public function bcc($to, $alias='') {
        self::$message->setBcc($to, $alias);
        return $this;
    }

    public function readReceipt($to) {
        self::$message->setReadReceipt($to);
        return $this;
    }

    public function from($from, $alias='') {
        self::$message->setFrom($from, $alias);
        return $this;
    }

    public function sender($sender) {
        self::$message->setSender($sender);
        return $this;
    }

    public function bounceAddress($to) {
        self::$message->setBounceAddress($to);
        return $this;
    }

    public function subject($subject) {
        self::$message->setSubject($subject);
        return $this;
    }

    public function body($body, $type='text/txt', $params=array()) {
        self::$message->setBody($body, $type, $params);
        return $this;
    }

    public function attach($attachment) {
        self::$message->setAttachment($attachment);
        return $this;
    }

    public function charset($charset) {
        self::$message->setCharset($charset);
        return $this;
    }

    /**
     * Preview the message.
     * @return  string
     */
    public function preview() {
        return self::$message->getBody();
    }

    /**
     * Create a Swift Mail and send it.
     * @return   int  Numbers of mail sent
     */
    public function send() {
        // create a new instance of Swift SmtpTransport
        // and pass some required values
        $transport = Swift_SmtpTransport::newInstance($this->host, $this->port);

        if ($this->username) {
            $transport->setUsername($this->username);
        }

        if ($this->password) {
            $transport->setPassword($this->password);
        }

        // create a new instance of Swift Mailer and pass the
        // transportation
        $swift = Swift_Mailer::newInstance($transport);

        return $swift->send($this->buildMessage());
    }

    /**
     * Builds the message and return it.
     * @return  Swift_Message
     */
    public function buildMessage() {
        $subject       = self::$message->getSubject();
        $body          = self::$message->getBody();
        $type          = self::$message->getType();
        $to            = self::$message->getTo();
        $cc            = self::$message->getCc();
        $bcc           = self::$message->getBcc();
        $from          = self::$message->getFrom();
        $sender        = self::$message->getSender();
        $bounceAddress = self::$message->getBounceAddress();
        $charset       = self::$message->getCharset();
        $readReceipt   = self::$message->getReadReceipt();

        // create a new instance of Swift Message and set some
        // required values
        $message = Swift_Message::newInstance()
                   ->setSubject($subject)
                   ->setFrom($from)
                   ->setTo($to)
                   ->setBody($body, $type);

        if ($cc) {
            $message->setCc($cc);
        }

        if ($bcc) {
            $message->setBcc($bcc);
        }

        if ($bounceAddress) {
            $message->setReturnPath($bounceAddress);
        }

        if ($sender) {
            $message->setSender($this->sender);
        }

        if ($readReceipt) {
            $message->setReadReceipt($readReceipt);
        }

        if ($charset) {
            $message->setCharset($charset);
        }

        // loop through all attachments
        foreach (self::$message->getAttachments() as $attachment) {
            $message->attach(Swift_Attachment::fromPath($attachment));
        }

        return $message;
    }
}
