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

        $romanize = $this->convertThaiVowelInFist($romanize);
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

    public function convertThaiVowelInFist($thaiChar) {
        mb_internal_encoding("UTF-8");
        $mapping = $this->thaimapper->thaiVowelInFist;
        $charList = Util::charList($thaiChar);

        if (count($charList) > 0) {
            foreach ($mapping as $key => $value) {
                if ($charList[0] === $key) {
                    $charList[0] = $value;
                }
//                $charList = Util::charList($thaiChar);
                for ($index = 1; $index < count($charList); $index++) {

                    $check1 = !Util::isThaiConsonant($charList[$index - 1]);
                    $check2 = $charList[$index] == $key;
                    $check3 = $charList[$index] != "เ" && $charList[$index] != "า"; //ยกเว้นไว้กรณี สระเอา ก่อน

                    if ($check1 && $check2 && $check3) {
                        $charList[$index] = $value;
                    }
                }
                $thaiChar = Util::convertListTostring($charList);
                $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); //Remove ZERO WIDTH JOINER
            }
        }
        return $thaiChar;
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
