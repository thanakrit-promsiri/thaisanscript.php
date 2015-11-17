<?php
namespace ThaiSanskrit;
use ThaiSanskrit\ThaiSanscriptRule;

class ThaiSanscriptAPI {

    public function __construct() {       
    }
    
    public function convert($romanize) {
        echo  ThaiSanscriptRule::convert($romanize, FALSE);
    }
    public function convertTracking($romanize) {
        echo ThaiSanscriptRule::convert($romanize, TRUE);
    }

}
