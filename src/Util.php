<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;

class Util {

    public $thaimapper;

    public function __construct($inForm = FALSE) {
        $this->thaimapper = new ThaiSanscript($inForm);
    }

    /*     * *******************    util part  ************************ */

    public function swapArray($swapCondition, $charList, $index) {

        if ($swapCondition && isset($charList[1])) {
//        if ($swapCondition && count($charList) > 1) {
            $tmp = $charList[$index];
            $charList[$index] = $charList[$index - 1];
            $charList[$index - 1] = $tmp;
        }
        return $charList;
    }

    public function Mapper($mapping, $romanize) {
        foreach ($mapping as $key => $value) {
            $romanize = str_replace($key, $value, $romanize);
        }
        return $romanize;
    }

    /*     * *******************    check part  ************************ */

    public function isThaiVowel($strChar) {
        $mapping = $this->thaimapper->mappingIsThaiVowel();
        return isset($mapping[$strChar]);
    }

    public function isThaiConsonant($strChar) {
        $mapping = $this->thaimapper->mappingIsThaiConsonant();
        return isset($mapping[$strChar]);
    }

    public function isThaiCharacter($strChar) {
        return $this->isThaiConsonant($strChar) || $this->isThaiVowel($strChar);
    }

    /*     * *******************    convertList  ************************ */

    public function convertListTostring($charList) {
        return implode("", $charList);
    }

    public function charList($thaiChar) {
        return preg_split('//u', $thaiChar, -1, PREG_SPLIT_NO_EMPTY);
    }

    /*     * *******************    convert ************************ */

    public function convertNumber($txt) {
        return str_replace($this->thaimapper->numRm, $this->thaimapper->numTh, $txt);
    }

    public function convertRomanizeSingleConsonant($romanize) {
        return str_replace($this->thaimapper->singleConsonantRm, $this->thaimapper->singleConsonantTh, $romanize);
    }

    public function convertRomanizeMixConsonant($romanize) {
        return str_replace($this->thaimapper->mixConsonantRm, $this->thaimapper->mixConsonantTh, $romanize);
    }

    public function convertRomanizeSingleVowel($romanize) {
        return str_replace($this->thaimapper->singleVowelRm, $this->thaimapper->singleVowelTh, $romanize);
    }

    public function convertRomanizeMixVowel($romanize) {
        return str_replace($this->thaimapper->mixVowelRm, $this->thaimapper->mixVowelTh, $romanize);
    }

    public function convertAO($txt) {
        return str_replace("ัโ", "ะโ", $txt);
    }

    public function convertAE($txt) {
        return str_replace("ัเ", "ะเ", $txt);
    }

    public function convertThaiVowelPrefix($thaiChar) {
        $thaiChar = "     " . $thaiChar; // before space 4 after space 6  reserve  for condition
        $charList = $this->charList($thaiChar);
        foreach ($charList as $i => $char) {
//        for ($i = 1; $i < count($charList); $i++) {
            $check = $char === "เ" ||
                    $char === "โ" ||
                    $char === "ไ";
            if ($check) {

                $condition1 = $check &&
                        $this->isThaiConsonant($charList[$i - 2]) &&
                        $charList[$i - 1] == "ร" &&
                        $charList[$i - 3] != "เ" &&
                        $charList[$i - 3] != "โ" &&
                        $charList[$i - 3] != "ไ";
//                  ยaตฺกฺรเาญฺ
                $condition2 = $check &&
                        $this->isThaiConsonant($charList[$i - 3]) &&
                        $charList[$i - 1] == "ร" &&
                        $charList[$i - 2] == "ฺ" &&
                        $charList[$i - 4] != "เ" &&
                        $charList[$i - 4] != "โ" &&
                        $charList[$i - 4] != "ไ";

                if ($condition1) {
                    $charList = $this->swapArray($check, $charList, $i);
                    $charList = $this->swapArray($check, $charList, $i - 1);
                } elseif ($condition2) {
                    $charList = $this->swapArray($check, $charList, $i);
                    $charList = $this->swapArray($check, $charList, $i - 1);
                    $charList = $this->swapArray($check, $charList, $i - 2);
                } elseif ($this->isThaiConsonant($charList[$i - 1])) {
                    $charList = $this->swapArray($check, $charList, $i);
                }
            }
        }
        return str_replace(" ", "", $this->convertListTostring($charList));
    }

    public function convertThaiVowelInFist($thaiChar) {
        $thaiChar = $thaiChar . " "; // before space 3 after space 6  reserve  for condition
        $mapping = $this->thaimapper->thaiVowelInFist;
        $charList = $this->charList($thaiChar);

        if (isset($charList[0])) {
//        if (count($charList) > 0) {
            foreach ($mapping as $key => $value) {
                if ($charList[0] === $key) {
                    $charList[0] = $value;
                }
//                $charList = $this->charList($thaiChar);
                foreach ($charList as $index => $char) {
//                for ($index = 1; $index < count($charList); $index++) {
                    if ($index > 0) {
                        $check1 = !$this->isThaiConsonant($charList[$index - 1]) && $char == $key;
                        $check2 = $charList[$index] != "เ" && $charList[$index] != "า"; //ยกเว้นไว้กรณี สระเอา ก่อน

                        if ($check1 && $check2) {
                            $charList[$index] = $value;
                        } elseif ($check1 && $charList[$index] == "เ" && $charList[$index + 1] != "า") {
                            $charList[$index] = $value;
                        } elseif ($check1 && $charList[$index] == "า" && $charList[$index - 1] != "เ") {
                            $charList[$index] = $value;
                        }
                    }
                }
                $thaiChar = $this->convertListTostring($charList);
                $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); //Remove ZERO WIDTH JOINER
            }
        }
        return $thaiChar;
    }

    public function convertThaiAAInFist($thaiChar) { //แปลงท้ายสุดแก้ปัญหา สระ เอา จะเหลือสระอา ดังนั้นต้องแปลงอีก
        $charList = $this->charList($thaiChar);
        foreach ($charList as $i => $char) {
            //for ($index = 1; $index < count($charList); $index++) {
            if ($i > 0) {
                $check1 = !$this->isThaiConsonant($charList[$i - 1]) &&
                        $char == 'า';

                if ($check1) {
                    $charList[$i] = "อา";
                }
            }
        }
        $thaiChar = $this->convertListTostring($charList);
        $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); //Remove ZERO WIDTH JOINER
        return $thaiChar;
    }

}
