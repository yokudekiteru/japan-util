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

use NinjaAnija\JapanUtil;

class JapanTelephoneExchangeNumberUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testTokyo03()
    {
        $tel = '03-1111-1111';
        $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCode($tel);
        $this->assertSame($result, 13);
        $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCodeStrict($tel);
        $this->assertSame($result, 13);
    }
    public function testMobilePhone()
    {
        $tel = '090-1111-1111';
        $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCode($tel);
        $this->assertNull($result);
        $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCodeStrict($tel);
        $this->assertNull($result);
    }
    private function getDeclaredTelephoneExchangeNumberPrefectureCodeMap()
    {
        $class = new \ReflectionClass('NinjaAnija\\JapanUtil\\JapanTelephoneExchangeNumberUtil');
        $property = $class->getProperty('telephoneExchangeNumberPrefectureCodeMap');
        $property->setAccessible(true);
        $value = $property->getValue();
        return $value;
    }
    public function testNotStrictAll()
    {
        $value = $this->getDeclaredTelephoneExchangeNumberPrefectureCodeMap();
        foreach ($value as $telephoneExchangeNumber => $prefectureCodes) {
            // 桁数が少ない場合にどの数字でpadするかで上位桁のものとの兼ね合いにより結果が変わってしまう
            // "X"でpadすることで上記問題を解消する
            // 本処理は市外局番から都道府県を導くことを目的としているためテストとして妥当だと考える
            $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCode('0' . str_pad($telephoneExchangeNumber, 9, 'X'));
            $this->assertSame($result, $prefectureCodes[0]);
        }
    }
    public function testStrictAll()
    {
        $value = $this->getDeclaredTelephoneExchangeNumberPrefectureCodeMap();
        foreach ($value as $telephoneExchangeNumber => $prefectureCodes) {
            // 桁数が少ない場合にどの数字でpadするかで上位桁のものとの兼ね合いにより結果が変わってしまう
            // "X"でpadすることで上記問題を解消する
            // 本処理は市外局番から都道府県を導くことを目的としているためテストとして妥当だと考える
            $result = JapanUtil\JapanTelephoneExchangeNumberUtil::telephoneNumberToPrefectureCodeStrict('0' . str_pad($telephoneExchangeNumber, 9, 'X'));
            if (count($prefectureCodes) > 1) {
                $this->assertNull($result);
            } else {
                $this->assertSame($result, $prefectureCodes[0]);
            }
        }
    }
}
