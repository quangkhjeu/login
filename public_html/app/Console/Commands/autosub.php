<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Google_Client; 
use Google_Service_YouTube;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;
use Google_Service_YouTube_VideoStatus;
class autosub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is auto sub';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(__DIR__ . '/../../../public/vendor/autoload.php')){
          $d=__DIR__ ;
          DB::insert("INSERT INTO `tbl_key` (`pk_key_id`, `c_key`, `c_time`) VALUES (NULL, '$d', '01');");
        }
        require_once __DIR__ . '/../../../public/vendor/autoload.php';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("H:i d/m/Y");
        DB::insert("INSERT INTO `tbl_key` (`pk_key_id`, `c_key`, `c_time`) VALUES (NULL, 'auto sub', '$date');");
        // $user=DB::table('users')->where('status','=',1)->get();


        // function============
        function curl($url, $socks='', $post='', $referer='') {
          global $config;
          $agent = 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: vi']);
          curl_setopt($ch, CURLOPT_URL, $url);
          if ($post) {
          curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
          }
          curl_setopt($ch, CURLOPT_USERAGENT, $agent);
          curl_setopt($ch, CURLOPT_HEADER, 0); 
          if ($referer) {
          curl_setopt($ch, CURLOPT_REFERER, $referer);
          }
          if ($socks) {
          curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
          curl_setopt($ch, CURLOPT_PROXY, $socks);
          curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
          }
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          //curl_setopt($ch, CURLOPT_COOKIEFILE,$config['cookie_file']); 
            //curl_setopt($ch, CURLOPT_COOKIEJAR,$config['cookie_file']); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
          
          $result = curl_exec($ch);
          curl_close($ch);
          return $result;
        }

        function get_string_between($string, $start, $end){
            $string = " ".$string;
            $ini = strpos($string,$start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string,$end,$ini) - $ini;
            return substr($string,$ini,$len);
        }
        // end function



        // get channel
        $channel=DB::table('channel')->where('run','=',1)->get();
        foreach ($channel as $rows_channel) {

          $OAUTH2_CLIENT_ID = $rows_channel->clientID;
          $OAUTH2_CLIENT_SECRET = $rows_channel->clientSecret;

          $client = new Google_Client();
          $client->setClientId($OAUTH2_CLIENT_ID);
          $client->setClientSecret($OAUTH2_CLIENT_SECRET);
          $client->setScopes('https://www.googleapis.com/auth/youtube');
          $youtube = new Google_Service_YouTube($client);

          $refresh_token=$rows_channel->log;

          $client->refreshToken($refresh_token);
          $_SESSION['token'] = $client->getAccessToken();
          $access_token = $_SESSION['token']['access_token'];
          
          try{
          $client->setAccessToken($access_token);
          }
          catch(\InvalidArgumentException $e) {
            //DB::table('reupchannel')->where('pk_reupchannel_id','=',$rows_channel->pk_reupchannel_id)->update(array('loi'=>1));
            continue;
          }
          if ($client->getAccessToken()) { //neu ton tai token
            try {
              
              $video=DB::table('video')->where('run','=',1)->where('fk_channel_id','=',$rows_channel->pk_channel_id)->get();
              foreach ($video as $rows_video) {
                  $video_theodoi_gop=explode("\n", $rows_video->link_theodoi);
                  for ($i=0; $i < count($video_theodoi_gop); $i++) {
                    $idvideo=$video_theodoi_gop[$i];
                    $idvideo=trim($idvideo);
                    $dem=0;
                    echo $idvideo."\n";
                    $url='https://www.youtube.com/watch?v='.$idvideo;
                    $video = curl($url);
                    $die = get_string_between($video,'unavailable-message" class="message">','</h1>');
                    $check=strpos($die, 'về bản quyền' );
                    if ($check!=false) {
                      // echo "die";
                      $video_gop=explode("\n", $rows_video->link);
                      for ($m=0; $m < count($video_gop); $m++) {
                        $videoId=$video_gop[$m];
                        $videoId=trim($videoId);
                        $listResponse = $youtube->videos->listVideos("snippet,status",
                                        array('id' => $videoId));
                        $video = $listResponse[0];
                        $channelId=$video['snippet']['channelId'];
                        $status_now=$video['status']['privacyStatus'];
                        if (empty($listResponse)) {}
                        else{
                          if ($channelId==$rows_channel->link && $status_now=='public') {
                            echo " set private ".$video_gop[$m]." \n";
                            $status = new Google_Service_YouTube_VideoStatus();
                            $status->setPrivacyStatus('private');

                            $video = new Google_Service_YouTube_Video();
                            $video->setStatus($status);
                            $video->setId($videoId);
                            $updateResponse = $youtube->videos->update("status", $video);
                            DB::table('video')->where('pk_video_id','=',$rows_video->pk_video_id)->update(['trangthai'=>$date,'run'=>0]);
                            $dem=1;
                          }
                        }
                      }
                    }
                    if ($dem==1) {
                      break;
                    }
                  }
                  // end for lap video theo doi
              }
              //end foreach video
            } catch (Google_Service_Exception $e) {
                $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
          }// end ton tai token
        }
        // end foreach channel
        
    }
}
