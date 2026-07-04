# Phone number splitter

日本の電話番号を、ハイフン区切りの表記へ整形するためのライブラリです。

- 電話番号を「市外局番-市内局番-識別番号」に分割
- ハイフン無しの電話番号をハイフン付の形式に変換
- ハイフン付の電話番号をハイフン無しの形式に変換
- 緊急通報番号や携帯電話番号も含めて整形

## Installation

You can install this plugin with Composer.
Requires PHP 8.4.1+.

```sh
composer require rebib/phonenumbersplitter
```

## Usage

### 基本的な使い方

```php
use Rebib\Phonenumber\Splitter;

$provider = (new Splitter())->parse('0312345678');
```

### ハイフン付きで取得する

```php
echo $provider->getNumberWithHyphen();
```

```text
03-1234-5678
```

### ハイフン無しで取得する

```php
echo $provider->getNumberWithoutHyphen();
```

```text
0312345678
```

### 配列で取得する

```php
print_r($provider->toArray());
```

```text
Array
(
    [0] => 03
    [1] => 1234
    [2] => 5678
)
```

## Usage examples

```php
use Rebib\Phonenumber\Splitter;

$splitter = new Splitter();

echo $splitter->parse('110')->getNumberWithHyphen();
// 110

echo $splitter->parse('157')->getNumberWithHyphen();
// 157

echo $splitter->parse('05078273831')->getNumberWithHyphen();
// 050-7827-3831

echo $splitter->parse('08012345678')->getNumberWithHyphen();
// 080-1234-5678

echo $splitter->parse('031-234-5678')->getNumberWithHyphen();
// 03-1234-5678
```

Inspired by kennyj's article on splitting Japanese phone numbers.

## Reference

- 総務省 [電気通信番号指定状況](https://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html)
