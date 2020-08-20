<?php

use AventriEventSync\Aventri;

ignore_user_abort(true);

require_once __DIR__ . '/../../../../wp-load.php';

$speakers = get_posts([
    'post_type' => Aventri::POST_TYPE_SPEAKER,
]);

foreach ($speakers as $speaker) {
    echo 'Name: ';
    echo get_the_title($speaker);
    echo PHP_EOL;
    echo 'Sessions:';
    echo PHP_EOL;

    $sessions = Aventri::get_speaker_sessions($speaker->ID);
    foreach ($sessions as $session) {
        echo '- ';
        echo get_the_title($session);
        echo PHP_EOL;
    }

    echo PHP_EOL;
}
