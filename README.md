# YouTube Field for SilverStripe

## Introduction

This SilverStripe module provides a simple YouTube field, primarily for use in the CMS/ModelAdmin. It accepts input of
various common YouTube URL formats and converts them for storage in database as the 11-character YouTube ID.

## Requirements

 * silverstripe/framework 3.0+ for basic field and URL parser
 * silverstripe/framework 3.3+ for video information support

## Basic field

    private static $db = array(
        'VideoID' => 'Varchar(11)',
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new \RebelAlliance\YouTubeField\YouTubeField('VideoID', 'YouTube Video'));
        return $fields;
    }

## Video Information

Optionally you can provide a key for the YouTube v3 API, which is used to display video information (title, thumbnail,
length) under the field when a valid ID has been provided.

![Screenshot of Video Information](https://cloud.githubusercontent.com/assets/1176635/10863696/39612420-803c-11e5-8940-95e190c06545.png)

*mysite/_config/youtubefield.yml*

	---
	name: youtubefield
	---
	RebelAlliance\YouTubeField\YouTubeField:
      api_key: 'YOUR_API_KEY'

## URL Parser

There is a static function which can be called (without using the YouTubeField) to simply retrieve the YouTube ID from
a supported URL format.

	\RebelAlliance\YouTubeField\YouTubeField::url_parser($url);
