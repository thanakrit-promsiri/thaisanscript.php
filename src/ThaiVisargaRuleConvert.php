<?php

namespace ThaiSanskrit;

use ThaiSanskrit\Util;

class ThaiVisargaConvert {

    public function convert($thaiChar) {
        $thaiChar = " " . $thaiChar . "      "; // before space 1 after space 6  reserve  for condition
        $charList = Util::charList($thaiChar);

        for ($i = 0; $i < count($charList); $i++) {
            if ($charList[$i] === "ะ") {

                $charList = $this->a_before_rule($charList, $i);
                $charList = $this->ah_rule($charList, $i);
                $charList = $this->ar_rule($charList, $i);
                $charList = $this->thaivisarga_rule($charList, $i);
            }
        }
        return str_replace(" ", "", Util::convertListTostring($charList));
    }

    protected function a_before_rule($charList, $i) {
        // "ะนกะ" -> "อันกะ"; กรณี สระ ะ นำหน้าให้ใช้ อั
        $_before = $charList[$i - 1];
        $_current = $charList[$i];
        if ($_before == " " && $_current == "ะ") {

            $charList[$i] = "อั";
        }
        return $charList;
    }

    protected function ah_rule($charList, $i) {
        // brahma ยกเว้น namaḥ ตัด พรัหมะ เป็น พรหมะ เว้นไว้แต่ นะมะห์
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                Util::isThaiConsonant($_after1) &&
                $_after1 == "ห" &&
                !Util::isThaiVowel($_after2) &&
                $_after2 != ("์");

        if ($condition) {
            $charList[$i] = "";
        }
        return $charList;
    }

    protected function ar_rule($charList, $i) {
        // lokottaravāda sarva เปลี่ยน สะรวา เป็น ร หัน สรรวะ
        $_before = $charList[$i - 1];
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                Util::isThaiConsonant($_before) &&
                $_after1 == "ร" &&
                !Util::isThaiVowel($_after2);

        if ($condition) {
            $charList[$i] = "ร";
        }
        return $charList;
    }

    protected function thaivisarga_rule($charList, $i) {

        if (($charList[$i + 1] != "ว")) { //เว้น ว ไว้ทุก กรณี เช่น ตะว เพื่อป้องกันการ ออกเสียงสระ อัว เช่น ตัว
            $charList = $this->thaivisarga_non_consonantclusters($charList, $i);
            $charList = $this->thaivisarga_consonantclusters($charList, $i);
            $charList = $this->thaivisarga_lastword($charList, $i);
            $charList = $this->thaivisarga_non_sunskritvisarga($charList, $i);
        }
        return $charList;
    }

    protected function thaivisarga_non_consonantclusters($charList, $i) {
        //'ะกธ' !ร  เว้น แต่คำควบกล้ำ เช่น   พีชะครามะ CVCRVCV จะไม่เป็น พีชัครามะ
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = ($_current === "ะ" &&
                Util::isThaiConsonant($_after1) &&
                Util::isThaiConsonant($_after2)) &&
                $_after2 != "ร";

        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_consonantclusters($charList, $i) {
//          
//        vajracchedikā วัชรัจเฉทิกา
//        CACRVCCVC------ visarga
//        bījagrāma พีชะครามะ
//        --CACRVCV non visarga
//        sabrahmacāriṇaśca สะพรหมะ
//        CACRVHCV--------- non visarga
//        āryaprajñāpāramitāyai อารยะปรัชญาปาระมิตา(ประช)
//        --CAPRAJ non visarga
//           C A C R V C[C] visarga
//           C A C R V C[V] non visarga
//           C A C R V[H]C non visarga
//           C A[P R A J] non visarga
//           0 1 2 3 4 5 6

        $_before = $charList[$i - 1];
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];
        $_after3 = $charList[$i + 3];
        $_after4 = $charList[$i + 4];
        $_after5 = $charList[$i + 5];

        $condition = Util::isThaiConsonant($_before) && //C
                $_current == "ะ" && //A
                Util::isThaiConsonant($_after1) && //C
                $_after2 == "ร" && //R       
                Util::isThaiVowel($_after3) && //V  
                Util::isThaiConsonant($_after4) && //C
                $_after4 != "ห" && //R
                Util::isThaiConsonant($_after5) && //C
                !($_after1 == "ป" && $_after2 == "ร" && $_after3 == "ะ" && $_after4 == "ช" ); //[P R A J]
        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_lastword($charList, $i) {
        // ร'ะก  ' -> 'รัก  ' ตัวที่สุดท้าย

        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                Util::isThaiConsonant($_after1) &&
                $_after1 != " " &&
                $_after2 == " ";

        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_non_sunskritvisarga($charList, $i) {
        // เว้นไว้ วิสรรค์ของสันสกฤต namaḥ  'นะมะห์'  trayaḥ  'ตระยะห์' ไม่ให้เป็น นะมัห
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                Util::isThaiConsonant($_after1) &&
                !Util::isThaiCharacter($_after2) &&
                $_after2 != ("์");
        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

}
