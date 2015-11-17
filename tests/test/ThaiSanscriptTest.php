<?php

include '../src/ThaiSanscript.php';
use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptTest extends PHPUnit_Framework_TestCase {
    /* @var $thaiSanscript ThaiSanscript */
//    public $thaiSanscript;

    function __construct() {
//        $this->thaiSanscript = new ThaiSanscript();
    }

    public function testsetRevertFlag() {
        $torevert = ThaiSanscript::$singleConsonant;
        $revert = array();
        $revert = ThaiSanscript::setRevertFlag($revert, $torevert);
//      print_r($torevert);
//      print_r($revert);
        $assrt = "k,g,ṅ,c,j,ñ,ṭ,ḍ,ṇ,t,d,n,p,b,m,y,r,l,v,ś,ṣ,s,h,ḥ,'";
        $src = implode(",", $revert);
        $this->assertEquals($assrt, $src);
    }

    public function testmappingIsThaiConsonant() {
        $consonant = ThaiSanscript::mappingIsThaiConsonant();
        //print_r($consonant);
        $assrt = "อ,ก,ค,ง,จ,ช,ญ,ฏ,ฑ,ณ,ต,ท,น,ป,พ,ม,ย,ร,ล,ว,ศ,ษ,ส,ห,หฺ,',ข,ฉ,ฐ,ถ,ผ,ฆ,ฌ,ฒ,ธ,ภ";
        $revert = ThaiSanscript::setRevertFlag(array(), $consonant);
        $src = implode(",", $revert);
        $this->assertEquals($assrt, $src);
    }

    public function testmappingIsThaiVowel() {
        $vowel = ThaiSanscript::mappingIsThaiVowel();
        $assrt = "ั,า,ิ,ี,ุ,ู,ฤ,เ,โ,ไ,เา";
        $revert = ThaiSanscript::setRevertFlag(array(), $vowel);
        $src = implode(",", $revert);
        $this->assertEquals($assrt, $src);
    }
}
