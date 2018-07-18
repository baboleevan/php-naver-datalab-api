# PHP Client for Naver Datalab API #

네이버 데이터랩 API 사용을 위한 PHP Client

MIT licensed.

#### 네이버 데이터랩 통합검색어 트렌드 API ####

- [통합검색어 트렌드](https://developers.naver.com/docs/datalab/search/)

## 설치 ##

PHP Composer 를 통해 패키지를 설치합니다.

`$ composer require chicpro/php-naver-datalab-api`

## 예제 ##

```
require __DIR__.'/vendor/autoload.php';

use chicpro\DATALAB\SEARCH;

$search = new SEARCH();

$search->setCredential('client id', 'client secret');
$search->setStartDate('2018-07-01');
$search->setEndDate('2018-07-15');
$search->setKeywordGroups('네이버', '네이버, naver');
$search->setKeywordGroups('구글', '구글, google');

$result = $search->sendRequest();

print_r($result);
```
