<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;
use ThaiSanskrit\ThaiVisargaConvert;
use ThaiSanskrit\Util;

class ThaiSanscriptRule {

    public $thaimapper;

    function __construct() {
        $this->thaimapper = new ThaiSanscript();
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

        $romanize = $this->convertAnusvaraAndChandrabindu($romanize);
        $this->printTrackMode($romanize, $isTracking);

        $romanize = Util::convertThaiVowelInFist($romanize);
        $this->printTrackMode($romanize, $isTracking);

        $thaiChar = $romanize;

        $thaiChar = $this->convertThaiVisarga($thaiChar);
        $this->printTrackMode($thaiChar, $isTracking);

        $thaiChar = Util::convertThaiVowelPrefix($thaiChar);
        $this->printTrackMode($thaiChar, $isTracking);

        $thaiChar = $this->convertThaiAAInFist($thaiChar);
        $this->printTrackMode($thaiChar, $isTracking);

        return $thaiChar;
    }

    public function printTrackMode($romanize, $isTracking) {
        if ($isTracking) {
            echo ($romanize . " -> ");
        }
    }

    public function convertAnusvaraAndChandrabindu($thaiChar) {
        $thaiChar = $thaiChar . " "; // after space 1  reserve  for condition
        $charList = Util::charList($thaiChar);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] === $this->thaimapper->anusvara ||
                    $charList[$i] === $this->thaimapper->chandrabindu) {
                $charList[$i] = $this->thaimapper->getAnusvara($charList[$i + 1]);
            }
        }
        return str_replace(" ", "", Util::convertListTostring($charList));
    }

   

    public function convertThaiAAInFist($thaiChar) { //แปลงท้ายสุดแก้ปัญหา สระ เอา จะเหลือสระอา ดังนั้นต้องแปลงอีก
        $charList = Util::charList($thaiChar);
        for ($index = 1; $index < count($charList); $index++) {

            $check1 = !Util::isThaiConsonant($charList[$index - 1]);
            $check2 = $charList[$index] == 'า';

            if ($check1 && $check2) {
                $charList[$index] = "อา";
            }
        }
        $thaiChar = Util::convertListTostring($charList);
        $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); //Remove ZERO WIDTH JOINER

        return $thaiChar;
    }

    public function convertThaiVisarga($thaiChar) {
        $visarga = new ThaiVisargaConvert();
        return $visarga->convert($thaiChar);
    }
    
      
    

}
