<?php
$image = get_field('full_width_banner_image');
if( $image ){
    ?>
    <div class="partial-full_width_banner" style="background-image: url(<?php echo $image; ?>)"></div>
    <?php
}
?>
