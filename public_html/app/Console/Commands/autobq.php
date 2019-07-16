<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Google_Client; 
use Google_Service_YouTube;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;
use Google_Service_YouTube_VideoStatus;
class autobq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:bq';

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
      require_once __DIR__ . '/../../../public/vendor/autoload.php';
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
              $status = new Google_Service_YouTube_VideoStatus();
              $status->setPrivacyStatus('private');

              $video = new Google_Service_YouTube_Video();
              $video->setStatus($status);
              $a='iOrxxuWMyFY';
              $video->setId($a);
              $updateResponse = $youtube->videos->update("status", $video);
            } catch (Google_Service_Exception $e) {
                $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
          }// end ton tai token  
        }
    }
}
