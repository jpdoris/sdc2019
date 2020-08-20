<?php
/*
 Template Name: Getting Here
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
get_header('page');

get_template_part('partials/full-width-banner');

$fields = get_fields();
?>

<main class="content-getting-here">
  <div class="container-fluid location-information">
    <div class="row align-items-lg-center">
      <div class="col-12 col-lg-6 mb-4 mb-lg-0 location-information__name-address-col">
        <p class="location-information__name">
          <?php echo $fields['location_information']['name']; ?>
        </p>
        <span class="location-information__address">
          <?php echo $fields['location_information']['address']; ?>
        </span>
      </div>
      <div class="col-12 col-lg-6">
        <div id="convention-center-map-container">
          <div class="map-label">
            <a href="https://goo.gl/maps/q7rWK5w9mN8zuZec8" target="_blank">
              <h4>Convention Center Station</h4>
              <p>San Jose, CA 95110</p>
            </a>
            <p><a href="https://goo.gl/maps/q7rWK5w9mN8zuZec8" target="_blank">View larger map</a></p>
            <a href="https://www.google.com/maps/dir//Convention+Center+Station,+San+Jose,+CA+95110" target="_blank" class="directions">
              <img src="<?php echo get_template_directory_uri(); ?>/images/directions.svg">
              Directions
            </a>
          </div>
          <div id="convention-center-map"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="hotels-section">
    <div class="container hotels-section__nav-container">
      <div class="row align-items-lg-center no-gutters">
        <div class="col-4 hotels-section__nav-container__btn-col">
          <div class="hotels-section__change-view-btn__container">
            <button type="button" class="hotels-section__change-view-btn hotels-section__slider-view-btn active" data-target="#hotels-slider-view">
              <span class="sr-only">Change view</span>
            </button>

            <button type="button" class="hotels-section__change-view-btn hotels-section__map-view-btn" data-target="#hotels-map-view">
              <span class="sr-only">Change view</span>
            </button>
          </div>
        </div>
        <div class="col-4 text-center">
          <div class="hotels-section__section__title__wrap">
            <p class="section__title">Hotels</p>
          </div>
        </div>
      </div>
    </div>
    <div id="hotels-slider-view" class="hotels-slick-slider hotels-view">
      <?php
      $hotelDivs = [];

      while (have_rows('hotels')) : the_row();
        $image = get_sub_field('image');
        $name = get_sub_field('name');
        $nameLink = get_sub_field('name_link');
        $description = get_sub_field('description');
        $addressLine1 = get_sub_field('address_line_1');
        $addressLine2 = get_sub_field('address_line_2');
        $addressLink = get_sub_field('address_link');
        $distanceText = get_sub_field('distance_from_conference');
        $phoneNumber = get_sub_field('phone_number');
        $googleMapData = get_sub_field('google_map');

        ob_start();
        ?>
        <span class="hotel-data" data-lat="<?= $googleMapData['lat']; ?>" data-lng="<?= $googleMapData['lng'] ?>">
          <p class="hotel-data__name">
            <?php if (!empty($nameLink)) : ?>
              <a href="<?= $nameLink ?>" target="_blank">
              <?php endif; ?>
              <?= $name ?>
              <?php if (!empty($nameLink)) : ?>
              </a>
            <?php endif; ?>
          </p>
          <!-- <span class="hotel-data__desc"><?= $description ?></span> -->
          <span class="hotel-data__desc">
            <p class="hotel-data__desc__ad1">
              <?php if (!empty($addressLink)) : ?>
                <a href="<?= $addressLink ?>" target="_blank">
                <?php endif; ?>
                <?= $addressLine1 ?>
                <?php if (!empty($addressLink)) : ?>
                </a>
              <?php endif; ?>
            </p>
            <p class="hotel-data__desc__ad2">
              <?php if (!empty($addressLink)) : ?>
                <a href="<?= $addressLink ?>" target="_blank">
                <?php endif; ?>
                <?= $addressLine2 ?>
                <?php if (!empty($addressLink)) : ?>
                </a>
              <?php endif; ?>
            </p>
            <p class="hotel-data__desc__distance"><?= $distanceText ?></p>
            <p><?= $phoneNumber ?></p>
            <p class="hotel-info__desc__special_rates">
              *Special Rates available upon registration
            </p>
          </span>
        </span>
        <?php
        $content = ob_get_clean();
        $hotelDivs[] = $content;
        ?>
        <div class="hotels-slide">
          <div class="img-outer">
            <img src="<?= $image ?>" alt="">
          </div>
          <div class="hotel-info">
            <h3 class="hotel-info__name">
              <?php if (!empty($nameLink)) : ?>
                <a href="<?= $nameLink ?>" target="_blank">
                <?php endif; ?>
                <?= $name ?>
                <?php if (!empty($nameLink)) : ?>
                </a>
              <?php endif; ?>
            </h3>
            <span class="hotel-info__desc">
              <p class="hotel-info__desc__ad1">
                <?php if (!empty($addressLink)) : ?>
                  <a href="<?= $addressLink ?>" target="_blank">
                  <?php endif; ?>
                  <?= $addressLine1 ?>
                  <?php if (!empty($addressLink)) : ?>
                  </a>
                <?php endif; ?>
              </p>
              <p class="hotel-info__desc__ad2">
                <?php if (!empty($addressLink)) : ?>
                  <a href="<?= $addressLink ?>" target="_blank">
                  <?php endif; ?>
                  <?= $addressLine2 ?>
                  <?php if (!empty($addressLink)) : ?>
                  </a>
                <?php endif; ?>
              </p>
              <p class="hotel-info__desc__distance"><?= $distanceText ?></p>
              <p class="hotel-info__desc__phone"><?= $phoneNumber ?></p>
              <p class="hotel-info__desc__special_rates">
                *Special Rates available upon registration
              </p>
            </span>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <div id="hotels-map-view" class="hotels-view d-none">

      <div id="hotelsMap" data-lat="<?= $fields['location_information']['google_map_embed']['lat'] ?>" data-lng="<?= $fields['location_information']['google_map_embed']['lng'] ?>"></div>
    </div>

    <span id="hotel-data__container" class="d-none">
      <?php
      foreach ($hotelDivs as $d) {
        echo $d;
      }
      ?>
    </span>
  </div>
  <div class="transportation-section">
    <p class="section__title">Transportation</p>

    <div class="container transportation-section__container">
      <div class="row">
        <div class="col-12 col-lg-6">
          <ul class="nav nav-tabs justify-content-center justify-content-lg-start" id="transportationTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab-plane" data-toggle="tab" href="#transportation-plane" role="tab">
                <span class="sr-only">Plane</span>
              </a>
            </li>
            <li class="nav-item" id="nav-item__bus">
              <a class="nav-link" id="tab-bus" data-toggle="tab" href="#transportation-bus" role="tab">
                <span class="sr-only">Bus</span>
              </a>
            </li>
            <li class="nav-item" id="nav-item__parking">
              <a class="nav-link" id="tab-parking" data-toggle="tab" href="#transportation-parking" role="tab">
                <span class="sr-only">Parking</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-bicycle" data-toggle="tab" href="#transportation-bicycle" role="tab">
                <span class="sr-only">Bicycle</span>
              </a>
            </li>
          </ul>

          <div class="tab-content" id="transportationTabContent">
            <div class="tab-pane fade show active" id="transportation-plane" role="tabpanel">
              <?php echo $fields['transportation']['plane']; ?>
            </div>
            <div class="tab-pane fade" id="transportation-bus" role="tabpanel">
              <?php echo $fields['transportation']['bus']; ?>
            </div>
            <div class="tab-pane fade" id="transportation-parking" role="tabpanel">
              <?php echo $fields['transportation']['parking']; ?>
            </div>
            <div class="tab-pane fade" id="transportation-bicycle" role="tabpanel">
              <?php echo $fields['transportation']['bicycle']; ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6">
          <img src="<?php echo $fields['transportation_image']; ?>" alt="Transportation" class="d-none d-lg-block">
        </div>
      </div>
    </div>
  </div>
</main>

<?php get_footer('page'); ?>