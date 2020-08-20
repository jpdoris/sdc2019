<?php

use AventriEventSync\Aventri;

ignore_user_abort(true);

require_once __DIR__ . '/../../../../wp-load.php';

$sessions = get_posts([
    'post_type' => Aventri::POST_TYPE_SESSION,
]);

foreach ($sessions as $session) {
    echo 'Title: ';
    echo get_the_title($session);
    echo PHP_EOL;
    echo 'Speakers:';
    echo PHP_EOL;

    $speakers = Aventri::get_session_speakers($session->ID);
    foreach ($speakers as $speaker) {
        echo '- ';
        echo get_the_title($speaker) . ' ' . get_post_meta($speaker->ID, 'facebook')[0];
        echo PHP_EOL;
    }

    echo PHP_EOL;
}
