<?php

namespace EdgarIndustries\YouTubeField;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;

class YouTubeField extends TextField
{
    /**
     * @var string
     * @config
     * (optional) YouTube API key valid for this site. Used for displaying video information within the CMS.
     * @link https://developers.google.com/youtube/v3/
     */
    private static $api_key;

    public function Field($properties = [])
    {
        Requirements::css('toastnz/youtubefield:client/css/YouTubeField.css');
        Requirements::javascript('toastnz/youtubefield:client/js/YouTubeField.js');

        if ($api_key = $this->config()->get('api_key')) {
            $this->setAttribute('data-apikey', $api_key);
            Requirements::javascript('https://apis.google.com/js/client.js?onload=googleApiClientReady');
        } elseif (!empty($this->value) && self::url_parser($this->value)) {
            $this->setAttribute('data-apidata', json_encode(static::get_video_information($this->value)));
        }

        return parent::Field($properties);
    }

    public function dataValue()
    {
        return self::url_parser($this->value);
    }

    /**
     * Provide a default Right Title, explaining what can be put into this field.
     *
     * @return string Right Title, or an explanatory default if none set
     */
    public function getDescription()
    {
        if (!empty($this->description)) {
            return $this->description;
        } else {
            return 'YouTube video URL or ID. Must be a single video, not a playlist or channel.';
        }
    }

    public function Type()
    {
        return 'text youtube';
    }

    public static function get_video_information($videoId)
    {
        $client = new Client();
        try {
            $res = $client->get('https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=' . $videoId . '&format=json');
            if ($res->getStatusCode() == '200' && $data = json_decode($res->getBody())) {
                $api_data = new \stdClass();
                $api_data->id = $videoId;
                $api_data->snippet = new \stdClass();
                $api_data->snippet->title = $data->title;
                if (preg_match('/user\/([\w-]+)/', $data->author_url, $author)) {
                    $api_data->snippet->channelTitle = $author[1];
                }
                $api_data->snippet->thumbnails = new \stdClass();
                $api_data->snippet->thumbnails->default = new \stdClass();
                $api_data->snippet->thumbnails->default->url = $data->thumbnail_url;

                return $api_data;
            }
        } catch (ClientException $e) {}
    }

    /**
     * Parse YouTube URL into a valid 11-character ID.
     *
     * @param $url string Valid YouTube URL or ID.
     *
     * @return string|false YouTube Video ID, or false if no ID found
     */
    public static function url_parser($url)
    {
        $regex = '/(?<=v=|v\/|vi=|vi\/|youtu.be\/|embed\/)([a-zA-Z0-9_-]{11})/';

        if (!empty($url)) {
            //full youtube URL
            if (strpos($url, 'youtu') !== false) {
                if (preg_match($regex, $url, $matches)) {
                    return $matches[1];
                }
            } elseif (strlen($url) == 11) {
                return $url;
            }
        }

        return false;
    }

}
