<?php

namespace ThaiSanskrit;

/**
 * Sanscript
 *
 * Sanscript is a Sanskrit transliteration library. Currently, it supports
 * other Indian languages only incidentally.
 *
 * Released under the MIT and GPL Licenses.
 * 
 *
 * @author Thanakrit.P
 *
 * k = ก (กะ)	kh = ข (ขะ)	g = ค (คะ)	gh = ฆ (ฆะ)	ṅ = ง (งะ) c = จ (จะ)	ch = ฉ
 * (ฉะ)	j = ช (ชะ)	jh = ฌ (ฌะ)	ñ = ญ (ญะ) ṭ = ฏ (ฏะ)	ṭh = ฐ (ฐะ)	ḍ = ฑ (ฑะ)	ḍh=
 * ฒ (ฒะ)	ṇ = ณ (ณะ) t = ต (ตะ)	th = ถ (ถะ)	d = ท (ทะ)	dh = ธ (ธะ)	n = น (นะ) p
 * p= ป (ปะ)	ph = ผ (ผะ)	b = พ (พะ)	bh = ภ (ภะ)	m = ม (มะ) y = ย (ยะ)	r = ร (ระ)
 * l = ล (ละ)	v = ว (วะ)	ś = ศ (ศะ) ṣ = ษ (ษะ)	s = ส (สะ)	h = ห (หะ)
 *
 * a = –ะ, –ั	ā = –า	i = –ิ	ī = –ี	u = –ุ ū = –ู	ṛ
 * (เมื่ออยู่ต้นคำหรือตามหลังพยัญชนะ) = ฤ e = เ–	ai = ไ–	o = โ–	o (ตามหลังสระ) =
 * ว	au = เ–า ṁ (ใช้แทนจันทรพินทุ) = ง	ṃ (ใช้แทนอนุสวาร) = ง	ḥ = ห์
 *
 */
class ThaiSanscript {

// Transliteration process option defaults.

    public static $thaiVowelInFist = array(
        "ะ" => "อะ",
        "า" => "อา",
        "ิ" => "อิ",
        "ี" => "อี",
        "ุ" => "อุ",
        "ู" => "อู",
        "เ" => "เอ",
        "โ" => "โอ"
    );
    public static $singleVowel = array(
        "a" => "ะ",
        "ā" => "า",
        "i" => "ิ",
        "ī" => "ี",
        "u" => "ุ",
        "ū" => "ู",
        "ṛ" => "ฤ",
        "e" => "เ",
        "ē" => "เ",
        "ō" => "โ",
        "o" => "โ"
    );
    public static $mixVowel = array(
        "ai" => "ไ",
        "au" => "เา",
        "r̥" => "ฤ"
    );
    public static $singleConsonant = array(
        "k" => "ก",
        "g" => "ค",
        "ṅ" => "ง",
        "c" => "จ",
        "j" => "ช",
        "ñ" => "ญ",
        "ṭ" => "ฏ",
        "ḍ" => "ฑ",
        "ṇ" => "ณ",
        "t" => "ต",
        "d" => "ท",
        "n" => "น",
        "p" => "ป",
        "b" => "พ",
        "m" => "ม",
        "y" => "ย",
        "r" => "ร",
        "l" => "ล",
        "v" => "ว",
        "ḻ" => "ฬ",
        "ḷ" => "ฬ",
        "ś" => "ศ",
        "ṣ" => "ษ",
        "s" => "ส",
        "h" => "ห",
        "ṁ" => "ม",
        "ṃ" => "ม",
        "ḥ" => "ห์",
        "'" => "'"
    );
    public static $mixConsonant = array(
        "kh" => "ข",
        "ch" => "ฉ",
        "ṭh" => "ฐ",
        "th" => "ถ",
        "ph" => "ผ",
        "n̄" => "ญ",
        "gh" => "ฆ",
        "jh" => "ฌ",
        "ḍh" => "ฒ",
        "dh" => "ธ",
        "bh" => "ภ"
    );

    public static function mappingIsThaiVowel() {

        $revert = array();
        $revert["ั"] = 'a';
        $singleVowel = ThaiSanscript::$singleVowel;
        $revert = ThaiSanscript::setRevertFlag($revert, $singleVowel);
        $mixVowel = ThaiSanscript::$mixVowel;
        $revert = ThaiSanscript::setRevertFlag($revert, $mixVowel);
        return $revert;
    }

    public static function mappingIsThaiConsonant() {

        $revert = array();
        $revert["อ"] = 'a';
        $single = ThaiSanscript::$singleConsonant;
        $revert = ThaiSanscript::setRevertFlag($revert, $single);
        $mix = ThaiSanscript::$mixConsonant;
        $revert = ThaiSanscript::setRevertFlag($revert, $mix);
        return $revert;
    }

    /* @var $revert array()
     * @var $revertMap array()
     */

    public static function setRevertFlag($revert, $torevert) {

        foreach ($torevert as $key => $value) {
            $keyNew = $value;
            if (!isset($revert[$keyNew])) {
                $revert[$keyNew] = $key;
            }
        }
        return $revert;
    }

}
