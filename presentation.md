# Perlに比べてPHPが不便（主観です）ああ…だから僕は…

date: 20160521
at: PHPカンファレンス福岡 2016
speaker: uzulla

***

# 自己紹介
- うずら(uzulla)です
- 東京の方のうずらです


***

# Perlに比べて
# PHPが不便
# （主観です）

***

# ああ…だから僕は…

***

# イエーイ！

***

# yay

- https://github.com/marcioAlmada/yay
- Yay is a high level PHP preprocessor
- Not ready for real world usage yet :bomb:
- つまり、プリプロセッサ（マクロ置き換え）です

***

# プリプロセッサとは

- コンパイル時にコードを動的に変換する
- コードの中に定義を書く
- （yayはPHPコードを変換してPHPコードとして出力）

***

# say

- `say("hogehoge");`
- sayはechoより一文字少ない
- 最後に改行がはいるのなにげに便利だと思う
- 「Perl6じゃん」「p5でもuse sayすればええんや」

***

```php
<?php
macro { say ·string()·message } >> {
  echo ·message;
  echo PHP_EOL;
}

say "say!";
say "yes!";
```

***

# unless

- `unless($flag){}`
- `if(!$flag){}`と同じ
- 「!を書けばいいじゃない」「せやな」

***

```php
<?php
macro {
    unless (···expression) { ···body }
} >> {
    if (! (···expression)) { ···body }
}

$isSuccess = true;
unless($isSuccess){
    die('failed');
}
```

***

# `//=` 演算子

- `$a //= 'default';`
- phpだと`$a = $a ?? 'default';`と書ける
- （`//`は`??`と同じ）
- 結構本当に欲しい
- 7.xで来る（けど先取りということで）
=> `https://wiki.php.net/rfc/null_coalesce_equal_operator`

***

```php
<?php
macro {  T_VARIABLE·A ??= } >> {
   T_VARIABLE·A =  T_VARIABLE·A ??
}

$a ??= 123;
echo $a;
```

***

# range 演算子

- `for(1 .. 10){ print $_; }`
- 正直かなり欲しい


***

```php
<?php
macro {
    foreach ( T_LNUMBER·S .. T_LNUMBER·E as T_VARIABLE·INDEX ) {
        ···body
    }
} >> {
    foreach ( range(T_LNUMBER·S , T_LNUMBER·E) as T_VARIABLE·INDEX ){
        ···body
    }
}

foreach( 1 .. 10 as $_ ){ echo $_; }
```

***

# range 演算子 その２

- 「range()でforeachとかダメでしょ」「ウッ」


***

```php
<?php
macro {
    foreach ( T_LNUMBER·S .. T_LNUMBER·E as T_VARIABLE·INDEX ) {
        ···body
    }
} >> {
    $__itelatorGenerator = itelatorGenerator(T_LNUMBER·S, T_LNUMBER·E);
    foreach ( $__itelatorGenerator as T_VARIABLE·INDEX ){ ···body }
}

function itelatorGenerator($init, $fin){
    $num = $init;
    while(1){
        if( $fin < $num ) { return $num++; }
        yield $num++;
    }
}

foreach( 1 .. 10 as $_ ){ echo $_; }
```


***

# 正規表現マッチ

- `if( "123" =~ /9/ ){print 1;}`

***

```php
<?php
macro {
    T_VARIABLE·A =~ /T_STRING·B/
} >> {
    preg_match( '/'. ··stringify(T_STRING·B) .'/u' ,T_VARIABLE·A )     
}

$abc = 'abc';
if($abc =~ /a/){
    echo 1;
}
```


***

# 正規表現置換

- `$abc =~ s/a/b/ ;`
- `$abc = preg_replace( '/a/u', 'v' ,$abc );`

***

```php
<?php
macro {
    T_VARIABLE·A =~ s/T_STRING·B/T_STRING·C/
} >> {
    T_VARIABLE·A = 
        preg_replace( 
            '/'. ··stringify(T_STRING·B) .'/u',
            ··stringify(T_STRING·C) ,
            T_VARIABLE·A 
        )
}

$abc = 'abc';
$abc =~ s/a/v/;
echo $abc;
```



***

# 余談:JS風正規表現マッチ

- `if($abc./a/){ ... }`
- 「OOPっぽい！」「PHPはオブジェクト指向言語だぞしっかりしろ」

***

```php
<?php
macro {
    if( T_VARIABLE·A./T_STRING·B/ ){ ···body }
} >> {
    if( preg_match(
        '/'. ··stringify(T_STRING·B) .'/u' ,
        T_VARIABLE·A ) )
    { ···body }
}

$abc = 'abc';
if($abc./a/){
    echo 1;
}
```


***


# splitとjoin

- どうしてもExplode/Implodeが慣れない
- PHP7でついに消滅しましたね

***

```php
<?php
macro { split } >> { mb_split }
macro { join } >> { implode }

print_r( split(",", "a,b,c") );
print_r( implode(",", ["a","b","c"]) );
```

***

（PHP7でsplit()がなくなってもmb_splitはのこっているんだよなあ…）

***

# yadayada

- `...`
- 「後で実装するから...」という時の「...」

***


```php
<?php
macro { ... } >> {
  throw new \Exception ('Unimplemented');
}

if(1){
  ...
}
```

***

# block scope

- `{〜}`
- ブロックの中でスコープが出来る
- (ある意味一番マクロっぽい)
- めっちゃほしい！！！

***



```php
<?php
macro { { ···body } } >> {
    ( function( $arg ) {
    	extract( $arg );
        ···body
    } ) ( get_defined_vars() ) ;
}

$abc = 'abc';
{
    $abc = 'def';
    echo $abc, PHP_EOL;
}
echo $abc, PHP_EOL;
```

***

# short closure

- `( )->{ }`

***

```
<?php
macro {
    (···condition) -> { ···body }
} >> {
    function ( ···condition ) { ···body }
}

$a = ($v)->{ echo $v; };

$a('abc');
```


***

# use strict; use warnings;

- (適当です)


***

```php
<?php
macro { use strict; } >> {
    set_error_handler(function($errno, $errstr, $errfile, $errline){
        throw new \Error($errstr);
    });
}
macro { use warnings; } >> { error_reporting(-1); }

use strict;
use warnings;
echo $a;
```


***

# yayは便利！

- 「一人プロジェクトや趣味ならな！」
- ちゃんとコード解析しているので、かなり壊れづらい
- 出力されるのは、本当のPHPなので安心（？）
- 単純なDSLならつくれます

***

# 今日のあれこれは

- こちらにございます
- builtinserverで立ててyayを試すツールもついてます


***

# `__END__` 

***

# `__halt_compiler();`



