<?php

namespace Bestnetwork\GSMBox\Test;

use Bestnetwork\GSMBox\Codec\GsmCodecInterface;
use Bestnetwork\GSMBox\Codec\CodecException;
use Bestnetwork\GSMBox\Codec\GSM0338Codec;

use \DateTime;
use \DateInterval;
use DateTimeZone;

class GsmPdu {

    /**
     * @var string
     */
    protected $pdu;

    /**
     * @var integer
     */
    protected $length;

    /**
     * @var integer
     */
    protected $cnt;

    /**
     * @var integer
     */
    protected $seq;

    /**
     * @param string $pdu
     * @param integer $lenSmsc
     * @param integer $cnt
     * @param integer $seq
     */
    public function __construct( $pdu, $lenSmsc, $cnt = 1, $seq = 1){
        $this->length = strlen($pdu) / 2 - $lenSmsc;
        $this->pdu = strtoupper($pdu);
        $this->cnt = $cnt;
        $this->seq = $seq;
    }

    /**
     * @return string
     */
    public function getPdu(){
        return $this->pdu;
    }

    /**
     * @return integer
     */
    public function getLength(){
        return $this->length;
    }

    /**
     * @return integer
     */
    public function getCount(){
        return $this->cnt;
    }

    /**
     * @return string
     */
    public function getSequence(){
        return $this->seq;
    }
}

class GsmSms {

    const DATA_CODING_GSM_7BIT = 0;
    const DATA_CODING_GSM_8BIT = 4;
    const DATA_CODING_UCS = 8;

    const SEVENBIT_MAX_LENGTH = 160;
    const EIGHTBIT_MAX_LENGTH = 140;
    const UCS2_MAX_LENGTH = 70;

    const SEVENBIT_MP_MAX_LENGTH = 153;             // SEVENBIT_MP_MAX_LENGTH - 7
    const EIGHTBIT_MP_MAX_LENGTH = 134;             // EIGHTBIT_MAX_LENGTH - 6
    const UCS2_MP_MAX_LENGTH = 67;                  // UCS2_MAX_LENGTH - 3

    const ADDRESS_TYPE_UNKNOWN = 0;
    const ADDRESS_TYPE_INTERNATIONAL = 1;
    const ADDRESS_TYPE_NATIONAL = 2;
    const ADDRESS_TYPE_NETWORK_SPECIFIC = 3;
    const ADDRESS_TYPE_SUBSCRIBER = 4;
    const ADDRESS_TYPE_ALPHANUMERIC = 5;
    const ADDRESS_TYPE_ABBREVIATED = 6;
    const ADDRESS_TYPE_RESERVED = 7;

    const ESCAPE_TO_EXTENSION_TABLE = '1B';

    /**
     * The codec wich will be used to encode and decode the message
     *
     * @var GsmCodecinterface
     */
    protected $codec;
    
    /**
     * Recipient
     *
     * @var string
     */
    protected $number;
    
    /**
     * Sender
     *
     * @var string
     */
    protected $csca;
    
    /**
     * Validity
     * 
     * @var DateTime
     */
    protected $validity;

    /**
     * Request status
     *
     * @var boolean
     */
    protected $request_status;
    
    /**
     * Message content
     * 
     * @var string
     */
    protected $text;
    
    /**
     * Encoded GSM text
     * 
     * @var string
     */
    protected $text_gsm;

    /**
     * @var boolean
     */
    protected $udh;

    /**
     * @var integer
     */
    protected $class;

    /**
     * @var integer
     */
    protected $fmt;

    /**
     * @var integer
     */
    protected $dcs;

    /**
     * @var integer
     */
    protected $pid;

    /**
     * @var integer
     */
    private $ref;

    /**
     * @var integer
     */
    private $rand_id;

    public function __construct( GsmCodecInterface $codec ){
        $this->codec = $codec;

        $this->text = '';
        $this->text_gsm = '';
        $this->rand_id = 0;
        $this->class = NULL;
        $this->validity = NULL;
        $this->fmt = self::DATA_CODING_GSM_7BIT;
        $this->dcs = 0;
        $this->pid = 0;
        $this->ref = 0;
    }

    /**
     * @param string $number
     * @return string
     */
    protected static function cleanNumber( $number ){
        return preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Set number
     *
     * @param string $number
     * @return GsmSms $this
     */
    public function setNumber( $number ){
        $this->number = self::cleanNumber($number);

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber(){
        if( $this->number[0] == '+' ){
            return $this->number;
        }else{
            return '+' . $this->number;
        }
    }

    /**
     * Set text
     *
     * @param string $text
     * @return GsmSms $this
     */
    public function setText( $text ){
        $this->text = $text;

        try{
            // Encode test as GSM
            $this->text_gsm = $this->codec->encode($text);

            // Check if is 7Bit or 8Bit encoded
            if( strpos($this->text_gsm, hex2bin(self::ESCAPE_TO_EXTENSION_TABLE)) === false ){
                $this->fmt = self::DATA_CODING_GSM_7BIT;
            }else{
                $this->fmt = self::DATA_CODING_GSM_8BIT;
            }

        }catch( CodecException $e ){
            $this->text_gsm = '';

            // Set DataCoding to UCS
            $this->fmt = self::DATA_CODING_UCS;
        }

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText(){
        return $this->text;
    }

    /**
     * Set number
     *
     * @param string $csca
     * @return GsmSms $this
     */
    public function setCsca( $csca ){
        $this->csca = self::cleanNumber($csca);

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getCsca(){
        if( $this->csca[0] == '+' ){
            return $this->csca;
        }else{
            return '+' . $this->csca;
        }
    }

    /**
     * Set class
     *
     * @param integer $class
     * @return GsmSms
     */
    public function setClass( $class ){
        if( $class >= 0 && $class <= 3 ){
            $this->class = $class;
        }else{
            $this->class = NULL;
        }

        return $this;
    }

    /**
     * Get class
     *
     * @return integer
     */
    public function getClass(){
        return $this->class;
    }

    /**
     * Set valid until $date
     *
     * @param DateTime|DateInterval|NULL $until
     * @return GsmSms
     */
    public function setValidity( $until ){
        if( $until instanceof DateTime ){
            $until->setTimezone(new DateTimeZone('UTC'));
            $this->validity = $until;
        }elseif( $until instanceof DateInterval ){
            $this->validity = new DateTime();
            $this->validity->add($until);
            $this->validity->setTimezone(new DateTimeZone('UTC'));
        }else{
            $this->validity = NULL;
        }

        return $this;
    }

    /**
     * Get validity date
     *
     * @return DateTime
     */
    public function getValidity(){
        if( !$this->validity ){
            return NULL;
        }

        $until = $this->validity;
        $until->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return $until;
    }

    /**
     * Set request status
     *
     * @param boolean $request_status
     * @return GsmSms
     */
    public function setRequestStatus( $request_status ){
        $this->request_status = $request_status;

        return $this;
    }

    /**
     * Get request status
     *
     * @return boolean
     */
    public function getRequestStatus(){
        return $this->request_status;
    }

    /**
     * Generate Pdu
     *
     * @TODO Multipart sms
     *
     * @return GsmPdu
     */
    public function getPdu(){
        $smsc_pdu = $this->getSmscPdu();
        $len_smsc = strlen($smsc_pdu) / 2;
        $sms_submit_pdu = $this->getSubmitPdu();
        $tpmessref_pdu = $this->getMessRefPdu();
        $sms_phone_pdu = $this->getPhonePdu();
        $pid_pdu = $this->getPidPdu();
        $sms_msg_pdu = $this->getMsgPdu();
        /* ..... */

        $pdu = $smsc_pdu;
        $pdu .= $sms_submit_pdu;
        $pdu .= $tpmessref_pdu;
        $pdu .= $sms_phone_pdu;
        $pdu .= $pid_pdu;
        $pdu .= $sms_msg_pdu[0];
        /* ..... */


        return new GsmPdu($pdu, $len_smsc);
    }

    protected function getSmscPdu(){
        if( empty($this->csca) ){
            return '00';
        }

        $ps = $this->getNumberPdu($this->csca);
        $len = (int) (strlen($ps) / 2);

        return bin2hex(chr($len)) . $ps;
    }

    protected function getPhonePdu(){
        if( empty($this->number) ){
            return '00';
        }

        $ps = $this->getNumberPdu($this->number);
        $len = strlen($ps) - (2 + strlen($this->number) % 2);

        return bin2hex(chr($len)) . $ps;
    }

    /**
     * @param string $number
     * @return string
     */
    protected function getNumberPdu( $number ){
        if( empty($number) ){
            return;
        }

        $len = strlen($number);
        $result = '91';

        if( $len % 2 ){
            $number .= 'F';
        }

        for( $i=0; $i<$len; $i+=2 ){
            $result .= $number[$i+1].$number[$i];
        }

        return $result;
    }

    /**
     * @param boolean $udh
     * @return string
     */
    protected function getSubmitPdu( $udh = FALSE ){
        $sms_submit = 1;

        if( !is_null($this->validity) ){
            $sms_submit |= 24;
        }

        if( $this->request_status ){
            $sms_submit |= 32;
        }

        if( $udh ){
            $sms_submit |= 64;
        }

        return bin2hex(chr($sms_submit));
    }

    /**
     * @return string
     */
    protected function getMessRefPdu(){
        return bin2hex(chr($this->ref));
    }

    /**
     * @return string
     */
    protected function getPidPdu(){
        return bin2hex(chr($this->pid));
    }

    /**
     * @return string
     */
    protected function getMsgPdu(){
        $dcsPdu = bin2hex(chr($this->fmt));
        $msgPdu = array();

        if( !is_null($this->class) ){
            $this->dcs = $this->fmt | ( $this->class + 16 );
            $dcsPdu = bin2hex(chr($this->dcs));
        }

        # Validity period
        $msgvpPdu = '';
        if( !is_null($this->validity) ){
            /** @TODO Validity date */
            /* NOT IMPLEMENTED YET */
        }

        # UDL + UD
        $txtPdu = array();
        switch( $this->fmt ){
            case self::DATA_CODING_GSM_7BIT:
                if( strlen($this->text_gsm) <= self::SEVENBIT_MAX_LENGTH ){
                    $txtPdu = array(bin2hex(self::packTo7Bit($this->text_gsm)));
                }else{
                    $txtPdu = self::splitMessage($this->text_gsm);
                }

                break;

            case self::DATA_CODING_GSM_8BIT:
                if( strlen($this->text_gsm) <= self::EIGHTBIT_MAX_LENGTH ){
                    $txtPdu = array(bin2hex(self::packTo8Bit($this->text_gsm)));
                }else{
                    $txtPdu = self::splitMessage($this->text_gsm);
                }

                break;

            case self::DATA_CODING_UCS:
                if( mb_strlen($this->text, 'UCS2') <= self::UCS2_MAX_LENGTH ){
                    $txtPdu = array(bin2hex(self::packTo8Bit($this->text)));
                }else{
                    $txtPdu = self::splitMessage($this->text);
                }

                break;

            default:
                throw new \RuntimeException('Unknown data coding scheme: '.$this->fmt);
        }

        foreach( $txtPdu as $text ){
            $msgPdu[] = $dcsPdu.$msgvpPdu.$text;
        }

        return $msgPdu;
    }

    /**
     * @TODO $udh logic
     *
     * @param string $text
     * @param string $udh
     * return string
     */
    protected static function packTo7Bit( $text, $udh='' ){
        $len = strlen($text);
        $msg_len = (int) (($len + 1) * 7 / 8);
        $text .= chr(0);
        $shift = 0;
        $c = 0;

        $result = '';
        for( $i=0; $i<$msg_len; $i++){
            if( $shift == 6 ){
                $c ++;
            }

            $shift = $i % 7;
            $lb = ord($text[$c]) >> $shift;
            $hb = (ord($text[$c+1]) << (7 - $shift)) & 255;
            $result .= chr($lb + $hb);
            $c ++;
        }

        return chr($len) . $result;
    }

    /**
     * @param string $text
     * @param string $udh
     * return string
     */
    protected static function packTo8Bit( $text, $udh='' ){
        if( !empty($udh) ){
            $text = $udh . $text;
        }

        return chr(strlen($text)) . $text;
    }

    /**
     * Split a long text into multiple smaller pieces
     *
     * @TODO Split message
     *
     * @param string $text
     * @return array
     */
    protected static function splitMessage( $text ){
        $result = array();

        return $result;
    }

    /**
     * @return integer
     */
    protected function getRandId(){
        if( $this->rand_id > 255 ){
            $this->rand_id = 0;
        }

        return $this->rand_id ++;
    }
}

class SmsTest extends \PHPUnit_Framework_TestCase
{
    public function encodeDataProvider(){
        return array(
            // RESULT, MESSAGE, TO, FROM, CONFIRM
            array('001100099143323213F20000AA05E8329BFD06', 'hello', '+342323312'),
            array('069143353432F41101099143323213F20000AA05E8329BFD06', 'hello', '+342323312', '+345343234'),
            array('069143353432F43102099143323213F20000AA05E8329BFD06', 'hello', '+342323312', '+345343234', TRUE)
        );
    }

    /**
     * @dataProvider encodeDataProvider
     */
    public function testEncode( $result, $text, $to, $from = NULL, $confirm = FALSE ){
        $this->markTestIncomplete('This test has not been implemented yet.');

        $sms = $this->getSmsMock();

        $sms->setNumber($to)
            ->setText($text)
            ->setCsca($from);
        
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $sms->getPdu());
        $this->assertContains($result, $sms->getPdu()->getPdu());
    }

    public function encodingCscaDataProvider(){
        return array(
            array('Hola', '+34616585119', '+34646456456', '07914346466554F601000B914316565811F9000004C8373B0C', 17),
            array('', '+44123231231', '', '0001000B914421231332F1000000', 13)
        );
    }

    /**
     * @dataProvider encodingCscaDataProvider
     */
    public function testEncodingCsca( $text, $number, $csca, $expected_pdu, $expected_len ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs)
            ->setCsca($csca);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
        $this->assertEquals($expected_len, $pdu->getLength());
    }

    public function encodingClassDataProvider(){
        return array(
            array('hey yo', '+34654123456', 0, '0001000B914356143254F6001006E8721E947F03'),
            array('hey yo', '+34654123456', 1, '0001000B914356143254F6001106E8721E947F03'),
            array('hey yo', '+34654123456', 2, '0001000B914356143254F6001206E8721E947F03'),
            array('hey yo', '+34654123456', 3, '0001000B914356143254F6001306E8721E947F03')
        );
    }

    /**
     * @dataProvider encodingClassDataProvider
     */
    public function testEncodingClass( $text, $number, $class, $expected_pdu ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs)
            ->setClass($class);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
    }

    public function encodingRequestDataProvider(){
        return array(
            array('hey yo', '+34654123456', TRUE, '0021000B914356143254F6000006E8721E947F03'),
            array('hey yo', '+34654123456', FALSE, '0001000B914356143254F6000006E8721E947F03')
        );
    }

    /**
     * @dataProvider encodingRequestDataProvider
     */
    public function testEncodingRequest( $text, $number, $request, $expected_pdu ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs)
            ->setRequestStatus($request);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
    }

    public function encodingText7BitDataProvider(){
        return array(
            array('Hola', '+34654123456', '0001000B914356143254F6000004C8373B0C', 17),
            array('Hey how\'s it going?', '+34654123456', '0001000B914356143254F6000013C8721E847EDF4F73509A0E3ABFD3EEF30F', 30)
        );
    }

    /**
     * @dataProvider encodingText7BitDataProvider
     */
    public function testEncodingText7Bit( $text, $number, $expected_pdu, $expected_len ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
        $this->assertEquals($expected_len, $pdu->getLength());
    }

    public function encodingText8BitDataProvider(){
        return array(
            array('Hölä', '+34654123456', '0001000B914356143254F6000004483E7B0F', 17),
            array('BÄRÇA äñ@', '+34654123456', '0001000B914356143254F6000009C2AD341104EDFB00', 21)
        );
    }

    /**
     * @dataProvider encodingText8BitDataProvider
     */
    public function testEncodingText8Bit( $text, $number, $expected_pdu, $expected_len ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
        $this->assertEquals($expected_len, $pdu->getLength());
    }

    public function encodingTextUcs2DataProvider(){
        return array(
            array('あ叶葉', '+34616585119', '0001000B914316565811F9000806304253F68449', 19),
            array('Русский', '+34616585119', '0001000B914316565811F900080E0420044304410441043A04380439', 27)
        );
    }

    /**
     * @dataProvider encodingTextUcs2DataProvider
     */
    public function testEncodingTextUcs2( $text, $number, $expected_pdu, $expected_len ){
        $text_ucs = mb_convert_encoding($text, 'UCS2', mb_detect_encoding($text));

        $sms = $this->getSmsMock();
        $sms->setNumber($number)
            ->setText($text_ucs);

        $pdu = $sms->getPdu();
        $this->assertInstanceOf('Bestnetwork\GSMBox\Test\GsmPdu', $pdu);
        $this->assertEquals($expected_pdu, $pdu->getPdu());
        $this->assertEquals($expected_len, $pdu->getLength());
    }

    public function decodeDataProvider(){
        return array(
            array('source' => '07911326040000F0040B911346610089F60000208062917314080CC8F71D14969741F977FD07', 'result' => array(
                'cnt' => 0,
                'seq' => 0,
                'text' => 'How are you?',
                'fmt' => 0,
                'pid' => 0,
                'csca' => '+31624000000',
                'number' => '+31641600986',
                'type' => 0,
                'date' => '02/08/26 19:37:41',
                'ref' => 0
            ))
        );
    }

    /**
     * @dataProvider decodeDataProvider
     */
    public function testDecode( $source, $result ){
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @return GsmSms
     */
    private function getSmsMock(){
        $mock = $this->getMock('Bestnetwork\GSMBox\Test\GsmSms', NULL, array(new GSM0338Codec()));

//        $mock->expects($this->any())
//            ->method('getPdu')
//            ->will($this->returnValue($this->getPduMock()));
        
        return $mock;
    }

    /**
     * @param array $params
     * @return GsmPdu
     */
    private function getPduMock( $params = array() ){
        $mock = $this->getMock('Bestnetwork\GSMBox\Test\GsmPdu', array('getPdu'), $params);

        $mock->expects($this->any())
            ->method('getPdu')
            ->will($this->returnValue(''));
        
        return $mock;
    }
}