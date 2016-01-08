<?php

class YouTubeField extends TextField {

	/**
	 * @var string
	 * @config
	 * (optional) YouTube API key valid for this site. Used for displaying video information within the CMS.
	 * @link https://developers.google.com/youtube/v3/
	 */
	private static $api_key;

	public function Field($properties = array()) {
		if($api_key = $this->config()->get('api_key')) {
			$this->setAttribute('data-apikey', $api_key);
			Requirements::javascript(SS_YOUTUBEFIELD_DIRECTORY . '/javascript/YouTubeField.js');
			Requirements::javascript('https://apis.google.com/js/client.js?onload=googleApiClientReady');
		}

		return parent::Field($properties);
	}

	public function dataValue() {
		return self::url_parser($this->value);
	}

	/**
	 * Provide a default Right Title, explaining what can be put into this field.
	 *
	 * @return string Right Title, or an explanatory default if none set
	 */
	public function RightTitle() {
		if(!empty($this->rightTitle)) {
			return $this->rightTitle;
		} else {
			return 'YouTube video URL or ID. Must be a single video, not a playlist or channel.';
		}
	}

	public function Type() {
		return 'text youtube';
	}

	/**
	 * Parse YouTube URL into a valid 11-character ID.
	 *
	 * @param $url string Valid YouTube URL or ID.
	 *
	 * @return string|false YouTube Video ID, or false if no ID found
	 */
	public static function url_parser($url) {
		$regex = '/(?<=v=|v\/|vi=|vi\/|youtu.be\/|embed\/)([a-zA-Z0-9_-]{11})/';

		if (!empty($url)) {
			//full youtube URL
			if (strpos($url,'youtu') !== false) {
				if (preg_match($regex, $url, $matches)) {
					return $matches[1];
				}
			} elseif(strlen($url) == 11) {
				return $url;
			}
		}

		return false;
	}

}