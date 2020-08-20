<?php
/*
 Template Name: Schedule
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
 get_header('page');

 get_template_part( 'partials/full-width-banner' );

 $scheduleFields = get_field('schedule_group');
 ?>

 <main class="content-schedule">
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
        <h1 class="text-center mb-2"><?php echo $scheduleFields['sessions_title']; ?>
            <span class="show-hide"><a id="show-hide">Hide Filters</a></span></h1>
        <div class="filter-topics">
            <h2 class="mb-2">Topics
                <span class="clear-all d-inline d-xl-none"><a id="clear-all-sm">Clear All Filters</a></span></h2>
            <div class="filter-topics-content"></div>
        </div>
        <div class="session-search">
            <div class="search-form">
                <form id="sessions-searchform">
                    <input type="text" placeholder="Search sessions...">
                    <button id="day1">Day 1</button>
                    <button id="day2">Day 2</button>
                    <div class="select-menu">
                        <select name="type" id="select-type">
                            <option value="">Type</option>
                        </select>
                    </div>
                    <div class="select-menu">
                        <select name="level" id="select-level">
                            <option value="">Level</option>
                        </select>
                    </div>
                </form>
                <span class="clear-all d-none d-xl-block"><a id="clear-all-xl">Clear All Filters</a></span>
            </div>
        </div>
        <div class="show-sessions"></div>
        <div class="view-more"><a id="view-more">View More</a></div>
    </div>

    <button type="button" data-toggle="modal" data-target="#sessionModal">Launch modal</button>
    <div class="modal" id="sessionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close d-none d-lg-block" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/modal-close.svg" alt="">
                        </span>
                    </button>
              </div>
              <div class="modal-body">
                    <button type="button" class="modal__back-btn d-lg-none" data-dismiss="modal">
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/back-icon.svg" alt="">
                        </span>
                    </button>
                    <h3 class="modal__title modal__session__title">session title</h3>
                    <p class="modal__subheading mb-0 modal__session__date-time">Tuesday, October 29, 12:30 – 1:30 PM</p>
                    <p class="modal__subheading modal__session__location">Room 4</p>
                    <p class="modal__session__description">Join Dr. David Rhew, Chief Medical Officer as well as VP and GM of B2B Healthcare at Samsung Electronics America, as he moderates a panel session on the use of devices, data, and analytics in the healthcare world. He’ll be joined by key thought leaders from medical device companies and large health systems from Kaiser and UCSF to Medtronic, Dexcom and Insulet. Hear about how the industry is shifting towards the use of secure smartphones to manage medical implants as well as how medical device data, which traditionally has been “locked and silo’d” may now be analyzed in conjunction with other sources of data. Find out how wearables will play a larger role in managing one’s health. Additional topics of discussion include FDA and data security/privacy considerations.</p>

                    <div class="modal__session__meta-container">
                        <div class="d-inline-flex modal__topics modal__session__meta pl-0 align-items-center">
                            <p>Topic(s):</p>
                            <ul class="modal__topics__list">
                                <!-- <li>Health</li>
                                <li>Test</li> -->
                            </ul>
                        </div>
                        <div class="d-flex d-lg-inline-flex modal__session__meta">
                            <p>Type: </p>
                            <p class="modal__session__meta__type">Panel</p>
                        </div>
                        <div class="d-flex d-lg-inline-flex modal__session__meta">
                            <p>Level: </p>
                            <p class="modal__session__meta__level">Intermediate</p>
                        </div>
                    </div>

                    <p class="modal__subheading">Speakers</p>
                    <div class="modal__session__speakers-list"></div>
              </div>
          </div>
      </div>
  </div>
  <div class="modal" id="speakerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center d-none d-lg-block">
                <button type="button" class="modal__back-btn" data-dismiss="modal">
                    <span>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/back-icon.svg" alt="">
                    </span>
                </button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/modal-close.svg" alt="">
                    </span>
                </button>
            </div>
            <div class="modal-body modal__speaker__body">
                <button type="button" class="modal__back-btn d-lg-none" data-dismiss="modal">
                    <span>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/back-icon.svg" alt="">
                    </span>
                </button>
                <div class="d-flex flex-column align-items-center flex-lg-row modal__speaker__header">
                    <div class="modal__speaker__image">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/speaker2.png" alt="">
                    </div>
                    <div>
                        <h3 class="modal__title modal__speaker__name">David Rhew</h3>
                        <p class="modal__subheading modal__speaker__title">Chief Medical Officer</p>
                        <div class="text-center text-lg-left">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/iconmonstr-twitter-1.png" class="d-inline-block mr-4 modal__speaker__social-icon" alt="">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/iconmonstr-linkedin-3.png" class="d-inline-block modal__speaker__social-icon" alt="">
                        </div>
                    </div>
                </div>

                <p>Dr. David Rhew is the Chief Medical Officer (CMO) and Head of Healthcare and Fitness for Samsung Electronics of America. David received his Bachelor of Science degrees in computer science and cellular molecular biology from the University of Michigan. He received his MD degree from Northwestern University and completed internal medicine residency at Cedars-Sinai Medical Center. He completed fellowships in health services research at Cedars-Sinai Medical Center and infectious diseases at the University of California, Los Angeles.</p>

                <p>Dr. Rhew has served as Senior Vice-President and CMO at Zynx Health Inc.; clinician/researcher in the Division of Infectious Diseases at the VA Greater Los Angeles Healthcare System; and Associate Clinical Professor of Medicine at UCLA. Dr. Rhew’s interests include measurably improving the quality, safety, and efficiency of patient care and applying technology to engage patients and consumers in their health.</p>

                <div class="modal__speaker__links">
                    <a href="#" class="modal__link d-block modal__speaker__link">Check Out David Rhew’s Sessions</a>
                    <a href="#" class="modal__link d-block modal__speaker__link">View All Speakers</a>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<?php get_footer('page'); ?>
