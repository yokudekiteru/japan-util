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

class JapanStringUtilTest extends \PHPUnit_Framework_TestCase
{

    public function testStandardize()
    {
        $this->assertSame('AB ガガガー-- らら', JapanStringUtil::standardize('　ＡB 　ガｶﾞｶﾞー--' . html_entity_decode("&nbsp;&nbsp;&nbsp;らら ")));
    }
}
