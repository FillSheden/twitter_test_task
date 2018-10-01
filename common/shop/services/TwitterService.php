<?php
/**
 * Created by PhpStorm.
 * User: sheden
 * Date: 01.10.18
 * Time: 19:40
 */

namespace common\shop\services;

use TwitterAPIExchange;
use Yii;

class TwitterService
{
    private $settings;

    public function __construct()
    {
        $this->settings = [
            'consumer_key' => Yii::$app->params['twitter_consumer'],
            'consumer_secret' => Yii::$app->params['twitter_consumer_secret'],
            'oauth_access_token' => Yii::$app->params['twitter_token'],
            'oauth_access_token_secret' => Yii::$app->params['twitter_token_secret'],
        ];
    }

    /**
     * @param null $screen_name
     * @return mixed
     * @throws \Exception
     */
    public function getUserTimeLine($screen_name = null)
    {
        if ($screen_name == null) {
            return false;
        } else {
            $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
            $getfield = "?screen_name={$screen_name}";
            $twitter = new TwitterAPIExchange($this->settings);

            $user_timeline = $twitter
                ->setGetfield($getfield)
                ->buildOauth($url, 'GET')
                ->performRequest();

            return json_decode($user_timeline);
        }
    }
}