<?php

namespace Bestnetwork\GSMBox\Codec;

use Bestnetwork\GSMBox\Codec\CodecInterface;

class GSM0338Codec implements GsmCodecInterface {

    const ON_ERROR_THROW_EXCEPTION = 0;
    const ON_ERROR_REPLACE = 1;
    const ON_ERROR_IGNORE = 2;
    
    /**
     * Encode UCS2 to GSM0338
     * 
     * @var array
     */
    protected static $encodeArray = array(
        '000A'  => '0A',   #   LINE FEED
        '000C'  => '1B0A', #   FORM FEED
        '000D'  => '0D',   #   CARRIAGE RETURN
        '0020'  => '20',   #   SPACE
        '0021'  => '21',   #   EXCLAMATION MARK
        '0022'  => '22',   #   QUOTATION MARK
        '0023'  => '23',   #   NUMBER SIGN
        '0024'  => '02',   #   DOLLAR SIGN
        '0025'  => '25',   #   PERCENT SIGN
        '0026'  => '26',   #   AMPERSAND
        '0027'  => '27',   #   APOSTROPHE
        '0028'  => '28',   #   LEFT PARENTHESIS
        '0029'  => '29',   #   RIGHT PARENTHESIS
        '002A'  => '2A',   #   ASTERISK
        '002B'  => '2B',   #   PLUS SIGN
        '002C'  => '2C',   #   COMMA
        '002D'  => '2D',   #   HYPHEN-MINUS
        '002E'  => '2E',   #   FULL STOP
        '002F'  => '2F',   #   SOLIDUS
        '0030'  => '30',   #   DIGIT ZERO
        '0031'  => '31',   #   DIGIT ONE
        '0032'  => '32',   #   DIGIT TWO
        '0033'  => '33',   #   DIGIT THREE
        '0034'  => '34',   #   DIGIT FOUR
        '0035'  => '35',   #   DIGIT FIVE
        '0036'  => '36',   #   DIGIT SIX
        '0037'  => '37',   #   DIGIT SEVEN
        '0038'  => '38',   #   DIGIT EIGHT
        '0039'  => '39',   #   DIGIT NINE
        '003A'  => '3A',   #   COLON
        '003B'  => '3B',   #   SEMICOLON
        '003C'  => '3C',   #   LESS-THAN SIGN
        '003D'  => '3D',   #   EQUALS SIGN
        '003E'  => '3E',   #   GREATER-THAN SIGN
        '003F'  => '3F',   #   QUESTION MARK
        '0040'  => '00',   #   COMMERCIAL AT
        '0041'  => '41',   #   LATIN CAPITAL LETTER A
        '0042'  => '42',   #   LATIN CAPITAL LETTER B
        '0043'  => '43',   #   LATIN CAPITAL LETTER C
        '0044'  => '44',   #   LATIN CAPITAL LETTER D
        '0045'  => '45',   #   LATIN CAPITAL LETTER E
        '0046'  => '46',   #   LATIN CAPITAL LETTER F
        '0047'  => '47',   #   LATIN CAPITAL LETTER G
        '0048'  => '48',   #   LATIN CAPITAL LETTER H
        '0049'  => '49',   #   LATIN CAPITAL LETTER I
        '004A'  => '4A',   #   LATIN CAPITAL LETTER J
        '004B'  => '4B',   #   LATIN CAPITAL LETTER K
        '004C'  => '4C',   #   LATIN CAPITAL LETTER L
        '004D'  => '4D',   #   LATIN CAPITAL LETTER M
        '004E'  => '4E',   #   LATIN CAPITAL LETTER N
        '004F'  => '4F',   #   LATIN CAPITAL LETTER O
        '0050'  => '50',   #   LATIN CAPITAL LETTER P
        '0051'  => '51',   #   LATIN CAPITAL LETTER Q
        '0052'  => '52',   #   LATIN CAPITAL LETTER R
        '0053'  => '53',   #   LATIN CAPITAL LETTER S
        '0054'  => '54',   #   LATIN CAPITAL LETTER T
        '0055'  => '55',   #   LATIN CAPITAL LETTER U
        '0056'  => '56',   #   LATIN CAPITAL LETTER V
        '0057'  => '57',   #   LATIN CAPITAL LETTER W
        '0058'  => '58',   #   LATIN CAPITAL LETTER X
        '0059'  => '59',   #   LATIN CAPITAL LETTER Y
        '005A'  => '5A',   #   LATIN CAPITAL LETTER Z
        '005B'  => '1B3C', #   LEFT SQUARE BRACKET
        '005C'  => '1B2F', #   REVERSE SOLIDUS
        '005D'  => '1B3E', #   RIGHT SQUARE BRACKET
        '005E'  => '1B14', #   CIRCUMFLEX ACCENT
        '005F'  => '11',   #   LOW LINE
        '0061'  => '61',   #   LATIN SMALL LETTER A
        '0062'  => '62',   #   LATIN SMALL LETTER B
        '0063'  => '63',   #   LATIN SMALL LETTER C
        '0064'  => '64',   #   LATIN SMALL LETTER D
        '0065'  => '65',   #   LATIN SMALL LETTER E
        '0066'  => '66',   #   LATIN SMALL LETTER F
        '0067'  => '67',   #   LATIN SMALL LETTER G
        '0068'  => '68',   #   LATIN SMALL LETTER H
        '0069'  => '69',   #   LATIN SMALL LETTER I
        '006A'  => '6A',   #   LATIN SMALL LETTER J
        '006B'  => '6B',   #   LATIN SMALL LETTER K
        '006C'  => '6C',   #   LATIN SMALL LETTER L
        '006D'  => '6D',   #   LATIN SMALL LETTER M
        '006E'  => '6E',   #   LATIN SMALL LETTER N
        '006F'  => '6F',   #   LATIN SMALL LETTER O
        '0070'  => '70',   #   LATIN SMALL LETTER P
        '0071'  => '71',   #   LATIN SMALL LETTER Q
        '0072'  => '72',   #   LATIN SMALL LETTER R
        '0073'  => '73',   #   LATIN SMALL LETTER S
        '0074'  => '74',   #   LATIN SMALL LETTER T
        '0075'  => '75',   #   LATIN SMALL LETTER U
        '0076'  => '76',   #   LATIN SMALL LETTER V
        '0077'  => '77',   #   LATIN SMALL LETTER W
        '0078'  => '78',   #   LATIN SMALL LETTER X
        '0079'  => '79',   #   LATIN SMALL LETTER Y
        '007A'  => '7A',   #   LATIN SMALL LETTER Z
        '007B'  => '1B28', #   LEFT CURLY BRACKET
        '007C'  => '1B40', #   VERTICAL LINE
        '007D'  => '1B29', #   RIGHT CURLY BRACKET
        '007E'  => '1B3D', #   TILDE
//      '00A0'  => '1B',   #   ESCAPE TO EXTENSION TABLE (or as NBSP, see note above)
        '00A1'  => '40',   #   INVERTED EXCLAMATION MARK
        '00A3'  => '01',   #   POUND SIGN
        '00A4'  => '24',   #   CURRENCY SIGN
        '00A5'  => '03',   #   YEN SIGN
        '00A7'  => '5F',   #   SECTION SIGN
        '00BF'  => '60',   #   INVERTED QUESTION MARK
        '00C4'  => '5B',   #   LATIN CAPITAL LETTER A WITH DIAERESIS
        '00C5'  => '0E',   #   LATIN CAPITAL LETTER A WITH RING ABOVE
        '00C6'  => '1C',   #   LATIN CAPITAL LETTER AE
        '00C7'  => '09',   #   LATIN CAPITAL LETTER C WITH CEDILLA
        '00C9'  => '1F',   #   LATIN CAPITAL LETTER E WITH ACUTE
        '00D1'  => '5D',   #   LATIN CAPITAL LETTER N WITH TILDE
        '00D6'  => '5C',   #   LATIN CAPITAL LETTER O WITH DIAERESIS
        '00D8'  => '0B',   #   LATIN CAPITAL LETTER O WITH STROKE
        '00DC'  => '5E',   #   LATIN CAPITAL LETTER U WITH DIAERESIS
        '00DF'  => '1E',   #   LATIN SMALL LETTER SHARP S (German)
        '00E0'  => '7F',   #   LATIN SMALL LETTER A WITH GRAVE
        '00E4'  => '7B',   #   LATIN SMALL LETTER A WITH DIAERESIS
        '00E5'  => '0F',   #   LATIN SMALL LETTER A WITH RING ABOVE
        '00E6'  => '1D',   #   LATIN SMALL LETTER AE
        '00E8'  => '04',   #   LATIN SMALL LETTER E WITH GRAVE
        '00E9'  => '05',   #   LATIN SMALL LETTER E WITH ACUTE
        '00EC'  => '07',   #   LATIN SMALL LETTER I WITH GRAVE
        '00F1'  => '7D',   #   LATIN SMALL LETTER N WITH TILDE
        '00F2'  => '08',   #   LATIN SMALL LETTER O WITH GRAVE
        '00F6'  => '7C',   #   LATIN SMALL LETTER O WITH DIAERESIS
        '00F8'  => '0C',   #   LATIN SMALL LETTER O WITH STROKE
        '00F9'  => '06',   #   LATIN SMALL LETTER U WITH GRAVE
        '00FC'  => '7E',   #   LATIN SMALL LETTER U WITH DIAERESIS
        '0393'  => '13',   #   GREEK CAPITAL LETTER GAMMA
        '0394'  => '10',   #   GREEK CAPITAL LETTER DELTA
        '0398'  => '19',   #   GREEK CAPITAL LETTER THETA
        '039B'  => '14',   #   GREEK CAPITAL LETTER LAMDA
        '039E'  => '1A',   #   GREEK CAPITAL LETTER XI
        '03A0'  => '16',   #   GREEK CAPITAL LETTER PI
        '03A3'  => '18',   #   GREEK CAPITAL LETTER SIGMA
        '03A6'  => '12',   #   GREEK CAPITAL LETTER PHI
        '03A8'  => '17',   #   GREEK CAPITAL LETTER PSI
        '03A9'  => '15',   #   GREEK CAPITAL LETTER OMEGA
        '20AC'  => '1B65', #   EURO SIGN
    );
    
    protected static $decodeArray;
    
    public function __construct() {
        self::$decodeArray = array_flip(self::$encodeArray);
    }
    
    public function getName(){
        return 'GSM-03.38';
    }

    /**
     * Encode
     *
     * @TODO To complete
     * @TODO To static function
     * 
     * @param string $input Input must be in UTF-8
     * @return string
     */
    public function encode( $input, $onError = self::ON_ERROR_THROW_EXCEPTION ){
        $len = mb_strlen($input, 'UCS2');

        $output = '';
        for( $i=0; $i<$len; $i++ ){
            $char = mb_substr( $input, $i, 1, 'UCS2');

            if( self::canBeEncoded($char) ){
                $output .= self::encodeChar($char);

            }else{
                switch( $onError ){
                    case self::ON_ERROR_IGNORE:
                        break;

                    case self::ON_ERROR_REPLACE:
                        $output .= "\x3F";
                        break;

                    default:
                        throw new CodecException('Invalid character at position '. ($i));
                }
            }
        }

        return $output;
    }
    
    /**
     * Decode
     *
     * @TODO To implement
     * @TODO To static function
     * 
     * @param string $input
     * @return string
     */
    public function decode( $input ){
    }

    /**
     * Check if a char can be encoded
     *
     * @param $char
     * @return boolean
     */
    protected static function canBeEncoded( $char ){
        return isset(self::$encodeArray[strtoupper(bin2hex($char))]);
    }

    /**
     * Encode a char
     *
     * @param $char
     * @return string
     */
    protected static function encodeChar( $char ){
        return hex2bin(self::$encodeArray[strtoupper(bin2hex($char))]);
    }

    /**
     * Check if a char can be decoded
     *
     * @TODO Check if a char can be decoded
     *
     * @param $char
     * @return boolean
     */
    protected static function canBeDecoded( $char ){
        return TRUE;
    }

    /**
     * Decode a char
     *
     * @TODO Decode a char
     *
     * @param $char
     * @return string
     */
    protected static function decodeChar( $char ){
        return '';
    }

    /**
     * Convert multibyte character code to character
     *
     * @param string $code
     * @return string
     */
    protected function mb_chr( $code ){
        return pack("H*" , $code);
    }

    /**
     * Convert multibyte character to character code
     *
     * @param string $char
     * @return string
     */
    protected function mb_ord( $char ){
        return strtoupper(bin2hex($char));
    }

    /**
     * Check if a character is valid GSM character
     *
     * @param character $char
     * @return boolean
     */
    public function isaValidGsmCharacter( $char ){
        return isset(self::$decodeArray[$this->mb_ord($char)]);
    }
}