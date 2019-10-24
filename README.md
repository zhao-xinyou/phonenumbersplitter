# ハイフンなしの電話番号をハイフン付の形式に変換する


- 電話番号を「市外局番-市内局番-識別番号」に分割
- ハイフン無しの電話番号をハイフン付の形式(「市外局番-市内局番-識別番号」)に復元
- ハイフン付の形式(「市外局番-市内局番-識別番号」)をハイフン無しの電話番号に変換

## Installation
You can install this plugin with Composer.

```sh
$ composer require rebib/phonenumbersplitter
```

## 例

```php
 $provider = (new Rebib\Phonenumber\PhonenumberSplitter())->normalize("031-234-5678");
 ```

### ハイフン無し
 ```php
 echo $provider->getNumberWithoutHyphen(); 
```

```
0312345678 
```    

###  ハイフン付
```php
 echo $provider->getNumberWithHyphen(); 
```

```
03-1234-5678
```    

###  array
```php
 print_r($provider->toArray());
```
 
```
Array
(
    [0] => 03
    [1] => 1234
    [2] => 5678
)
```

## 参照

- 総務省 [電気通信番号指定状況](http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html)
- kennyj [ハイフン付き電話番号に分割](https://gist.github.com/kennyj/4966002)
