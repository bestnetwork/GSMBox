<?php

namespace Bestnetwork\GSMBox\Codec;

interface CodecInterface {
    
    /**
     * Get the name of codec
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Encode
     * 
     * @param string $input
     * @return string
     */
    public function encode( $input );
    
    /**
     * Decode
     * 
     * @param string $input
     * @return string
     */
    public function decode( $input );
}