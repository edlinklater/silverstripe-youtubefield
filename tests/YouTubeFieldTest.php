<?php

class YouTubeFieldTest extends SapphireTest {

	protected $videos = array(
		'zGm4d7kMvu8',
		'zKx2B8WCQuw',
		'OPf0YbXqDm0',
		'J_8mdH20qTQ',
		'wVFMCgyDixc',
		'0b-v-wMR69k',
	);

	function testID() {
		foreach($this->videos as $v) {
			$field = new YouTubeField('TestYouTubeField');
			$field->setValue($v);
			$this->assertEquals($field->dataValue(), $v);
			unset($field);
		}
	}

	function testLongURL() {
		foreach($this->videos as $v) {
			$field = new YouTubeField('TestYouTubeField');
			$field->setValue('https://www.youtube.com/watch?v=' . $v);
			$this->assertEquals($field->dataValue(), $v);
			unset($field);
		}
	}

	function testShortURL() {
		foreach($this->videos as $v) {
			$field = new YouTubeField('TestYouTubeField');
			$field->setValue('https://youtu.be/' . $v);
			$this->assertEquals($field->dataValue(), $v);
			unset($field);
		}
	}

	function testShortTimestampedURL() {
		foreach($this->videos as $v) {
			$field = new YouTubeField('TestYouTubeField');
			$field->setValue('https://youtu.be/' . $v . '?t=3s');
			$this->assertEquals($field->dataValue(), $v);
			unset($field);
		}
	}

	function testEmbedURL() {
		foreach($this->videos as $v) {
			$field = new YouTubeField('TestYouTubeField');
			$field->setValue('https://www.youtube.com/embed/' . $v);
			$this->assertEquals($field->dataValue(), $v);
			unset($field);
		}
	}

	function testStaticParser() {
		foreach($this->videos as $v) {
			$this->assertEquals(YouTubeField::YouTubeURLParser('https://www.youtube.com/watch?v=' . $v), $v);
		}
	}

}