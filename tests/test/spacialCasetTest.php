<?php

include '../src/ThaiSanscriptAPI.php';

use ThaiSanskrit\ThaiSanscriptAPI;

class spacialCase extends PHPUnit_Framework_TestCase {

    public function __construct() {
        
    }

    public function testCase1() {
        $this->spacialCase("prātimōkṣasūtram", "ปราติโมกษะสูตรัม");
    }

    public function testCase2() {
        $this->spacialCase("uddānam", "อุททานัม");
    }

    public function testCase3() {
        $this->spacialCase("-uddānam", "-อุททานัม");
    }

    public function testCase4() {
        $this->spacialCase("uddānam-uddānam", "อุททานัม-อุททานัม");
    }

    public function testCase5() {
        $this->spacialCase("na-an", "นะ-อัน");
    }

    public function testCase6() {
        $this->spacialCase("naan", "นะอัน");
    }

    public function testCase7() {
        $this->spacialCase("bījagrāmabhūtagrāma", "พีชะครามะภูตะครามะ");
    }

    public function testCase8() {
        $this->spacialCase("namaḥ", "นะมะห์");
    }

    public function testCase9() {
        $this->spacialCase("trayaḥ", "ตระยะห์");
    }

    public function testCase10() {
        $this->spacialCase("daurvvalyamanāviṣkṛtvā", "เทารววัลยะมะนาวิษกฤตวา");
    }

    public function testCase11() {
        $this->spacialCase("maitreya", "ไมเตรยะ");
    }

    public function testCase12() {
        $this->spacialCase("vajracchedikā ", "วัชรัจเฉทิกา");
    }

    public function testCase13() {
        $this->spacialCase("sarva ", "สรรวะ");
        $this->spacialCase("dharma ", "ธรรมะ");
        $this->spacialCase("punar ", "ปุนรร");
    }

    public function testCase14() {
        $this->spacialCase("parivarjjayitvā ", "ปะริวรรชชะยิตวา");
    }

    public function testCase15() {
        $this->spacialCase("lokottaravāda ", "โลโกตตะระวาทะ");
    }

    public function testCase16() {
        $this->spacialCase("brahma ", "พรหมะ");
    }

    public function testCase17() {
        $this->spacialCase("sabrahmacāriṇaśca ", "สะพรหมะจาริณัศจะ");
    }

    public function testCase18() {
        $this->spacialCase("sarvvatra ", "สรรววะตระ");
    }
    
    public function testCase20() {
        $this->spacialCase("otīṇṇā ", "โอตีณณา");
    }
    
    

    public function spacialCase($src, $asrt) {
        $thaiSanscriptAPI = new ThaiSanscriptAPI();
        $src = $thaiSanscriptAPI->transliterationTracking($src);
        echo " ['ASRT :" . $asrt . "' :  '" . $src . "'] \n";
        $this->assertEquals($asrt, $src);
    }

}
