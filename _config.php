<?php

define('SS_YOUTUBEFIELD_DIRECTORY', basename(dirname(__FILE__)));

// Add custom style into the CMS. Done here instead of YAML so we can use dynamic module path and support SS 3.0.
LeftAndMain::require_css(SS_YOUTUBEFIELD_DIRECTORY . '/css/LeftAndMainYouTubeField.css');