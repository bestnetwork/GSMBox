<?php

namespace Bestnetwork\GSMBox\Entity;

use \DateTime;

use Bestnetwork\GSMBox\Exceptions\InvalidNumberFormatException;

class Message {
    
    const INTERNATIONAL_NUMBER_PATTERN = '/\+(\d{1,2})[-\s.]?\(?(\d{3})\)?[-\s.]?(\d{3})[-\s.]?(\d{4})/';

    /**
     * @var string
     */
    protected $to;

    /**
     * Optional
     *
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var boolean
     */
    protected $readed;
    
    public function __construct(){
        $this->date = new DateTime();
        $this->readed = FALSE;
    }

    /**
     * Set sender
     *
     * @param string $from
     *
     * @return Message
     */
    public function setFrom( $from ){
        if( preg_match(self::INTERNATIONAL_NUMBER_PATTERN, $from) ){
            $this->from = preg_replace(self::INTERNATIONAL_NUMBER_PATTERN, '+$1$2$3$4', $from);
        }else{
            throw new InvalidNumberFormatException('The number must be in International Format Number');
        }

        return $this;
    }

    /**
     * Get sender
     * 
     * @return string
     */
    public function getFrom(){
        return $this->from;
    }

    /**
     * Set recipient
     *
     * @param string $to
     *
     * @return Message
     */
    public function setTo( $to ){
        if( preg_match(self::INTERNATIONAL_NUMBER_PATTERN, $to) ){
            $this->to = preg_replace(self::INTERNATIONAL_NUMBER_PATTERN, '+$1$2$3$4', $to);
        }else{
            throw new InvalidNumberFormatException('The number must be in International Format Number');
        }

        return $this;
    }

    /**
     * Get recipient
     * 
     * @return string
     */
    public function getTo(){
        return $this->to;
    }

    /**
     * Set content
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText( $text ){
        // Convert to UTF-8
        //$text = mb_convert_encoding($text, 'UTF-8', mb_detect_encoding($text));
        
        // Remove all non printable characters
        //$text = preg_replace('/[^[:print:]]/', ' ',$text);
        //$text = preg_replace('/[\x00-\x1F\x60\x80-\x9F]/', ' ',$text);
        
        // Strip multiple spaces with a single space
        $text = preg_replace('/[[:blank:]]+/', ' ',$text);
        
        $this->text = trim($text);

        return $this;
    }

    /**
     * Get content
     * 
     * @return string
     */
    public function getText( $markAsReaded = TRUE ){
        if( $markAsReaded ){
            $this->setAsReaded();
        }
        
        return $this->text;
    }

    /**
     * Get date
     * 
     * @return DateTime
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * Set as readed
     * 
     * @return Message
     */
    public function setAsReaded(){
        $this->readed = TRUE;
        
        return $this;
    }

    /**
     * Has been readed?
     * 
     * @return boolean
     */
    public function hasBeenReaded(){
        return $this->readed;
    }
}