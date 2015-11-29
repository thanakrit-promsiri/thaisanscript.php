<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;
use ThaiSanskrit\ThaiVisargaConvert;

class ThaiSanscriptInformRule {


    public function convertTrackMode($romanize) {
        return convertGeneral($romanize, true);
    }

    public static function convert($romanize, $isTracking = false) {

        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertRomanizeMixConsonant($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertRomanizeMixVowel($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertRomanizeSingleConsonant($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertRomanizeSingleVowel($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertAnusvaraAndChandrabindu($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = ThaiSanscriptRule::convertThaiVowelInFist($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $thaiChar = $romanize;

        $thaiChar = ThaiSanscriptRule::convertThaiVisarga($thaiChar);
        ThaiSanscriptRule::printTrackMode($thaiChar, $isTracking);

        $thaiChar = ThaiSanscriptRule::convertThaiVowelPrefix($thaiChar);
        ThaiSanscriptRule::printTrackMode($thaiChar, $isTracking);

        $thaiChar = ThaiSanscriptRule::convertThaiAAInFist($thaiChar);
        ThaiSanscriptRule::printTrackMode($thaiChar, $isTracking);

        return $thaiChar;
    }

    public static function printTrackMode($romanize, $isTracking) {
        if ($isTracking) {
            echo ($romanize . " -> ");
        }
    }

   

}
