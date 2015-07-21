# YouTube Field for SilverStripe

## Introduction

This SilverStripe module provides a simple YouTube field, primarily for use in the CMS/ModelAdmin. It accepts input of
various common YouTube URL formats and converts them for storage in database as the 11-character YouTube ID.

## Requirements

 * SilverStripe 3.0+

## Configuration

Optionally you can provide a key for the YouTube v3 API. Currently this has no function, but in future it will be used
to display video information (title, thumbnail, length) under the field when a valid ID has been provided.

*mysite/_config/youtubefield.yml*

	---
	name: youtubefield
	---
	YouTubeField:
	  api_key: YOUR_API_KEY

## URL Parser

There is a static function which can be called (without using the YouTubeField) to simply retrieve the YouTube ID from
a supported URL format.

	YouTubeField::url_parser($url);