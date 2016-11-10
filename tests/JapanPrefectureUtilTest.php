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

class JapanPrefectureUtilTest extends \PHPUnit_Framework_TestCase
{

    public function testConvertCodeToName()
    {
        $this->assertSame('埼玉県', JapanPrefectureUtil::convertCodeToName(11));
        $this->assertNull(JapanPrefectureUtil::convertCodeToName(99));
    }

    public function testConvertNameToCode()
    {
        $this->assertSame(47, JapanPrefectureUtil::convertNameToCode('沖縄県'));
        $this->assertNull(JapanPrefectureUtil::convertNameToCode('北海県'));
    }

    public function testConvertAreaCodeToName()
    {
        $this->assertSame('東北', JapanPrefectureUtil::convertAreaCodeToName(2));
        $this->assertNull(JapanPrefectureUtil::convertAreaCodeToName(99));
    }

    public function testConvertAreaNameToCode()
    {
        $this->assertSame(7, JapanPrefectureUtil::convertAreaNameToCode('四国'));
        $this->assertNull(JapanPrefectureUtil::convertAreaNameToCode('九国'));
    }

    public function testPickFirst()
    {
        $this->assertSame('東京都', JapanPrefectureUtil::pickFirst('aaa東京都aaa'));
        $this->assertSame('鹿児島県', JapanPrefectureUtil::pickFirst('鹿児島県東京都'));
        $this->assertSame('大阪府', JapanPrefectureUtil::pickFirst('北海大阪府'));
        $this->assertNull(JapanPrefectureUtil::pickFirst('ふくすま県'));
    }

    public function testPickFirstAsCode()
    {
        $this->assertSame(13, JapanPrefectureUtil::pickFirstAsCode('aaa東京都aaa'));
        $this->assertSame(46, JapanPrefectureUtil::pickFirstAsCode('鹿児島県東京都'));
        $this->assertSame(27, JapanPrefectureUtil::pickFirstAsCode('北海大阪府'));
        $this->assertNull(JapanPrefectureUtil::pickFirstAsCode('ふくい県'));
    }

    public function testPickAll()
    {
        $this->assertSame(['秋田県', '新潟県', '滋賀県'], JapanPrefectureUtil::pickAll('秋田県あきた新潟県にんにん滋賀県がが県'));
        $this->assertSame([], JapanPrefectureUtil::pickAll('テクマクマヤコン'));
    }

    public function testPickAllAsCode()
    {
        $this->assertSame([5, 15, 25], JapanPrefectureUtil::pickAllAsCode('秋田県あきた新潟県にんにん滋賀県がが県'));
        $this->assertSame([], JapanPrefectureUtil::pickAll('テクマクマヤコン'));
    }

    public function testGetAreaCode()
    {
        $this->assertSame(1, JapanPrefectureUtil::getAreaCode('北海道'));
        $this->assertSame(1, JapanPrefectureUtil::getAreaCode(1));
        $this->assertNull(JapanPrefectureUtil::getAreaCode('琉球'));
    }

    public function testGetAreaName()
    {
        $this->assertSame('中部', JapanPrefectureUtil::getAreaName('岐阜県'));
        $this->assertSame('東北', JapanPrefectureUtil::getAreaName(3));
        $this->assertNull(JapanPrefectureUtil::getAreaName('琉球'));
    }
}
