<?php

namespace Bestnetwork\GSMBox\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $gsm = $this->get('gsm_box.gateway.2n.voiceblue');
        
        return array();
    }
}