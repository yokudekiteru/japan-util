<?php

/*
 * This file is part of JapanUtil.
 *
 * (c) Takashi OGAWA
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NinjaAnija\JapanUtil;

/**
 * 日本の都道府県を扱うためのユーティリティ.
 *
 * @author Takashi OGAWA
 *
 */
class JapanPrefectureUtil
{
    private static $prefectureMap = [
        1 => '北海道',
        2 => '青森県', 3 => '岩手県', 4 => '宮城県', 5 => '秋田県', 6 => '山形県', 7 => '福島県',
        8 => '茨城県', 9 => '栃木県', 10 => '群馬県', 11 => '埼玉県', 12 => '千葉県', 13 => '東京都', 14 => '神奈川県',
        15 => '新潟県', 16 => '富山県', 17 => '石川県', 18 => '福井県', 19 => '山梨県', 20 => '長野県', 21 => '岐阜県', 22 => '静岡県', 23 => '愛知県',
        24 => '三重県', 25 => '滋賀県', 26 => '京都府', 27 => '大阪府', 28 => '兵庫県', 29 => '奈良県', 30 => '和歌山県',
        31 => '鳥取県', 32 => '島根県', 33 => '岡山県', 34 => '広島県', 35 => '山口県',
        36 => '徳島県', 37 => '香川県', 38 => '愛媛県', 39 => '高知県',
        40 => '福岡県', 41 => '佐賀県', 42 => '長崎県', 43 => '熊本県', 44 => '大分県', 45 => '宮崎県', 46 => '鹿児島県', 47 => '沖縄県',
    ];

    private static $areaMap = [
        1 => '北海道',
        2 => '東北',
        3 => '関東',
        4 => '中部',
        5 => '近畿',
        6 => '中国',
        7 => '四国',
        8 => '九州',
    ];

    private static $prefectureAreaMap = [
        1 => 1,
        2 => 2, 3 => 2, 4 => 2, 5 => 2, 6 => 2, 7 => 2,
        8 => 3, 9 => 3, 10 => 3, 11 => 3, 12 => 3, 13 => 3, 14 => 3,
        15 => 4, 16 => 4, 17 => 4, 18 => 4, 19 => 4, 20 => 4, 21 => 4, 22 => 4,
        24 => 5, 25 => 5, 26 => 5, 27 => 5, 28 => 5, 29 => 5, 30 => 5,
        31 => 6, 32 => 6, 33 => 6, 34 => 6,
        36 => 7, 37 => 7, 38 => 7, 39 => 7,
        40 => 8, 41 => 8, 42 => 8, 43 => 8, 44 => 8, 44 => 8, 45 => 8, 46 => 8, 48 => 8,
    ];

    private static $flippedPrefectureMap = null;
    private static $flippedAreaMap = null;
    private static $flippedPrefectureAreaMap = null;

    private static function prepareFlippedPrefectureMap()
    {
        if (self::$flippedPrefectureMap === null) {
            self::$flippedPrefectureMap = array_flip(self::$prefectureMap);
        }
    }

    private static function prepareFlippedAreaMap()
    {
        if (self::$flippedAreaMap === null) {
            self::$flippedAreaMap = array_flip(self::$areaMap);
        }
    }

    public static function convertCodeToName($prefectureCode)
    {
        if (isset(self::$prefectureMap[$prefectureCode])) {
            return self::$prefectureMap[$prefectureCode];
        }
        return null;
    }

    public static function convertNameToCode($prefectureName)
    {
        self::prepareFlippedPrefectureMap();
        if (isset(self::$flippedPrefectureMap[$prefectureName])) {
            return self::$flippedPrefectureMap[$prefectureName];
        }
        return null;
    }

    public static function convertAreaCodeToName($areaCode)
    {
        if (isset(self::$areaMap[$areaCode])) {
            return self::$areaMap[$areaCode];
        }
        return null;
    }

    public static function convertAreaNameToCode($areaName)
    {
        self::prepareFlippedAreaMap();
        if (isset(self::$flippedAreaMap[$areaName])) {
            return self::$flippedAreaMap[$areaName];
        }
        return null;
    }

    /**
     * @param string $heystack
     * @param string $needle
     * @param integer $adjuster
     */
    private static function mbStrposOf($heystack, $needle, $adjuster)
    {
        $mbStrposOf = mb_strpos($heystack, $needle);
        if ($mbStrposOf !== false) {
            $mbStrposOf -= $adjuster;
        }
        if ($mbStrposOf === false || $mbStrposOf < 0) {
            $mbStrposOf = -1;
        }
        return $mbStrposOf;
    }

    public static function pickFirstAsCode($address)
    {
        $address = JapanStringUtil::standardize($address);
        $array = [
            1 => self::mbStrposOf($address, '東京都', 0),
            2 => self::mbStrposOf($address, '北海道', 0),
            3 => self::mbStrposOf($address, '府', 2),
            4 => self::mbStrposOf($address, '県', 2),
        ];
        $array[5] = $array[4] === -1 ? -1 : $array[4] - 1;
        asort($array);
        $prefectureName = '';
        foreach ($array as $key => $val) {
            if ($val < 0) {
                continue;
            }
            $substrLength = $key === 5 ? 4 : 3;
            $prefectureName = @mb_substr($address, $val, $substrLength);
            $code = self::convertNameToCode($prefectureName);
            if ($code !== null) {
                return $code;
            }
        }
        return null;
    }

    /**
     * @param string $address
     */
    public static function pickFirst($address)
    {
        $pickedCode = self::pickFirstAsCode($address);
        return self::convertCodeToName($pickedCode);
    }

    public static function pickAll($address)
    {
        $address = JapanStringUtil::standardize($address);
        $list = [];
        while ($prefectureName = self::pickFirst($address)) {
            $list[] = $prefectureName;
            $address = str_replace($prefectureName, '', $address);
        }
        return $list;
    }

    public static function pickAllAsCode($address)
    {
        $list = self::pickAll($address);
        $codeList = [];
        foreach ($list as $prefectureName) {
            $codeList[] = self::convertNameToCode($prefectureName);
        }
        return $codeList;
    }

    public static function getAreaCode($prefectureNameOrCode)
    {
        if (!is_numeric($prefectureNameOrCode)) {
            $prefectureNameOrCode = self::convertNameToCode($prefectureNameOrCode);
        }
        return $prefectureNameOrCode === null ? null : self::$prefectureAreaMap[$prefectureNameOrCode];
    }

    public static function getAreaName($prefectureNameOrCode)
    {
        $areaCode = self::getAreaCode($prefectureNameOrCode);
        return self::convertAreaCodeToName($areaCode);
    }
}
