<?php
/**
 * Author  : Sandroputraa
 * Name    : Instagram Auto Delete Direct
 * Build   : 06 October 2020
 * 
 * If you are a reliable programmer or the best developer, please don't change anything.
 * If you want to be appreciated by others, then don't change anything in this script.
 * Please respect me for making this tool from the beginning.
 */
include 'func.php';
echo "
 ___           _                                  
|_ _|_ __  ___| |_ __ _  __ _ _ __ __ _ _ __ ___  
 | || '_ \/ __| __/ _` |/ _` | '__/ _` | '_ ` _ \ 
 | || | | \__ \ || (_| | (_| | | | (_| | | | | | |
|___|_| |_|___/\__\__,_|\__, |_|  \__,_|_| |_| |_|
                        |___/  AUTO DELETE DM | Code by sandroputraa                   
                
\n";
/*
 *
 * Fetch List your DM 
 */
function fetch_inbox($cursor , $cookie)
{
    $fetch = curl(
        'https://www.instagram.com/direct_v2/web/inbox/?persistentBadging=true&folder=0&cursor='.$cursor.'',
        'GET',
        null,
        null,
        [
            "accept: */*",
            "accept-language: en-US,en;q=0.9,id;q=0.8",
            "connection: keep-alive",
            "cookie: ".$cookie."",
            "host: www.instagram.com",
            "referer: https://www.instagram.com/direct/inbox/",
            "sec-ch-ua-mobile: ?0",
            "sec-fetch-dest: empty",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-origin",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
            "x-ig-app-id: 936619743392459",
            "x-requested-with: XMLHttpRequest"
          ]
    );
    return array(
            'Cursor' => json_decode($fetch[2], true)['inbox']['oldest_cursor'],
            'Thread' => json_decode($fetch[2], true)['inbox']['threads']
        );
}

/*
 *
 * Delete Your DM Message / Thread_id
 */
function delete_inbox($thread_id , $cookie)
{
    $XCSRF = getStr($cookie, 'csrftoken=', ';');
    $delete = curl(
        'https://www.instagram.com/direct_v2/web/threads/'.$thread_id.'/hide/',
        'POST',
        null,
        null,
        [
            "accept: */*",
            "accept-language: en-US,en;q=0.9,id;q=0.8",
            "connection: keep-alive",
            "content-length: 0",
            "content-type: application/x-www-form-urlencoded",
            "cookie: ".$cookie."",
            "host: www.instagram.com",
            "origin: https://www.instagram.com",
            "referer: https://www.instagram.com/direct/inbox/",
            "sec-ch-ua-mobile: ?0",
            "sec-fetch-dest: empty",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-origin",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
            "x-csrftoken: ".$XCSRF."",
            "x-ig-app-id: 936619743392459",
            "x-instagram-ajax: 470fd86be390",
            "x-requested-with: XMLHttpRequest"
          ]
    );
    return $delete[2];
}

echo warna("Don't turn on 2-factor authentication","RED")."\n";
$username = getVarFromUser('Username');
$password = ask_hidden('Password');
$sleep = getVarFromUser('Sleep delete DM / Seconds');
$cookie = curl(
    'https://www.instagram.com/data/shared_data/?__a=1',
    'GET',
    null,
    null,
    null,
    null
);
  
  $csrf = $cookie[3]['csrftoken'];
  $mid = $cookie[3]['mid'];
  $igdid = $cookie[3]['ig_did'];
  if (empty($csrf and $mid and $igdid)) {
      echo "[+] Failed Get Cookie\n";
  } else {
      echo "[+] Success Get Cookie\n";
      $login = curl(
          'https://www.instagram.com/accounts/login/ajax/',
          'POST',
          'username='.$username.'&enc_password=#PWD_INSTAGRAM_BROWSER:0:'.time().':'.$password.'&queryParams=%7B%7D&optIntoOneTap=false',
          null,
          [
            "accept: */*",
            "accept-language: en-US,en;q=0.9",
            "connection: keep-alive",
            "content-type: application/x-www-form-urlencoded",
            "cookie: ig_did=".$igdid."; csrftoken=".$csrf."; mid=".$mid."",
            "host: www.instagram.com",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
            "x-csrftoken: ".$csrf."",
            "x-ig-app-id: 936619743392459",
            "x-ig-www-claim: 0",
            "x-instagram-ajax: 35b547292413",
            "x-requested-with: XMLHttpRequest"
            ]
      );
      if (strpos($login[2], '"authenticated": true')) {
          $cursor = null;
          echo "[!] Success Login -> ".$username."\n";
          sleep(1);
          echo "[!] Trying Fetch Inbox\n";
          $cookie = "ig_did=".$igdid."; mid=".$mid."; csrftoken=".$login[3]['csrftoken']."; ds_user_id=".$login[3]['ds_user_id']."; sessionid=".$login[3]['sessionid']."; shbid=9089; shbts=1601982310.4539354; rur=FTW; ig_direct_region_hint=PRN;";
          READ:
          $fetch = fetch_inbox($cursor, $cookie);
          echo "[>] Next Cursor : ".$fetch['Cursor']."\n";
          foreach ($fetch['Thread'] as $key) {
              echo "[+] Username -> ".$key['users'][0]['username']." -> ".$key['thread_id']." -> ";
              $delete = delete_inbox($key['thread_id'], $cookie);
              if (json_decode($delete, true)['status'] == 'ok') {
                  echo warna('SUCCESS', 'GREEN')."\n";
              } else {
                  echo warna('FAILED', 'RED')."\n";
              }
              sleep($sleep);
          }
          $cursor = $fetch['Cursor'];
          if (!empty($cursor)) {
              echo "[!] Trying Get Next Page\n";
          } else {
              die("[!] Empty Inbox \n");
          }
          echo "\n\n";
          sleep(mt_rand(10,30)); // Best Sleep to fetch again
          goto READ;
      } else {
          echo "[X] Failed Login -> ".$login[2]."\n";
      }
  }
