<?php

namespace ThaiSanskrit;

use ThaiSanskrit\Util;

class ThaiSanscriptInformRule {

    function __construct() {
        
    }

    public function convertTrackMode($romanize) {
        return convert($romanize, true);
    }

    public function convert($romanize, $isTracking = false) {

        $this->printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeMixConsonant($romanize);
        $this->printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeMixVowel($romanize);
        $this->printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeSingleConsonant($romanize);
        $this->printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeSingleVowel($romanize);
        $this->printTrackMode($romanize, $isTracking);

//        $romanize = Util::convertThaiVowelPrefix($romanize);
//        $this->printTrackMode($romanize, $isTracking);


        return $romanize;
    }

    public function printTrackMode($romanize, $isTracking) {
        if ($isTracking) {
            echo ($romanize . " -> ");
        }
    }

}
