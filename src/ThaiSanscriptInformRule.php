<?php

namespace ThaiSanskrit;

use ThaiSanskrit\Util;
use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptInformRule {

    public $informmapper;

    public function __construct() {
        $this->informmapper = new ThaiSanscript(TRUE);
    }

    public function convertTrackMode($txt) {
        return convert($txt, true);
    }

    public function convert($txt, $isTracking = false) {
        $informmapper = $this->informmapper;

        $this->printTrackMode($txt, $isTracking);

        $txt = Util::convertRomanizeMixConsonant($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = Util::convertRomanizeMixVowel($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = Util::convertRomanizeSingleConsonant($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = Util::convertRomanizeSingleVowel($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = $this->convertBindu($txt);
        $this->printTrackMode($txt, $isTracking);


        $txt = Util::convertThaiVowelInFist($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = Util::convertThaiVowelPrefix($txt, $informmapper);
        $this->printTrackMode($txt, $isTracking);

        $txt = $this->removeA($txt);
        $this->printTrackMode($txt, $isTracking);

        

        $txt = $this->swapAnusvaraAndChandrabindu($txt);
        $this->printTrackMode($txt, $isTracking);
        
        $txt = $this->convertChandrabindu($txt);
        $this->printTrackMode($txt, $isTracking);

        return $txt;
    }

    public function printTrackMode($txt, $isTracking) {
        if ($isTracking) {
            echo ($txt . " -> ");
        }
    }

    public function convertBindu($txt) {
        $txt = $txt . " ";
        $charList = Util::charList($txt);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] != " ") {
                $_current = $charList[$i];
                $_after1 = $charList[$i + 1];
                $condition = Util::isThaiConsonant($_current) &&
                        $this->informmapper->anusvara != $_current &&
                        $this->informmapper->chandrabindu != $_current &&
                        $_after1 != "a" &&
                        !Util::isThaiVowel($_after1);
                if ($condition) {
                    $charList[$i] = $_current . "ฺ";
                }
            }
        }
        $txt = Util::convertListTostring($charList);

        return $txt;
    }

    public function removeA($txt) {

        $charList = Util::charList($txt);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] == "a") {
                $charList[$i] = "";
            }
        }
        $txt = Util::convertListTostring($charList);

        return $txt;
    }

    public function convertChandrabindu($txt) {

        $txt = str_replace($this->informmapper->chandrabindu, "ัํ", $txt);

        return $txt;
    }

    public function swapAnusvaraAndChandrabindu($txt) {
        $txt = $txt . "  "; //  after space 2  reserve  for condition
        $charList = Util::charList($txt);
        for ($i = 1; $i < count($charList); $i++) {


            if ($charList[$i] == "า") {

                if ($charList[$i + 1] == "ํ") {
                    $charList = Util::swapArray(TRUE, $charList, $i+1);
                } elseif ($charList[$i + 1] == $this->informmapper->chandrabindu) {
                    $charList = Util::swapArray(TRUE, $charList, $i+1);                
                }
            }
        }
        return str_replace(" ", "", Util::convertListTostring($charList));
    }

}
