<?php
/* Created by sandro.putraa
 * 20-09-2020
 */

/*
 * CURL FUNCTION
 * $url -> Url Website
 * $method -> POST / GET
 * $postfields -> If method POST
 * $followlocation -> Followlocation 1 = on | null = off
 * $headers -> Headers
 */
function curl(
    $url,
    $method = null,
    $postfields = null,
    $followlocation = null,
    $headers = null
) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($followlocation == '1') {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }
    if ($method == "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    }
    if ($method == "POST") {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    }
    if ($headers !== null) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($ch);
    $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
    $cookies = array();
    foreach ($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    return array(
        $httpcode,
        $header,
        $body,
        $cookies,
        array(
            "url" => $url,
            "header_post" => $headers,
            "post" => $postfields
        ),
    );
}

/*
 * Get Variabel From
 * return $text
 */
function getVarFromUser($text)
{
    echo $text . ': ';
    $var = trim(fgets(STDIN));
    return $var;
}

/*
 * Save to file
 */

function save($fileName, $line)
{
    $file = fopen($fileName, 'a');
    fwrite($file, $line ."\n");
    fclose($file);
}

/*
 * Dot trick Gmail
 * if one result dot($varEmail)[rand(0,500)];
 */
function dot($str)
{
    if ((strlen($str) > 1) && (strlen($str) < 31)) {
        $ca = preg_split("//", $str);
        array_shift($ca);
        array_pop($ca);
        $head = array_shift($ca);
        $res = dot(join('', $ca));
        $result = array();
        foreach ($res as $val) {
            $result[] = $head . $val;
            $result[] = $head . '.' .$val;
        }
        return $result;
    }
    return array($str);
}

/*
 * Generate Random String
 * $a = 0 -> number
 * $a = 1 -> alphabet + number
 */
function random($length, $a)
{
    $str = "";
    if ($a == 0) {
        $characters = array_merge(range('0', '9'));
    } elseif ($a == 1) {
        $characters = array_merge(range('0', '9'), range('a', 'z'));
    }
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

/*
 * Find Text in string
 * return value text
 */
function get_between($string, $start, $end)
{
    $string = " ".$string;
    $ini = strpos($string, $start);
    if ($ini == 0) {
        return "";
    }
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;

    return substr($string, $ini, $len);
}

/*
 * Find Text in string
 * return value text
 */
function getStr($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, ($str[1]));
    return $str[0];
}

/*
 * Name Generator
 */
function namegenerator()
{
    $array = '{"region":"Indonesia",
        "male":["Adam","Adi","Adit","Ade","Agus","Agung","Andhika","Ahmad","Asep","Aji","Budi","Bambang","Bayu","Bagas","Bagus","Chaerul","Dani","Deni","Dedi","Dodi","Desi","Edi","Eko","Nugroho","Taufik","Panji","Pandu","Rendi","Ridho","Teguh","Ujang","Veri","Wawan","Yogi","Zulham"],
        "female":["Ani","Anti","Ayu","Bunga","Cinta","Dewi","Dwi","Dina","Diah","Dian","Desi","Eka","Fani","Fitri","Gladys","Intan","Irma","Jeni","Juju","Kiki","Lala","Mita","Maya","Nurul","Nenden","Pratiwi","Putri","Ria","Rahma","Ririn","Tuti","Utami","Wulan","Yayah","Yuni"],
        "surnames":["Susanto","Setiawan","Fauziah","Ginting","Widodo","Wahyuni","Zakaria","Nasution","Pangestu","Shihab","Hamzah","Falihi","Perdana","Notonegoro","Adiningrat","Gurusinga","Salim","Hidayat","Kusuma","Lestari","Muhammad","Dharmawan"]}';
    $decode = json_decode($array);
    return strtolower($decode->male[rand(0, 30)]."".$decode->surnames[rand(0, 10)]."".$decode->female[rand(0, 10)]);
}

/*
 * Random Color
 */
function randomColors()
{
    $arrX = array(
        "0;34","1;34","0;32","1;32","0;36","1;36","0;31","1;31","0;35","1;35","0;33","1;33"
    );
    $randIndex = array_rand($arrX);
    return $arrX[$randIndex];
}

/*
 * Readable random string
 */
function readable_random_string($length = 6)
{
    $string     = '';
    $vowels     = array("a","e","i","o","u");
    $consonants = array(
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
        'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
    );
    srand((double) microtime() * 1000000);
    $max = $length/2;
    for ($i = 1; $i <= $max; $i++) {
        $string .= $consonants[rand(0, 19)];
        $string .= $vowels[rand(0, 4)];
    }
    return $string;
}

/*
 * Rupiah Value
 */
function rupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}

/*
 * Generate Device ID
 */
function generate_device_id()
{
    return 'android-' . md5(rand(1000, 9999)) . rand(2, 9);
}

/*
 * Color Function
 */
function warna($text, $warna)
{
    $warna = strtoupper($warna);
    $list = array();
    $list['BLACK'] = "\033[30m";
    $list['RED'] = "\033[31m";
    $list['GREEN'] = "\033[32m";
    $list['YELLOW'] = "\033[33m";
    $list['BLUE'] = "\033[34m";
    $list['MAGENTA'] = "\033[35m";
    $list['CYAN'] = "\033[36m";
    $list['WHITE'] = "\033[37m";
    $list['RESET'] = "\033[39m";
    $warna = $list[$warna];
    $reset = $list['RESET'];
    if (in_array($warna, $list)) {
        $text = "$warna$text$reset";
    } else {
        $text = $text;
    }
    return $text;
}

/*
 * Random Name API
 */
function random_name()
{
    $name_female = curl(
        'https://namefake.com/indonesian-indonesia/female/',
        'GET',
        null,
        null,
        null
    );
    preg_match('/<h2>(.*?)<\/h2>/', $name_female[2], $result);
    $split = explode(" ", $result[1]);
    $firstName = $split[0];
    $lastName = $split[1];

    return array(
        'first' => $firstName,
        'last' => $lastName
    );
}

/*
 * UUID v4 Gen
 */

function gen_uuid()
{
    $curl = curl(
        'https://www.uuidgenerator.net/api/version4',
        'GET',
        null,
        null,
        null
    );
    return $curl[2];
}

/*
 * Send Msg Tele
 * $chatid
 * $msg
 * $bottoken
 */
function send_telegram($chatid, $msg, $bottoken)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.$bottoken.'/sendMessage?chat_id='.$chatid.'&text='.$msg.'&parse_mode=html');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $result = curl_exec($ch);
    curl_close($ch);
}

/* First Step
 * Tempm Get Email
 * Get_tempm($site = '1') 1 -> generator email | 2 -> tempm
 */

 function Get_tempm($site)
 {
     $url = '';
     if($site == '1'){
        $url = 'https://generator.email/';
     }elseif ($site == '2') {
         $url = 'https://tempm.com/';
     }
     $get_email = curl(
         $url,
         'GET',
         null,
         '1',
         null
     );
     print_r($get_email);
     preg_match('/var gasmurl="\/(.*?)\/(.*?)";/', $get_email[2], $value);
     return array(
        "email" => $value[2].'@'.$value[1],
        "name" => $value[2],
        "domain" => $value[1],
        "site" => $site
    );
 }
 
/* Second Step
 * Read Email
 * $name -> result from Get_tempm() name
 * $domain -> result from Get_tempm() domain
 */

 function Read_tempm($site , $name, $domain)
 {
    if($site == '1'){
        $url = 'https://generator.email/';
     }elseif ($site == '2') {
         $url = 'https://tempm.com/';
     }
     $headers_tempm = array();
     $headers_tempm[] = 'Host: '.str_replace(array("https://","/"), "", $url).'';
     $headers_tempm[] = 'Connection: keep-alive';
     $headers_tempm[] = 'Upgrade-Insecure-Requests: 1';
     $headers_tempm[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36';
     $headers_tempm[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
     $headers_tempm[] = 'Sec-Fetch-Site: none';
     $headers_tempm[] = 'Sec-Fetch-Mode: navigate';
     $headers_tempm[] = 'Cookie: _ga=GA1.2.727824364.1578704779; _gid=GA1.2.139870167.1578704779; embx=%5B%22'.$name.'%40'.$domain.'%22%2C%22dmoufekbelahmar7%40livecare.info%22%5D; surl='.$domain.'%2F%3F'.$name.'';
     $baca_email = curl(
         $url,
         'GET',
         null,
         '1',
         $headers_tempm
     );
     return $baca_email[2];
 }

 // Gmailnator temp mail step 1
 function gmailnator(){
     $curl = curl(
         'https://gmailnator.com/',
         'GET',
         null,
         null,
         null
     );
     if(empty($curl[3]['csrf_gmailnator_cookie'])){
         return array (
             'Response' => 'Failed Get Email'
            );
     }else{
         $curl2 = curl(
             'https://gmailnator.com/index/indexquery',
             'POST',
             'csrf_gmailnator_token='.$curl[3]['csrf_gmailnator_cookie'].'&action=GenerateEmail&data%5B%5D=2&data%5B%5D=1&data%5B%5D=3',
             null,
             [
                "accept: */*",
                "connection: keep-alive",
                "content-type: application/x-www-form-urlencoded; charset=UTF-8",
                "cookie: csrf_gmailnator_cookie=".$curl[3]['csrf_gmailnator_cookie']."; __gads=ID=afaba48156fd75d2:T=1601654935:S=ALNI_MYrHbdQVx8dCrhq2e9BbF6_Bockzg",
                "host: gmailnator.com",
                "origin: https://gmailnator.com",
                "referer: https://gmailnator.com/",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
                "x-requested-with: XMLHttpRequest"
              ]
             );
             if(!empty($curl2[2])){
                 return array(
                     'Response' => 'ok',
                     'Email' => $curl2[2],
                     'Token' => $curl[3]['csrf_gmailnator_cookie']
                 );
             }
     }
 }

 /* 
  * Read Data Gmailnator
  * var $token & $email -> get in read gmailnator
  */
 
 function Read_gmailnator($token,$email){
     $header_fix =         [
        "accept: */*",
        "connection: keep-alive",
        "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        "cookie: csrf_gmailnator_cookie=".$token."",
        "host: gmailnator.com",
        "origin: https://gmailnator.com",
        "referer: https://gmailnator.com/",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
        "x-requested-with: XMLHttpRequest"
     ];
    $curl1 = curl(
        'https://gmailnator.com/mailbox/mailboxquery',
        'POST',
        'csrf_gmailnator_token='.$token.'&action=LoadMailList&Email_address='.urlencode($email).'',
        null,
        $header_fix
        );
        if(strpos($curl1[2],'messageid')){
            $msg_id = getStr($curl1[2],'messageid\/#','\"');
            $em = getStr($curl1[2],'com\/','\/messageid');
            $read = curl(
                'https://gmailnator.com/mailbox/get_single_message/',
                'POST',
                'csrf_gmailnator_token='.$token.'&action=get_message&message_id='.$msg_id.'&email='.$em.'',
                null,
                $header_fix
            );
            return array(
                'Response' => 'ok',
                'Result' => $read[2]
            );
        }else{
            return array(
                'Response' => 'Failed',
                'Result' => $read[2]
            );
        }
 }
