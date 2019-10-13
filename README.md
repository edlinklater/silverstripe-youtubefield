# YouTube Field for SilverStripe

## Introduction

This SilverStripe module provides a YouTube field for use in the CMS/ModelAdmin. It accepts input of various common
YouTube URL formats and converts them for storage in database as the 11-character YouTube ID.

Once a valid ID is saved the field will display the video's thumbnail and title.

Optionally you can provide a key for the YouTube v3 API, which is used to display additional information (duration and
view count) and provides information immediately (rather than after saving).

![Screenshot of Video Information](https://cloud.githubusercontent.com/assets/1176635/10863696/39612420-803c-11e5-8940-95e190c06545.png)

## Requirements

 * silverstripe/framework ^4.0 _(See `1` branch for SilverStripe 3 compatibility)_

## Basic field

*mysite/code/Page.php*

```php
<?php

use EdgarIndustries\YouTubeField\YouTubeField;

class Page extends SiteTree
{

    private static $db = array(
        'VideoID' => 'Varchar(11)',
    );
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new YouTubeField('VideoID', 'YouTube Video'));
        return $fields;
    }
    
}
```

*mysite/_config/config.yml*
```yaml
EdgarIndustries\YouTubeField\YouTubeField:
  api_key: YOUR_API_KEY
```

## URL Parser

There is a static function which can be called (without using the YouTubeField) to simply retrieve the YouTube ID from
a supported URL format.

```php
YouTubeField::url_parser($url);
```
