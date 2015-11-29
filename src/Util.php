<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;

class Util {
    /*     * *******************    util part  ************************ */

    public static function swapArray($swapCondition, $charList, $index) {

        if ($swapCondition && count($charList) > 1) {
            $tmp = $charList[$index];
            $charList[$index] = $charList[$index - 1];
            $charList[$index - 1] = $tmp;
        }
        return $charList;
    }

    public static function Mapper($mapping, $romanize) {
        foreach ($mapping as $key => $value) {
            $romanize = str_replace($key, $value, $romanize);
        }
        return $romanize;
    }

    /*     * *******************    check part  ************************ */

    public static function isThaiVowel($strChar, $thaimapper = "") {

        $mapping = Util::getThaimapper($thaimapper)->mappingIsThaiVowel();
        return Util::isCharacter($mapping, $strChar);
    }

    public static function isThaiConsonant($strChar, $thaimapper = "") {

        $mapping = Util::getThaimapper($thaimapper)->mappingIsThaiConsonant();
        return Util::isCharacter($mapping, $strChar);
    }

    public static function isThaiCharacter($strChar) {
        return Util::isThaiConsonant($strChar) || Util::isThaiVowel($strChar);
    }

    public static function isCharacter($mapping, $strChar) {

        $returnVal = false;
        if (isset($mapping[$strChar])) {
            $returnVal = $mapping[$strChar];
        }
        return $returnVal;
    }

    /*     * *******************    convertList  ************************ */

    public static function convertListTostring($charList) {
        return implode("", $charList);
    }

    public static function charList($thaiChar) {
        return preg_split('//u', $thaiChar, -1, PREG_SPLIT_NO_EMPTY);
    }

    /*     * *******************    convert ************************ */

    public static function convertRomanizeSingleConsonant($romanize, $thaimapper = "") {
        $mapping = Util::getThaimapper($thaimapper)->singleConsonant;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixConsonant($romanize, $thaimapper = "") {
        $mapping = Util::getThaimapper($thaimapper)->mixConsonant;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeSingleVowel($romanize, $thaimapper = "") {
        $mapping = Util::getThaimapper($thaimapper)->singleVowel;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixVowel($romanize, $thaimapper = "") {
        $mapping = Util::getThaimapper($thaimapper)->mixVowel;
        return Util::Mapper($mapping, $romanize);
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

    public static function getThaimapper($thaimapper = "") {
        if ($thaimapper == "") {
            $thaimapper = new ThaiSanscript();
        }
        return $thaimapper;
    }

}
