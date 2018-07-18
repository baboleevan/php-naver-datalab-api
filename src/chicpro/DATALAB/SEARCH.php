<?php
/**
* @author chicpro <chicpro@gmail.com>
* @copyright (c) chicpro
* @link https://ncube.net
*/

namespace chicpro\DATALAB;

class SEARCH
{
    protected $clientID;
    protected $clientSecret;

    protected $endPoint;
    protected $startDate;
    protected $endDate;
    protected $timeUnit;
    protected $device;
    protected $gender;
    protected $ages;
    protected $keywordGroups = array();

    public function __construct(string $clientID = '', string $clientSecret = '')
    {
        $this->endPoint = 'https://openapi.naver.com/v1/datalab/search';
        $this->timeUnit = 'week';
        $this->device   = '';
        $this->gender   = '';
        $this->ages     = [];

        $this->setCredential($clientID, $clientSecret);
    }

    public function setCredential(string $clientID, string $clientSecret)
    {
        $this->clientID     = $clientID;
        $this->clientSecret = $clientSecret;
    }

    public function setStartDate(string $start)
    {
        $date = date('Y-m-d', strtotime($start));

        if ($date < '2016-01-01')
            throw new \Exception('2016년 1월 1일이후로 지정해 주십시오.');

        $this->startDate = $date;
    }

    public function setEndDate(string $end)
    {
        $this->endDate = date('Y-m-d', strtotime($end));
    }

    public function setTimeUnit(string $unit)
    {
        $units = ['date', 'week', 'month'];

        if (!in_array($unit, $units))
            throw new \Exception('구간 단위는 date, week, month 로 지정해 주십시오.');

        $this->timeUnit = $unit;
    }

    public function setDevice(string $device)
    {
        $devices = ['pc', 'mo'];

        if ($device && !in_array($device, $devices))
            throw new \Exception('검색 환경을 pc, mo 로 지정해 주십시오.');

        $this->device = $device;
    }

    public function setGender(string $gender)
    {
        $genders = ['m', 'f'];

        if ($gender && !in_array($gender, $genders))
            throw new \Exception('성별을 m, f 로 지정해 주십시오.');

        $this->gender = $gender;
    }

    public function setAges($ages)
    {
        /**
         * 1: 0∼12세
         * 2: 13∼18세
         * 3: 19∼24세
         * 4: 25∼29세
         * 5: 30∼34세
         * 6: 35∼39세
         * 7: 40∼44세
         * 8: 45∼49세
         * 9: 50∼54세
         * 10: 55∼59세
         * 11: 60세 이상
         */

        if (!is_array($ages))
            $ages = array_map('trim', explode(',', $ages));

        $this->ages;
    }

    public function setKeywordGroups(string $groupName, $keywords)
    {
        if (!$groupName || empty($keywords))
            throw new \Exception('주제어와 검색어 묶음을 지정해 주십시오.');

        if (!is_array($keywords))
            $keywords = array_map('trim', explode(',', $keywords));

        $keywords = array_slice($keywords, 0, 5);

        $this->keywordGroups[] = [
            'groupName' => $groupName,
            'keywords'  => $keywords
        ];
    }

    public function sendRequest()
    {
        $headers = [
            "X-Naver-Client-Id: ".$this->clientID,
            "X-Naver-Client-Secret: ".$this->clientSecret,
            "Content-Type: application/json"
        ];

        $param = [
            'startDate'     => $this->startDate,
            'endDate'       => $this->endDate,
            'timeUnit'      => $this->timeUnit,
            'keywordGroups' => $this->keywordGroups,
            'device'        => $this->device,
            'gender'        => $this->gender,
            'ages'          => $this->ages
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->endPoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

        $json = curl_exec($ch);

        if ($errno = curl_errno($ch)) {
            $error = new \stdClass;
            $error->errno = $errno;
            $error->error = 'Curl error: ' . curl_error($ch);

            $result = json_encode($error);
        } else {
            $result = $json;
        }

        return $result;
    }
}