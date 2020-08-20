<?php
/*
 Template Name: Schedule V1
 *
 * This template only has the tabbed schedule and session text. No session information list, modals, etc
*/
 get_header('page');

 get_template_part( 'partials/full-width-banner' );

 $scheduleFields = get_field('schedule_group');
 ?>

 <main class="content-schedule-v1">
    <div id="schedule-section">
        <h1 class="text-center mb-4 mb-md-5"><?php echo $scheduleFields['schedule_title']; ?></h1>
        <ul class="nav nav-tabs justify-content-center" id="scheduleTab" role="scheduleList">
            <li class="nav-item"><a href="#day0" id="day0-tab" class="nav-link" role="tab" data-toggle="tab" aria-controls="day0">Day 0</a></li>
            <li class="nav-item"><a href="#day1" id="day1-tab" class="nav-link active" role="tab" data-toggle="tab" aria-controls="day1">Day 1</a></li>
            <li class="nav-item"><a href="#day2" id="day2-tab" class="nav-link" role="tab" data-toggle="tab" aria-controls="day2">Day 2</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="day0" role="tabpanel">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-0-desktop.svg" class="d-none d-md-block mx-auto" alt="Day 0">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-0-mobile.svg" class="d-md-none mx-auto" alt="Day 0">
            </div>
            <div class="tab-pane active" id="day1" role="tabpanel">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-1-desktop.svg" class="d-none d-md-block mx-auto" alt="Day 1">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-1-mobile.svg" class="d-md-none mx-auto" alt="Day 1">
            </div>
            <div class="tab-pane" id="day2" role="tabpanel">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-2-desktop.svg" class="d-none d-md-block mx-auto" alt="Day 2">
                <img src="<?php echo get_template_directory_uri(); ?>/images/day-2-mobile.svg" class="d-md-none mx-auto" alt="Day 2">
            </div>
        </div>
    </div>

    <div id="sessions-section">
    <h1 class="text-center mb-4 mb-md-5"><?php echo $scheduleFields['sessions_title']; ?></h1>
    <p>
        <?php echo $scheduleFields['sessions_text']; ?>
    </p>
    </div>
</main>

<?php get_footer('page'); ?>
