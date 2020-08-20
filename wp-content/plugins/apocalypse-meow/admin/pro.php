<?php
/**
 * Admin: Premium License
 *
 * @package Apocalypse Meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\ajax;
use blobfolio\wp\meow\license;
use blobfolio\wp\meow\options;
use blobfolio\wp\meow\vendor\common;

$data = array(
	'forms'=>array(
		'pro'=>array(
			'action'=>'meow_ajax_pro',
			'n'=>ajax::get_nonce(),
			'license'=>options::get('license'),
			'errors'=>array(),
			'saved'=>false,
			'loading'=>false,
		),
	),
	'freeload'=>license::FREELOAD,
);
$license = license::get($data['forms']['pro']['license']);
$data['license'] = $license->get_license();

// JSON doesn't appreciate broken UTF.
common\ref\sanitize::utf8($data);
?>
<script>var meowData=<?php echo \json_encode($data, \JSON_HEX_AMP); ?>;</script>
<div class="wrap" id="vue-pro" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Premium License', 'apocalypse-meow'); ?></h1>

	<?php
	// Warn about OpenSSL.
	if (! \function_exists('openssl_get_publickey')) {
		echo '<div class="notice notice-warning">';
			// @codingStandardsIgnoreStart
			echo '<p>' . __('Please ask your system administrator to enable the OpenSSL PHP extension. Without this, your site will be unable to decode and validate the license details itself. In the meantime, Apocalypse Meow will try to offload this task to its own server. This should get the job done, but won\'t be as efficient and could impact performance a bit.', 'apocalypse-meow') . '</p>';
			// @codingStandardsIgnoreEnd
		echo '</div>';
	}
	?>

	<div class="updated" v-if="forms.pro.saved"><p><?php echo \__('Your license has been saved!', 'apocalypse-meow'); ?></p></div>
	<div class="error" v-for="error in forms.pro.errors"><p>{{error}}</p></div>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder meow-columns one-two">

			<!-- License -->
			<div class="postbox-container two">
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('License Key', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<form name="proForm" method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" v-on:submit.prevent="proSubmit">
							<textarea id="meow-license" class="meow-code" name="license" v-model.trim="forms.pro.license" placeholder="Paste your license key here."></textarea>
							<p><button type="submit" v-bind:disabled="forms.pro.loading" class="button button-primary button-large"><?php echo \__('Save', 'apocalypse-meow'); ?></button></p>
						</form>
					</div>
				</div>
			</div><!--.postbox-container-->

			<!-- License -->
			<div class="postbox-container one">

				<div class="postbox" v-if="!license.license_id">
					<h3 class="hndle"><?php echo \__('The Pro Pitch', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<?php
						echo '<p>' . \sprintf(
							\__("Apocalypse Meow's proactive security hardening and attack mitigation features are *free*. Forever. This is not about extortion. Haha. The %s is geared toward developers, system administrators, and general tech enthusiasts.", 'apocalypse-meow'),
							'<a href="' . \MEOW_URL . '" target="_blank">Pro version</a>'
						) . '</p>';

						echo '<p>' . \__("TL;DR it's about Workflow and Data:", 'apocalypse-meow') . '</p>';

						echo '<ul style="list-style: disc; margin-left: 2em;">';
						echo '<li>' . \__('Easy data exports and visualizations;', 'apocalypse-meow') . '</li>';
						echo '<li>' . \__('Complete WP-CLI integration;', 'apocalypse-meow') . '</li>';
						echo '<li>' . \__('Advanced management tools for managing sessions, user passwords, renaming users, and more;', 'apocalypse-meow') . '</li>';
						echo '<li>' . \__('Hookable actions and filters for custom theme/plugin integration;', 'apocalypse-meow') . '</li>';
						echo '</ul>';

						echo '<p>' . \sprintf(
							\__('To learn more, visit %s.', 'apocalypse-meow'),
							'<a href="' . \MEOW_URL . '" target="_blank">blobfolio.com</a>'
						) . '</p>';
						?>
					</div>
				</div>

				<div class="postbox" v-if="license.license_id && !isFreeload">
					<h3 class="hndle"><?php echo \__('Your License', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<table class="meow-meta">
							<tbody>
								<tr>
									<th scope="row"><?php echo \__('Created', 'apocalypse-meow'); ?></th>
									<td>{{license.date_created}}</td>
								</tr>
								<tr v-if="license.date_created !== license.date_updated">
									<th scope="row"><?php echo \__('Updated', 'apocalypse-meow'); ?></th>
									<td>{{license.date_updated}}</td>
								</tr>
								<tr v-if="license.errors.revoked">
									<th class="meow-fg-orange" scope="row"><?php echo \__('Revoked', 'apocalypse-meow'); ?></th>
									<td>{{license.date_revoked}}</td>
								</tr>
								<tr>
									<th scope="row"><?php echo \__('Name', 'apocalypse-meow'); ?></th>
									<td>{{license.name}}</td>
								</tr>
								<tr v-if="license.company">
									<th scope="row"><?php echo \__('Company', 'apocalypse-meow'); ?></th>
									<td>{{license.company}}</td>
								</tr>
								<tr>
									<th scope="row"><?php echo \__('Email', 'apocalypse-meow'); ?></th>
									<td>{{license.email}}</td>
								</tr>
								<tr>
									<th scope="row"><?php echo \__('Type', 'apocalypse-meow'); ?></th>
									<td>{{license.type}}</td>
								</tr>
								<tr>
									<th v-bind:class="{'meow-fg-orange' : license.errors.item}" scope="row"><?php echo \__('Thing', 'apocalypse-meow'); ?></th>
									<td>{{license.item}}</td>
								</tr>
								<tr v-if="license.type === 'single'">
									<th v-bind:class="{'meow-fg-orange' : license.errors.domain}" scope="row"><?php echo \__('Domain(s)', 'apocalypse-meow'); ?></th>
									<td>
										<div v-for="domain in license.domains">{{domain}}</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php echo \__('Help', 'apocalypse-meow'); ?></th>
									<td>
										<span v-if="!license.errors.domain && !license.errors.item && !license.errors.revoked"><?php echo \__('Thanks for going Pro!', 'apocalypse-meow'); ?></span>
										<?php
										echo \sprintf(
											\__('If you have any questions or need help, visit %s.', 'apocalypse-meow'),
											'<a href="' . \MEOW_URL . '" target="_blank">blobfolio.com</a>'
										);
										?>
									</td>
							</tbody>
						</table>
					</div>
				</div>

				<div class="postbox" v-if="!license.license_id || isFreeload">
					<h3 class="hndle"><?php echo \__('Freeload Edition', 'apocalypse-meow'); ?></h3>
					<div class="inside" v-if="!isFreeload">
						<?php
						$out = array(
							\sprintf(
								\__("If you can't afford a %s or do not wish to purchase one, you can still enable premium functionality by clicking the button below.", 'apocalypse-meow'),
								'<a href="' . \MEOW_URL . '" target="_blank">Premium License</a>'
							),
							\__('If your situation changes in the future, you can always switch the registration to a real license. :)', 'apocalypse-meow'),
						);

						echo '<p>' . \implode('</p><p>', $out) . '</p>';
						?>
						<p><button class="button" type="button" v-on:click.prevent="freeloadSubmit" v-bind:disabled="forms.pro.loading"><?php echo \__('Enable', 'apocalypse-meow'); ?></button></p>
					</div>
					<div class="inside" v-else>
						<?php
						$out = array(
							\__('The Freeload Edition has been enabled so you have access to all premium features. Hurray for Code Anarchy!', 'apocalypse-meow'),
							\sprintf(
								\__('If you went ahead and purchased a %s — thanks! — just go ahead and replace the key and hit save.', 'apocalypse-meow'),
								'<a href="' . \MEOW_URL . '" target="_blank">Premium License</a>'
							),
						);

						echo '<p>' . \implode('</p><p>', $out) . '</p>';
						?>
					</div>
				</div>

				<?php
				$plugins = admin::sister_plugins();
				if (\count($plugins)) {
					?>
					<div class="postbox">
						<div class="inside">
							<a href="https://blobfolio.com/" target="_blank" class="sister-plugins--blobfolio"><?php echo \file_get_contents(\MEOW_PLUGIN_DIR . 'img/blobfolio.svg'); ?></a>

							<div class="sister-plugins--intro">
								<?php
								echo \sprintf(
									\__('Impressed with %s?', 'apocalypse-meow') . '<br>' .
									\__('You might also enjoy these other fine and practical plugins from %s.', 'apocalypse-meow'),
									'<strong>Apocalypse Meow</strong>',
									'<a href="https://blobfolio.com/" target="_blank">Blobfolio, LLC</a>'
								);
								?>
							</div>

							<nav class="sister-plugins">
								<?php foreach ($plugins as $p) { ?>
									<div class="sister-plugin">
										<a href="<?php echo \esc_attr($p['url']); ?>" target="_blank" class="sister-plugin--name"><?php echo \esc_html($p['name']); ?></a>

										<div class="sister-plugin--text"><?php echo \esc_html($p['description']); ?></div>
									</div>
								<?php } ?>
							</nav>
						</div>
					</div>
				<?php } ?>

			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->
</div><!--.wrap-->
