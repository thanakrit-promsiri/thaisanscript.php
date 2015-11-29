<?php

include '../src/ThaiSanscriptRule.php';
include '../src/ThaiVisargaRuleConvert.php';
include '../src/Util.php';

use ThaiSanskrit\ThaiSanscriptRule;
use ThaiSanskrit\ThaiSanscript;
use ThaiSanskrit\Util;

class ThaiSanscriptRuleTest extends PHPUnit_Framework_TestCase {

    private function isThaiVowel($array, $asrt) {
        foreach ($array as $value) {
            $result = Util::isThaiVowel($value);
            $this->assertEquals($asrt, $result ? 1 : 0);
        }
    }

    private function isThaiConsonant($array, $asrt) {
        foreach ($array as $value) {
            $result = Util::isThaiConsonant($value);
            $this->assertEquals($asrt, $result ? 1 : 0);
        }
    }

    private function isThaiCharacter($array, $asrt) {
        foreach ($array as $value) {
            $result = Util::isThaiCharacter($value);
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
        $this->isThaiVowel(array(" "), 0);
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
        $this->isThaiConsonant(array(" "), 0);
    }

    public function testisThaiCharacter() {
        $array1 = ThaiSanscript::$singleConsonant;
        $array2 = ThaiSanscript::$mixConsonant;
        $array3 = ThaiSanscript::$singleVowel;
        $array4 = ThaiSanscript::$mixVowel;
        $this->isThaiCharacter($array1, 1);
        $this->isThaiCharacter($array2, 1);
        $this->isThaiCharacter($array3, 1);
        $this->isThaiCharacter($array4, 1);
        $this->isThaiCharacter(array(" ", "-", "@", ","), 0);
    }

    public function testconvertListTostring() {

        $asrt = "as df ฟหกดเสวง";
        $src = Util::convertListTostring(array('a', 's', ' ', 'd', 'f', ' ', 'ฟ', 'ห', 'ก', 'ด', 'เ', 'ส', 'ว', 'ง'));
        $this->assertEquals($asrt, $src);
    }

    public function testcharList() {

        $asrt = array('a', 's', ' ', 'd', 'f', ' ', 'ฟ', 'ห', 'ก', 'ด', 'เ', 'ส', 'ว', 'ง');
        $src = Util::charList("as df ฟหกดเสวง");
        $this->assertEquals($asrt, $src);
    }

    public function testMapper() {
        $asrt = "ฟหก";
        $romanize = "ASD";
        $mapping = array("A" => "ฟ", "S" => "ห", "D" => "ก");
        $src = Util::Mapper($mapping, $romanize);
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeSingleConsonant() {
        $mapping = ThaiSanscript::$singleConsonant;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = Util::convertRomanizeSingleConsonant($romanize);
        $src = implode("", $src);
        $asrt = "กคงจชญฏฑณตทนปพมยรลวฬศษสหँํห์'";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeMixConsonant() {
        $mapping = ThaiSanscript::$mixConsonant;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = Util::convertRomanizeMixConsonant($romanize);
        $src = implode("", $src);
        $asrt = "ขฉฐถผญฆฌฒธภ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeSingleVowel() {
        $mapping = ThaiSanscript::$singleVowel;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = Util::convertRomanizeSingleVowel($romanize);
        $src = implode("", $src);
        $asrt = "ะาิีุูฤเโ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertRomanizeMixVowel() {
        $mapping = ThaiSanscript::$mixVowel;
        $revert = array();
        $romanize = ThaiSanscript::setRevertFlag($revert, $mapping);
        $src = Util::convertRomanizeMixVowel($romanize);
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

    public function testswapArray() {
        $src = array("A", "B");
        $src = Util::swapArray(true, $src, 1);
        $asrt = array("B", "A");
        $this->assertEquals($asrt, $src);
    }

    public function testconvertThaiVowelInFist() {

        $src = "ะ า ิ ี ุ ู เ โ ะ";
        $src = ThaiSanscriptRule::convertThaiVowelInFist($src);
        $asrt = "อะ า อิ อี อุ อู เ โอ อะ";
        $this->assertEquals($asrt, $src);
    }

    public function testconvertThaiVisarga() {
//        try {
        $src = "ะนกะ";
        $asrt = "อันกะ";
        $src = ThaiSanscriptRule::convertThaiVisarga($src);
        $this->assertEquals($asrt, $src);

        $src = "ระก";
        $asrt = "รัก";
        $src = ThaiSanscriptRule::convertThaiVisarga($src);
        $this->assertEquals($asrt, $src);


////              
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
    }

    public function testconvertThaiAAInFist() {

        $src = "॥ารยะวัชรัจเฉทิกา";
        $asrt = "॥อารยะวัชรัจเฉทิกา";
        $src = ThaiSanscriptRule::convertThaiAAInFist($src);
        $this->assertEquals($asrt, $src);
    }

}
