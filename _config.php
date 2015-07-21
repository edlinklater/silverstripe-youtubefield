<?php

// Add custom style into the CMS. Done here instead of YAML so we can use dynamic module path and support SS 3.0.
LeftAndMain::require_css(basename(dirname(__FILE__)) . '/css/LeftAndMainYouTubeField.css');