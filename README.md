# ハイフン無しの電話番号をハイフン付の形式に変換する


- ハイフン付き電話番号に分割
- ハイフン無しの電話番号をハイフン付の形式に変換


## Example

```php
 $provider = (new Rebib\Phonenumber\PhonenumberSplitter())->normalize("031-234-5678");
 
 // ハイフン無し
 echo $provider->getNumberWithoutHyphen(); 
    #  031234-5678 
    
 // ハイフン付
 echo $provider->getNumberWithHyphen(); 
    #  03-1234-5678
    
 # array
 print_r($provider->toArray());
    [
        [0] => 03
        [1] => 1234
        [2] => 5678
    ]
```
