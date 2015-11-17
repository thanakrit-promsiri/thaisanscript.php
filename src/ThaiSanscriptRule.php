<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptRule {

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
//
//        romanize = convertThaiVowelInFist(romanize);
//        printTrackMode(romanize, isTracking);
//
        $thaiChar = $romanize;
//       
//        thaiChar = convertThaiVisarga(thaiChar);
//        printTrackMode(thaiChar, isTracking);
//       
//        thaiChar = convertThaiVowelPrefix(thaiChar);
//        printTrackMode(thaiChar, isTracking);
//
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

//
//    public String convertThaiVowelInFist(String thaiChar) {
//        Map<String, String> mapping = MappingCharacter.mappingThaiVowelInFist();
//        if (thaiChar.length() > 0) {
//            for (Map.Entry<String, String> entrySet : mapping.entrySet()) {
//                String key = entrySet.getKey();
//                String value = entrySet.getValue();
//                //char[] fist = romanize.toCharArray();
//                if (thaiChar.charAt(0) == key.charAt(0)) {
//                    thaiChar = thaiChar.substring(1);
//                    thaiChar = value + thaiChar;
//
//                }
//
//                List<String> charList = charList(thaiChar);
//
//                for (int i = 0; i < charList.size(); i++) {
//                    if (i > 0) {
//                        boolean check1 = !isThaiConsonant(charList.get(i - 1));
//                        boolean check2 = charList.get(i).equals(key);
//                        boolean check3 = !charList.get(i).equals("เ") && !charList.get(i).equals("า") ;
//                        if (check1 && check2 && check3) {
//                            charList.set(i, value);
//                        }
//                    }
//
//                }
//
//                thaiChar = convertListTostring(charList);
//            }
//        }
//
//        return thaiChar;
//    }
//
//    public String convertThaiVisarga(String thaiChar) {
//        thaiChar = " " + thaiChar + "  ";
//        List<String> charList = charList(thaiChar);
//
//        for (int i = 0; i < charList.size(); i++) {
//            boolean check = charList.get(i).equals("ะ");
//
//            if (check) {
//
//                String beforeA = charList.get(i - 1);
//                String currentA = charList.get(i);
//                String afterA1 = charList.get(i + 1);
//                String afterA2 = charList.get(i + 2);
//
//                if (beforeA.equals(" ") && charList.get(i).equals("ะ")) {
//                    charList.set(i, "อั");
//                }
//
//                if ((isThaiConsonant(afterA1) && isThaiConsonant(afterA2)) && charList.get(i).equals("ะ") && !afterA2.equals("ร") ) {
//                    charList.set(i, "ั");
//                }
//
//                if ((isThaiConsonant(afterA1) && !afterA1.equals(" ") && afterA2.equals(" ")) && charList.get(i).equals("ะ")) {
//                    charList.set(i, "ั");
//                }
//
//                if (isThaiConsonant(afterA1) && !isThaiConsonant(afterA2) && !isThaiVowel(afterA2) && !afterA2.equals("ฺ")) {
//                    charList.set(i, "ั");
//                }
//                
//             
//
//            }
//        }
//        return convertListTostring(charList).trim();
//    }
//
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
