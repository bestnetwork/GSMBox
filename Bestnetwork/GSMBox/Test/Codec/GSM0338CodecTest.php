<?php

namespace Bestnetwork\GSMBox\Test\Codec;

use Bestnetwork\GSMBox\Codec\GSM0338Codec;

class GSM0338CodecTest extends \PHPUnit_Framework_TestCase
{
    private static $dataArray = array(
        array('000A', '0A'),
        array('000C', '1B0A'),
        array('000D', '0D'),
        array('0020', '20'),
        array('0021', '21'),
        array('0022', '22'),
        array('0023', '23'),
        array('0024', '02'),
        array('0025', '25'),
        array('0026', '26'),
        array('0027', '27'),
        array('0028', '28'),
        array('0029', '29'),
        array('002A', '2A'),
        array('002B', '2B'),
        array('002C', '2C'),
        array('002D', '2D'),
        array('002E', '2E'),
        array('002F', '2F'),
        array('0030', '30'),
        array('0031', '31'),
        array('0032', '32'),
        array('0033', '33'),
        array('0034', '34'),
        array('0035', '35'),
        array('0036', '36'),
        array('0037', '37'),
        array('0038', '38'),
        array('0039', '39'),
        array('003A', '3A'),
        array('003B', '3B'),
        array('003C', '3C'),
        array('003D', '3D'),
        array('003E', '3E'),
        array('003F', '3F'),
        array('0040', '00'),
        array('0041', '41'),
        array('0042', '42'),
        array('0043', '43'),
        array('0044', '44'),
        array('0045', '45'),
        array('0046', '46'),
        array('0047', '47'),
        array('0048', '48'),
        array('0049', '49'),
        array('004A', '4A'),
        array('004B', '4B'),
        array('004C', '4C'),
        array('004D', '4D'),
        array('004E', '4E'),
        array('004F', '4F'),
        array('0050', '50'),
        array('0051', '51'),
        array('0052', '52'),
        array('0053', '53'),
        array('0054', '54'),
        array('0055', '55'),
        array('0056', '56'),
        array('0057', '57'),
        array('0058', '58'),
        array('0059', '59'),
        array('005A', '5A'),
        array('005B', '1B3C'),
        array('005C', '1B2F'),
        array('005D', '1B3E'),
        array('005E', '1B14'),
        array('005F', '11'),
        array('0061', '61'),
        array('0062', '62'),
        array('0063', '63'),
        array('0064', '64'),
        array('0065', '65'),
        array('0066', '66'),
        array('0067', '67'),
        array('0068', '68'),
        array('0069', '69'),
        array('006A', '6A'),
        array('006B', '6B'),
        array('006C', '6C'),
        array('006D', '6D'),
        array('006E', '6E'),
        array('006F', '6F'),
        array('0070', '70'),
        array('0071', '71'),
        array('0072', '72'),
        array('0073', '73'),
        array('0074', '74'),
        array('0075', '75'),
        array('0076', '76'),
        array('0077', '77'),
        array('0078', '78'),
        array('0079', '79'),
        array('007A', '7A'),
        array('007B', '1B28'),
        array('007C', '1B40'),
        array('007D', '1B29'),
        array('007E', '1B3D'),
//      array('00A0', '1B'),
        array('00A1', '40'),
        array('00A3', '01'),
        array('00A4', '24'),
        array('00A5', '03'),
        array('00A7', '5F'),
        array('00BF', '60'),
        array('00C4', '5B'),
        array('00C5', '0E'),
        array('00C6', '1C'),
        array('00C7', '09'),
        array('00C9', '1F'),
        array('00D1', '5D'),
        array('00D6', '5C'),
        array('00D8', '0B'),
        array('00DC', '5E'),
        array('00DF', '1E'),
        array('00E0', '7F'),
        array('00E4', '7B'),
        array('00E5', '0F'),
        array('00E6', '1D'),
        array('00E8', '04'),
        array('00E9', '05'),
        array('00EC', '07'),
        array('00F1', '7D'),
        array('00F2', '08'),
        array('00F6', '7C'),
        array('00F8', '0C'),
        array('00F9', '06'),
        array('00FC', '7E'),
        array('0393', '13'),
        array('0394', '10'),
        array('0398', '19'),
        array('039B', '14'),
        array('039E', '1A'),
        array('03A0', '16'),
        array('03A3', '18'),
        array('03A6', '12'),
        array('03A8', '17'),
        array('03A9', '15'),
        array('20AC', '1B65')
    );

    /**
     * Test encoding UCS2 to GSM0338
     * 
     * @dataProvider encodeDataProvider
     */
    public function testEncodeChar( $source, $exepted ){
        //$this->markTestIncomplete('This test has not been implemented yet.');
        
        $codec = new GSM0338Codec();

        //$source = mb_convert_encoding($source, 'UCS-2', mb_detect_encoding($source));
        $this->assertEquals($exepted, $codec->encode($source));
    }

    /**
     * Test encoding GSM0338 to UCS2
     *
     * @dataProvider decodeDataProvider
     */
    public function testDecodeChar( $source, $exepted ){
        $this->markTestIncomplete('This test has not been implemented yet.');

        //var_dump(mb_convert_encoding($this->mb_chr($b), 'UTF-8', 'UCS2'));
        $this->assertEquals($exepted, $codec->encode($source));
    }

    /**
     * Test encoding UCS2 to GSM0338
     *
     * @dataProvider encodeDataProvider
     */
    public function testEncodeMessage( $source, $exepted ){
        $this->markTestIncomplete('This test has not been implemented yet.');

        $codec = new GSM0338Codec();

        //$source = mb_convert_encoding($source, 'UCS-2', mb_detect_encoding($source));
        $this->assertEquals($exepted, $codec->encode($source));
    }

    /**
     * Test encoding GSM0338 to UCS2
     *
     * @dataProvider decodeDataProvider
     */
    public function testDecodeMessage( $source, $exepted ){
        $this->markTestIncomplete('This test has not been implemented yet.');

        //var_dump(mb_convert_encoding($this->mb_chr($b), 'UTF-8', 'UCS2'));
        $this->assertEquals($exepted, $codec->encode($source));
    }

    /**
     * Test for GSM characters
     */
    public function testGsmCharacters(){
        $codec = new GSM0338Codec();

        foreach( self::$dataArray as $data ){
            $char = hex2bin($data[1]);
            $this->assertTrue($codec->isaValidGsmCharacter($char), "Character '$char' (0x{$data[1]}) is not recognized as GSM character");
        }
    }

    /**
     * Test for not GSM characters
     */
    public function testNotGsmCharacters(){
        $this->markTestSkipped('This test is too expensive');

        $codec = new GSM0338Codec();

        for( $i=0; $i<= 0xFFFF; $i++){
            $char = pack("H*" , $i);
            $skip_char = FALSE;
            foreach( self::$dataArray as $data ){
                $char_code = strtoupper(bin2hex($char));
                if( $char_code == $data[1] ){
                    $skip_char = TRUE;
                    break 1;
                }
            }
            if( !$skip_char ){
                $this->assertFalse($codec->issValidGsmCharacter($char), "Character '$char' (0x$char_code) is not a valid GSM character");
            }
        }
    }

    public function encodeDataProvider(){
        $result = array();
        foreach( self::$dataArray as $data ){
            $result[] = array(hex2bin($data[0]), hex2bin($data[1]));
        }
        return $result;
    }

    public function decodeDataProvider(){
        $result = array();
        foreach( self::$dataArray as $data ){
            $result[] = array(hex2bin($data[1]), hex2bin($data[0]));
        }
        return $result;
    }
}