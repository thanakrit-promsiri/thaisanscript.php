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

    public static function isThaiVowel($strChar) {

        $mapping = ThaiSanscript::mappingIsThaiVowel();
        return Util::isCharacter($mapping, $strChar);
    }

    public static function isThaiConsonant($strChar) {

        $mapping = ThaiSanscript::mappingIsThaiConsonant();
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

    public static function convertRomanizeSingleConsonant($romanize) {
        $mapping = ThaiSanscript::$singleConsonant;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixConsonant($romanize) {
        $mapping = ThaiSanscript::$mixConsonant;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeSingleVowel($romanize) {
        $mapping = ThaiSanscript::$singleVowel;
        return Util::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixVowel($romanize) {
        $mapping = ThaiSanscript::$mixVowel;
        return Util::Mapper($mapping, $romanize);
    }

}
