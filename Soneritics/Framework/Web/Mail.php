<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Soneritics Webdevelopment.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Framework\Web;

use Framework\Exceptions\FatalException;
use Framework\Renderer\HtmlRenderer;
use Framework\Renderer\TextRenderer;

/**
 * Mail object. Sends e-mail.
 * 
 * @author Jordi Jolink
 * @since 2-1-2015
 * @todo Uses the PEAR Mail_Mime extension. Should not do that :-)
 */
class Mail
{
    protected $address = null;
    protected $cc = [];
    protected $bcc = [];
    protected $subject = '';
    protected $from = null;
    protected $messageHtml = null;
    protected $messageTxt = null;

    /**
     * Validate the properties.
     * 
     * @throw FatalException
     */
    protected function validate()
    {
        $props = [
            'address' => 'Address can not be empty',
            'from' => 'No sender address (from) specified',
            'messageHtml' => 'HTML message is mandatory'
        ];

        foreach ($props as $prop => $msg) {
            if (empty($this->$prop)) {
                throw new FatalException($msg);
            }
        }
    }

    /**
     * Send the mail, based on the class' properties.
     * 
     * @return bool
     */
    public function send()
    {
        // Validate properties and throw an exception for errors
        $this->validate();

        // Add text variant when only HTTML is provided
        if ($this->messageTxt === null) {
            $this->messageTxt = html_entity_decode($this->messageHtml);
        }

        // Send the mail
        $headers = ['From' => $this->from, 'Subject' => $this->subject];
        $mime = new \Mail_mime(PHP_EOL);
        $mime->setTXTBody($this->messageTxt);
        $mime->setHTMLBody($this->messageHtml);

        if (!empty($this->cc)) {
            foreach ($this->cc as $address) {
                $mime->addCc($address);
            }
        }

        if (!empty($this->bcc)) {
            foreach ($this->bcc as $address) {
                $mime->addBcc($address);
            }
        }

        $result = (new \Mail_mail)->send(
            $this->address,
            $mime->headers($headers),
            $mime->get(['text_charset' => 'utf-8', 'html_charset' => 'utf-8'])
        );

        return !is_a($result, 'PEAR_Error');
    }

    /**
     * Set the address to send the mail to.
     * 
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Add a CC address.
     * 
     * @param string $address
     * @return $this
     */
    public function addCC($address)
    {
        if (is_array($address)) {
            foreach ($address as $line) {
                $this->addCC($line);
            }
        } else {
            $this->cc[] = $address;
        }
        
        return $this;
    }

    /**
     * Add a BCC address.
     * 
     * @param string $address
     * @return $this
     */
    public function addBCC($address)
    {
        if (is_array($address)) {
            foreach ($address as $line) {
                $this->addBCC($line);
            }
        } else {
            $this->bcc[] = $address;
        }
        
        return $this;
    }

    /**
     * Set the subject of the email.
     * 
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the sender's email address.
     * 
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set the HTML body of the email (mandatory).
     * 
     * @param View|string $body
     * @return $this
     */
    public function setHTMLBody($body)
    {
        if (is_a($body, 'Framework\MVC\View')) {
            $body = $body->render((new HtmlRenderer('Pub')));
        }

        $this->messageHtml = $body;
        return $this;
    }

    /**
     * Set the text body of the email (optional).
     * 
     * @param View|string $body
     * @return $this
     */
    public function setTextBody($body)
    {
        if (is_a($body, 'Framework\MVC\View')) {
            $body = $body->render((new TextRenderer('Pub')));
        }

        $this->messageTxt = $body;
        return $this;
    }
}