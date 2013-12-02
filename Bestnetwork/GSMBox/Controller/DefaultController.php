<?php

namespace Bestnetwork\GSMBox\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Bestnetwork\GSMBox\Entity\Message;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>';
        
        /*
        $a='Λ';
        $a='Ψ';
        $a='Ξ';
        $a='€';
        $a='Π';
        $a='§';*/
        $a="\x0081";
        
        var_dump($a);
        var_dump(pack('h', 0x20AC));
        $a = mb_convert_encoding($a, 'UTF-8', mb_detect_encoding($a));
        var_dump(strlen($a));
        var_dump(mb_strlen($a));
        var_dump($a);
        //var_dump(strlen($a).' => '.dechex(ord($a[0])) . dechex(ord($a[1])) . dechex(ord($a[2])));
        //$a = mb_convert_encoding($a, 'UCS-2', mb_detect_encoding($a));
        var_dump($a);
        //$a = iconv(mb_detect_encoding($a), 'CP437', $a);
        var_dump(dechex(ord($a[0])) . dechex(ord($a[1])));
        
        
        
        
        $gsm = $this->get('gsm_box.gateway.2n.voiceblue');
        /*
        var_dump($gsm->getFirmwareInfo());
        var_dump($gsm->getGatewayInfo());
        var_dump($gsm->getAllSIMsStatus());
        var_dump($gsm->getSIMCount());*/
        
        $message = new Message();
        $message->setFrom('+393772087768')
            ->setTo('+393772087768')
            ->setText('Testo messaggio'."\n".
'[^?!"£$%&/()=?^'."\n".
//chr(0).chr(13).chr(27).chr(254).chr(255).chr(65).chr(32).
'ΛΨΞ€Π§Φ'.
'é{}ÆæßÉΦΓΩΣΘ€'
);
        
        var_dump($gsm->sendSMS($message));
        
        return array();
    }
}