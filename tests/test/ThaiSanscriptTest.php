<?php

include '../src/ThaiSanscript.php';

use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptTest extends PHPUnit_Framework_TestCase {

    public $thaiSanscript;

    public function testgetAnusvara() {
        $src = "อ,ก,ค,ง,จ,ช,ญ,ฏ,ฑ,ณ,ต,ท,น,ป,พ,ม,ย,ร,ล,ว,ฬ,ศ,ษ,ส,ห,ห์,',ข,ฉ,ฐ,ถ,ผ,ฆ,ฌ,ฒ,ธ,ภ";

        $src = explode(",", $src);
        $revert = "";
        $assrt = "มงงงญญญณณณนนนมมมมมมมมมมมมมมงญณนมงญณนม";
        foreach ($src as $value) {
            $revert .= ThaiSanscript::getAnusvara($value);
        }
        $this->assertEquals($assrt, $revert);
    }

    public function testsetRevertFlag() {
        $torevert = ThaiSanscript::$singleConsonant;
        $revert = array();
        $revert = ThaiSanscript::setRevertFlag($revert, $torevert);
        $assrt = "k,g,ṅ,c,j,ñ,ṭ,ḍ,ṇ,t,d,n,p,b,m,y,r,l,v,ḻ,ś,ṣ,s,h,ḥ,'";
        $src = implode(",", $revert);
        $this->assertEquals($assrt, $src);
    }

    public function testmappingIsThaiConsonant() {
        $consonant = ThaiSanscript::mappingIsThaiConsonant();
        //print_r($consonant);
        $assrt = "อ,ก,ค,ง,จ,ช,ญ,ฏ,ฑ,ณ,ต,ท,น,ป,พ,ม,ย,ร,ล,ว,ฬ,ศ,ษ,ส,ห,ँ,ํ,ห์,',ข,ฉ,ฐ,ถ,ผ,ฆ,ฌ,ฒ,ธ,ภ";
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
