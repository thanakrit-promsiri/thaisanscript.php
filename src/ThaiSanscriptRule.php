<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptRule {

    public static function transliterationToArray($romanize, $devanagari = array()) {
        mb_internal_encoding("UTF-8");
        $returnArray = array();
        $listRomanize = preg_split('/\r\n|\r|\n/', $romanize);
        
        foreach ($listRomanize as $key => $line) {

            $line = mb_strtolower($line, "UTF-8");
            $syllableThai = explode(" ", $line);
            $syllableRomanize = explode(" ", $line);

            for ($i = 0; $i < count($syllableThai); $i++) {
                $syllableThai[$i] = ThaiSanscriptRule::convert($syllableThai[$i]);
            }
            $returnArray['thai'][$key] = $syllableThai;
            $returnArray['romanize'][$key] = $syllableRomanize;
        }
        
        $listDevanagari = preg_split('/\r\n|\r|\n/', $devanagari);
        foreach ($listDevanagari as $key => $line) {
            
        }

        return $returnArray;
    }

    public static function transliteration($txt) {
        mb_internal_encoding("UTF-8");
//        $listLine = explode('\r', $txt);
        $listLine = preg_split('/\r\n|\r|\n/', $txt);
        $listLineThai = array();
        print_r($listLine);
        foreach ($listLine as $line) {

            $line = mb_strtolower($line, "UTF-8");
            $syllableThai = explode(" ", $line);
            $syllableRoman = explode(" ", $line);

            $builderThai = "";
            $builderRoman = "";
            for ($i = 0; $i < count($syllableThai); $i++) {
                $syllableThai[$i] = ThaiSanscriptRule::convert($syllableThai[$i]);
                $builderThai .= $syllableThai[$i];
                $builderThai .= " ";
                $builderRoman .= $syllableRoman[$i];
                $builderRoman .= " ";
            }

            $listLineThai[] = $builderThai . "\n";
            $listLineThai[] = $builderRoman . "\n";
//            listLineThai.add(builderRoman.toString());
//            listLineThai.add("\n");
        }

        return ThaiSanscriptRule::convertListTostring($listLineThai);
//        echo ThaiSanscriptRule::convertListTostring($listLine);
    }

    public function convertTrackMode($romanize) {
        return convert($romanize, true);
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

        $romanize = ThaiSanscriptRule::convertThaiVowelInFist($romanize);
        ThaiSanscriptRule::printTrackMode($romanize, $isTracking);

        $thaiChar = $romanize;

        $thaiChar = ThaiSanscriptRule::convertThaiVisarga($thaiChar);
        ThaiSanscriptRule::printTrackMode($thaiChar, $isTracking);

        $thaiChar = ThaiSanscriptRule::convertThaiVowelPrefix($thaiChar);
        ThaiSanscriptRule::printTrackMode($thaiChar, $isTracking);

        return $thaiChar;
    }

    public static function printTrackMode($romanize, $isTracking) {
        if ($isTracking) {
            echo ($romanize . " -> ");
        }
    }

    public static function convertRomanizeSingleConsonant($romanize) {
        $mapping = ThaiSanscript::$singleConsonant;
        return ThaiSanscriptRule::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixConsonant($romanize) {
        $mapping = ThaiSanscript::$mixConsonant;
        return ThaiSanscriptRule::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeSingleVowel($romanize) {
        $mapping = ThaiSanscript::$singleVowel;
        return ThaiSanscriptRule::Mapper($mapping, $romanize);
    }

    public static function convertRomanizeMixVowel($romanize) {
        $mapping = ThaiSanscript::$mixVowel;
        return ThaiSanscriptRule::Mapper($mapping, $romanize);
    }

    public static function convertThaiVowelInFist($thaiChar) {
        $mapping = ThaiSanscript::$thaiVowelInFist;
        $thaiCharArray = ThaiSanscriptRule::charList($thaiChar);

        if (count($thaiCharArray) > 0) {
            $i = 0;
            foreach ($mapping as $key => $value) {
                if ($thaiCharArray[0] === $key) {
                    mb_internal_encoding("UTF-8");
                    $thaiChar = mb_substr($thaiChar, 1);
                    $thaiChar = $value . $thaiChar;
                }
                $charList = ThaiSanscriptRule::charList($thaiChar);
                for ($index = 0; $index < count($charList); $index++) {
                    if ($index > 0) {
                        $check1 = !ThaiSanscriptRule::isThaiConsonant($charList[$index - 1]);
                        $check2 = $charList[$index] === $key;
                        $check3 = $charList[$index] != "เ" && $charList[$index] != "า";

                        if ($check1 && $check2 && $check3) {
                            $charList[$index] = $value;
                        }
                    }
                }
                $thaiChar = ThaiSanscriptRule::convertListTostring($charList);
            }
        }
        return $thaiChar;
    }

    public static function convertThaiVisarga($thaiChar) {
        $thaiChar = " " . $thaiChar . "  "; // before space 1 after space 2
        $charList = ThaiSanscriptRule::charList($thaiChar);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] === "ะ") {
                $before = $charList[$i - 1];

                $after1 = $charList[$i + 1];
                $after2 = $charList[$i + 2];


                if ($before == " " && $charList[$i] == "ะ") {
                    // "ะนกะ" -> "อันกะ";
                    $charList[$i] = "อั";
                }

                if (($charList[$i] === "ะ" && ThaiSanscriptRule::isThaiConsonant($after1) && ThaiSanscriptRule::isThaiConsonant($after2)) && $after2 != "ร") {
                    //'ะกธ' ร becuase 
                    $charList[$i] = "ั";
                }

                if ($charList[$i] == "ะ" && ThaiSanscriptRule::isThaiConsonant($after1) && $after1 != " " && $after2 == " ") {
                    // ร'ะก  ' -> 'รัก  '
                    $charList[$i] = "ั";
                }
                if ($charList[$i] == "ะ" && ThaiSanscriptRule::isThaiConsonant($after1) && !ThaiSanscriptRule::isThaiCharacter($after2) && $after2 != ("์")) {
                    // เว้น namaḥ  'นะมะห์'  trayaḥ  'ตระยะห์' ไม่ให้เป็น นะมัห
                    $charList[$i] = "ั";
                }
            }
        }
//        print_r($charList);
        return trim(ThaiSanscriptRule::convertListTostring($charList));
    }

    public static function convertThaiVowelPrefix($thaiChar) {

        $charList = ThaiSanscriptRule::charList($thaiChar);
        for ($i = 0; $i < count($charList); $i++) {
            if ($i > 0) {
                $check = $charList[$i] === "เ" ||
                        $charList[$i] === "โ" ||
                        $charList[$i] === "ไ";
                $charList = ThaiSanscriptRule::swapArray($check, $charList, $i);
            }
        }

        return trim(ThaiSanscriptRule::convertListTostring($charList));
    }

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
        return ThaiSanscriptRule::isCharacter($mapping, $strChar);
    }

    public static function isThaiConsonant($strChar) {

        $mapping = ThaiSanscript::mappingIsThaiConsonant();
        return ThaiSanscriptRule::isCharacter($mapping, $strChar);
    }

    public static function isThaiCharacter($strChar) {
        return ThaiSanscriptRule::isThaiConsonant($strChar) || ThaiSanscriptRule::isThaiVowel($strChar);
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

}
