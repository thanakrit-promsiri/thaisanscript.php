<?php

include '../src/ThaiSanscriptRule.php';

use ThaiSanskrit\ThaiSanscriptRule;
use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptRuleTest extends PHPUnit_Framework_TestCase {

    function __construct() {
        
    }

    private function isThaiVowel($array, $asrt) {
        foreach ($array as $value) {
            $result = ThaiSanscriptRule::isThaiVowel($value);
            $this->assertEquals($asrt, $result ? 1 : 0);
        }
    }

    private function isThaiConsonant($array, $asrt) {
        foreach ($array as $value) {
            $result = ThaiSanscriptRule::isThaiConsonant($value);
            $this->assertEquals($asrt, $result ? 1 : 0);
        }
    }

    public function testisThaiVowel() {
        $array1 = ThaiSanscript::$singleConsonant;
        $array2 = ThaiSanscript::$mixConsonant;
        $array3 = ThaiSanscript::$singleVowel;
        $array4 = ThaiSanscript::$mixVowel;
        $this->isThaiVowel($array1, 0);
        $this->isThaiVowel($array2, 0);
        $this->isThaiVowel($array3, 1);
        $this->isThaiVowel($array4, 1);
    }

    public function testisThaiConsonant() {
        $array1 = ThaiSanscript::$singleConsonant;
        $array2 = ThaiSanscript::$mixConsonant;
        $array3 = ThaiSanscript::$singleVowel;
        $array4 = ThaiSanscript::$mixVowel;
        $this->isThaiConsonant($array1, 1);
        $this->isThaiConsonant($array2, 1);
        $this->isThaiConsonant($array3, 0);
        $this->isThaiConsonant($array4, 0);
    }

    public function testconvertListTostring() {

        $asrt = "as df ฟหกดเสวง";
        $src = ThaiSanscriptRule::convertListTostring(array('a', 's', ' ', 'd', 'f', ' ', 'ฟ', 'ห', 'ก', 'ด', 'เ', 'ส', 'ว', 'ง'));
        $this->assertEquals($asrt, $src);
    }

    public function testcharList() {

        $asrt = array('a', 's', ' ', 'd', 'f', ' ', 'ฟ', 'ห', 'ก', 'ด', 'เ', 'ส', 'ว', 'ง');
        $src = ThaiSanscriptRule::charList("as df ฟหกดเสวง");
        $this->assertEquals($asrt, $src);
    }

    public function testMapper() {
        $asrt = "ฟหก";
        $romanize = "ASD";
        $mapping = array("A" => "ฟ", "S" => "ห", "D" => "ก");
        $src = ThaiSanscriptRule::Mapper($mapping, $romanize);
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeSingleConsonant() {
        $mapping = ThaiSanscript::$singleConsonant;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = ThaiSanscriptRule::convertRomanizeSingleConsonant($romanize);
        $src = implode("", $src);
        $asrt = "กคงจชญฏฑณตทนปพมยรลวศษสหหฺ'";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeMixConsonant() {
        $mapping = ThaiSanscript::$mixConsonant;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = ThaiSanscriptRule::convertRomanizeMixConsonant($romanize);
        $src = implode("", $src);
        $asrt = "ขฉฐถผญฆฌฒธภ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeSingleVowel() {
        $mapping = ThaiSanscript::$singleVowel;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = ThaiSanscriptRule::convertRomanizeSingleVowel($romanize);
        $src = implode("", $src);
        $asrt = "ะาิีุูฤเโ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeMixVowel() {
        $mapping = ThaiSanscript::$mixVowel;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = ThaiSanscriptRule::convertRomanizeMixVowel($romanize);
        $src = implode("", $src);
        $asrt = "ไเาฤ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertThaiVowelPrefix() {

        $src = "อเกกโนกไลกเา";
        $src = ThaiSanscriptRule::convertThaiVowelPrefix($src);
        $asrt = "เอกโกนไกลเกา";
        $this->assertEquals($asrt, $src);
    }

    public function  testswapArray() {
        $src = array("A", "B");
        $src = ThaiSanscriptRule::swapArray(true,$src,1);
        $asrt = array("B", "A");
        $this->assertEquals($asrt, $src);
    }

}
