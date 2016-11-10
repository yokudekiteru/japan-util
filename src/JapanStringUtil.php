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
 * 日本語文字列を扱うためのユーティリティ.
 *
 * @author Takashi OGAWA
 *
 */
class JapanStringUtil
{

    /**
     * 日本語文字列を下記のように標準化します
     *
     *  - 改行不可スペース（C2A0）を半角スペース変換
     *  - mb_convert_kana KVas
     *    全角ハイフンは半角ハイフンに変換（電話番号や郵便番号で利用を想定）
     *    全角ダッシュはそのままなので注意
     *    その他注意は右記リンクを参考に: http://qiita.com/hrdaya/items/470b338e7c0014fe6dc7
     *  - 複数連続した半角スペースをひとつに変換
     *  - trim
     *
     * @param  string  $str
     * @return string
     */
    public static function standardize($str)
    {
        $test = str_replace("\xc2\xa0", ' ', $str);
        return trim(preg_replace('/\s{2,}/', ' ', mb_convert_kana($test, 'KVas')));
    }
}
