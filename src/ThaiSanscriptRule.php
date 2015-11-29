<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;
use ThaiSanskrit\ThaiVisargaConvert;

class ThaiSanscriptRule {


    public function convertTrackMode($romanize) {
        return convertGeneral($romanize, true);
    }

    public static function convert($romanize, $isTracking = false) {

        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeMixConsonant($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeMixVowel($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeSingleConsonant($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $romanize = Util::convertRomanizeSingleVowel($romanize);
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

    public static function convertAnusvaraAndChandrabindu($thaiChar) {
        $thaiChar = $thaiChar . " "; // after space 1  reserve  for condition
        $charList = Util::charList($thaiChar);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] === ThaiSanscript::$anusvara ||
                    $charList[$i] === ThaiSanscript::$chandrabindu) {
                $charList[$i] = ThaiSanscript::getAnusvara($charList[$i + 1]);
            }
        }
        return str_replace(" ", "", Util::convertListTostring($charList));
    }

    public static function convertThaiVowelInFist($thaiChar) {
        mb_internal_encoding("UTF-8");
        $mapping = ThaiSanscript::$thaiVowelInFist;
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

    public static function convertThaiAAInFist($thaiChar) { //แปลงท้ายสุดแก้ปัญหา สระ เอา จะเหลือสระอา ดังนั้นต้องแปลงอีก
        
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

    public static function convertThaiVisarga($thaiChar) {
        $visarga = new ThaiVisargaConvert();
        return $visarga->convert($thaiChar);
    }

    public static function convertThaiVowelPrefix($thaiChar) {
        $thaiChar = "   " . $thaiChar; // before space 2 after space 6  reserve  for condition
        $charList = Util::charList($thaiChar);
        for ($i = 1; $i < count($charList); $i++) {

            $check = $charList[$i] === "เ" ||
                    $charList[$i] === "โ" ||
                    $charList[$i] === "ไ";
            if ($check && Util::isThaiConsonant($charList[$i - 2]) && $charList[$i - 1] == "ร") {
                $charList = Util::swapArray($check, $charList, $i);
                $charList = Util::swapArray($check, $charList, $i - 1);
            } else {
                $charList = Util::swapArray($check, $charList, $i);
            }
        }
        return str_replace(" ", "", Util::convertListTostring($charList));
    }

}
