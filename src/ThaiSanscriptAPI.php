<?php
namespace ThaiSanskrit;
use ThaiSanskrit\ThaiSanscriptRule;

class ThaiSanscriptAPI {

    public function __construct() {       
    }
    
    public function transliteration($romanize) {
        return  ThaiSanscriptRule::transliteration($romanize);
    }
    public function transliterationTracking($romanize) {
        return ThaiSanscriptRule::convert($romanize, TRUE);
    }
     public function transliterationToArray($romanize) {
        return  ThaiSanscriptRule::transliterationToArray($romanize);
    }

}
