<?php namespace Webbins\Mailing;

use Webbins\Views\View;

class Message {
    private $from;
    private $to;
    private $cc;
    private $bcc;
    private $readReceipt;
    private $subject;
    private $body;
    private $type;
    private $attachments = array();
    private $sender;
    private $bounceAddress;
    private $charset;

    public function setFrom($from, $alias) {
        assert(!empty($from), 'From address can\'t be empty.');

        if (is_array($from)) {
            $this->from = $from;
        } else {
            $this->from = array(
                $from => $alias
            );
        }
    }

    public function getFrom() {
        return $this->from;
    }

    public function setTo($to, $alias) {
        assert(!empty($to), 'You must specify an address to send your mail to.');

        if (is_array($to)) {
            $this->to = $to;
        } else {
            $this->to = array(
                $to => $alias
            );
        }
    }

    public function getTo() {
        return $this->to;
    }

    public function setCc($cc, $alias) {
        assert(!empty($cc), 'You must specify an address to send your copy to.');

        if (is_array($cc)) {
            $this->cc = $cc;
        } else {
            $this->cc = array(
                $cc => $alias
            );
        }
    }

    public function getCc() {
        return $this->cc;
    }

    public function setBcc($bcc, $alias) {
        assert(!empty($bcc), 'You must specify an address to send your hidden copy to.');

        if (is_array($bcc)) {
            $this->bcc = $bcc;
        } else {
            $this->bcc = array(
                $bcc => $alias
            );
        }
    }

    public function getBcc() {
        return $this->bcc;
    }

    public function setReadReceipt($readReceipt) {
        assert(!empty($bcc), 'You must specify an address.');
        $this->readReceipt = $readReceipt;
    }

    public function getReadReceipt() {
        return $this->readReceipt;
    }

    public function setSubject($subject) {
        assert(is_string($subject), 'Subject must be a string.');
        $this->subject = $subject;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setBody($body, $type, $params) {
        assert(is_string($body), 'Body must be a string.');
        assert(!empty($body), 'Body can\'t be empty.');

        // if type is text/webbins, then try to locate
        // and compile the passed file.
        if ($type == 'text/webbins') {
            $body = View::render($body, $params);
            $type = 'text/html';
        }

        $this->body = $body;
        $this->setType($type);
    }

    public function getBody() {
        return $this->body;
    }

    public function setType($type) {
        assert(is_string($type), 'The type was incorrect.');
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setAttachment($attachment) {
        assert(!empty($attachment), 'Attachment can\'t be empty.');
        $this->attachments[] = $attachment;
    }

    public function getAttachments() {
        return $this->attachments;
    }

    public function setSender($sender) {
        assert(!empty($to), 'Sender can\'t be empty.');
        $this->sender = $sender;
    }

    public function getSender() {
        return $this->sender;
    }

    public function setBounceAddress($bounceAddress) {
        assert(!empty($to), 'Bounce address can\'t be empty.');
        $this->bounceAddress = $bounceAddress;
    }

    public function getBounceAddress() {
        return $this->bounceAddress;
    }

    public function setCharset($charset) {
        assert(is_string($charset), 'Charset must be a string.');
        $this->charset = $charset;
    }

    public function getCharset() {
        return $this->charset;
    }
}
