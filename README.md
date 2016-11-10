[![CircleCI](https://circleci.com/gh/NinjaAnija/japan-util.svg?style=shield&circle-token=5937a1c02f22d7050e14eb952dffa7ae62f7e6aa)](https://circleci.com/gh/NinjaAnija/japan-util)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/NinjaAnija/japan-util/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/NinjaAnija/japan-util/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/NinjaAnija/japan-util/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/NinjaAnija/japan-util/?branch=master)

# JapanUtil

## 都道府県コードデータソース
http://www.soumu.go.jp/denshijiti/code.html

コード値をライブラリ中では int として扱います

## 市外局番から都道府県を導くためのデータソース
http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/shigai_list.html

平成28年5月27日現在のwordファイルをOffice For MacのWordで開き
全選択でコピーして貼り付けしたものが build/source_20160527.txt
データソースに更新があったら下記コマンドを実行し
src/JapanTelephoneExchangeNumberUtil.phpをアップデートします
```shell
    php build/japan_telephone_exchange_number_util_generator.php build/source_20160527.txt
```
貼り付けたものそのままだと制御コードが含まれたりで利用に不都合があります
不都合がある場合は japan_telephone_exchange_number_util_generator.php がエラー終了しますので
不都合部を手動で書き換えて再実行します
