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

mb_internal_encoding('UTF-8');

require_once __DIR__ . '/../src/' . 'JapanStringUtil.php';
require_once __DIR__ . '/../src/' . 'JapanPrefectureUtil.php';

$sourceText = file_get_contents($argv[1]);
$lines = explode("\n", $sourceText);

$skip = isset($argv[2]) ? $argv[2] : 7;
$switch = 0;

$map = [];
$prefectureCodes = [];
$telephoneExchangeNumber = null;
foreach ($lines as $rowNum => $content) {
    if (empty($content)) {
        continue;
    }
    if ($skip > 0) {
        $skip--;
        continue;
    }
    if ($switch === 0) {
        $switch++;
        continue;
    }
    $content = JapanStringUtil::standardize($content);
    if ($switch === 1) {
        $prefectureCodes = JapanPrefectureUtil::pickAllAsCode($content);
        if (empty($prefectureCodes)) {
            print_r("ERROR 都道府県が判別不能な行があります：" . $content . "\n");
            die(99);
        }
        if (count($prefectureCodes) > 1) {
            print_r("NOTICE 複数の都道府県が関連しています：" . $content . "\n");
        }
        $switch++;
        continue;
    }
    if ($switch === 2) {
        if (!ctype_digit($content)) {
            continue;
        }
        $telephoneExchangeNumber = $content;
        $switch++;
        continue;
    }
    if (!isset($map[$telephoneExchangeNumber])) {
        $map[$telephoneExchangeNumber] = $prefectureCodes;
    } else {
        $map[$telephoneExchangeNumber] = array_unique(array_merge($map[$telephoneExchangeNumber], $prefectureCodes));
    }
    $switch = 0;
    $telephoneExchangeNumber = null;
    $prefectureCodes = [];
}
krsort($map);
$convertMap = <<<FNC
    private static \$_telephoneExchangeNumberPrefectureCodeMap = [
FNC;

$convertFunction = <<<FNC
    public static function telephoneNumberToPrefectureCode(\$tel) {
        \$tel = JapanStringUtil::standardize(\$tel);
        \$tel = implode(explode('-', \$tel));

FNC;


$convertFunctionStrict = <<<FNC
    public static function telephoneNumberToPrefectureCodeStrict(\$tel) {
        \$tel = JapanStringUtil::standardize(\$tel);
        \$tel = implode(explode('-', \$tel));

FNC;

$preExchangeNumberLength = 100;
foreach ($map as $exchangeNumber => $prefectureCodes) {
    $exchangeNumberLength = strlen($exchangeNumber);
    if ($preExchangeNumberLength !== $exchangeNumberLength) {
        $convertFunction = <<<FNC
${convertFunction}
        \$needle = @substr(\$tel, 1, $exchangeNumberLength);

        if (isset(self::\$_telephoneExchangeNumberPrefectureCodeMap[\$needle])) {
            return self::\$_telephoneExchangeNumberPrefectureCodeMap[\$needle][0];
        }

FNC;

        $convertFunctionStrict = <<<FNC
${convertFunctionStrict}
        \$needle = @substr(\$tel, 1, $exchangeNumberLength);

        if (isset(self::\$_telephoneExchangeNumberPrefectureCodeMap[\$needle])) {
            \$result = self::\$_telephoneExchangeNumberPrefectureCodeMap[\$needle];
            return count(\$result) > 1 ? null : \$result[0];
        }

FNC;
        $preExchangeNumberLength = $exchangeNumberLength;
    }
    $prefectureCodesArrayString = '[' . implode(', ', $prefectureCodes) . ']';
    $convertMap = <<<FNC
${convertMap}
        ${exchangeNumber} => ${prefectureCodesArrayString},
FNC;

}

$test = <<<FNC
<?php

/*
 * This file is part of JapanUtil.
 *
 * (c) Takashi OGAWA
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NinjaAnija\\JapanUtil;

/**
 * 日本語文字列を扱うためのユーティリティ.
 *
 * @author Takashi OGAWA
 *
 */
class JapanTelephoneExchangeNumberUtil {
${convertMap}
    ];

${convertFunction}
        return null;
    }

${convertFunctionStrict}
        return null;
    }
}
FNC;


file_put_contents(__DIR__ . '/../src/JapanTelephoneExchangeNumberUtil.php', $test);

