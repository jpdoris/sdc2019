<?php

namespace Rublon_WordPress\Libs\RublonImplemented;

use RublonHelper;

class Rublon2FactorGUIWordPress
{

    public static $instance;

    public static function getInstance()
    {

        if (empty(self::$instance)) {
            $additional_settings = RublonHelper::getSettings('additional');
            $current_user = wp_get_current_user();
            self::$instance = new self(
                RublonHelper::getRublon(),
                RublonHelper::getUserId($current_user),
                RublonHelper::getUserEmail($current_user),
                $logout_listener = RublonHelper::isLogoutListenerEnabled()
            );

            // Embed consumer script
            if (RublonHelper::isSiteRegistered() && !RublonHelper::isNewVersion()) {
                add_action('wp_footer', array(self::$instance, 'renderConsumerScript'), PHP_INT_MAX);
                add_action('admin_footer', array(self::$instance, 'renderConsumerScript'), PHP_INT_MAX);
            }
        }

        return self::$instance;
    }


    public function getConsumerScript()
    {
        // Don't show consumer script, it will be embeded in the footer action using self::renderConsumerScript() method.
        return '';
    }


    /**
     * Returns Rublon Button for plugin's registration.
     *
     * Since the registration is now handled automatically,
     * the button is not necessary.
     *
     * @return RublonButton
     */
    protected function createActivationButton($activationURL)
    {
        return '';
    }


    /**
     * Create Trusted Devices Widget container for WP Dashboard
     *
     * @return string
     */
    public function getTDMWidget()
    {

        $result = '';

        if (RublonHelper::isSiteRegistered()) {

            if (RublonHelper::canShowTDMWidget()) {

                $current_user = wp_get_current_user();
                $protection_type = RublonHelper::YES;
                RublonHelper::isUserProtected($current_user, $protection_type);
                switch ($protection_type) {
                    case RublonHelper::PROTECTION_TYPE_MOBILE:
                        $result .= '<p>' . sprintf(__('Your account is protected by <a href="%s" target="_blank">Rublon</a>.', 'rublon'), RublonHelper::rubloncomUrl()) . '</p>';
                        break;
                    case RublonHelper::PROTECTION_TYPE_EMAIL:
                        $result .= '<p>' . sprintf(__('Your account is protected by <a href="%s" target="_blank">Rublon</a>.', 'rublon'), RublonHelper::rubloncomUrl())
                            . ' ' . sprintf(__('Get the <a href="%s/get" target="_blank">Rublon mobile app</a> for more security.', 'rublon'), RublonHelper::rubloncomUrl()) . '</p>';
                        break;
                    case RublonHelper::PROTECTION_TYPE_NONE:
                        $lang = RublonHelper::getBlogLanguage();
                        $result .= '<p>' . sprintf(
                                '<span style="color: red; font-weight: bold;">' . __('Warning!', 'rublon') . '</span>'
                                . ' ' . __('Your account is not protected. Go to <a href="%s">your profile page</a> to enable account protection.', 'rublon'),
                                admin_url(RublonHelper::WP_PROFILE_PAGE . RublonHelper::WP_PROFILE_EMAIL2FA_SECTION)
                            ) . '</p>';
                        break;
                    case RublonHelper::PROTECTION_TYPE_DISABLED:
                        $lang = RublonHelper::getBlogLanguage();
                        $result .= '<p>' .
                            '<span style="color: red; font-weight: bold;">' . __('Warning!', 'rublon') . '</span>'
                            . ' ' . __('Your account\'s protection is disabled. Ask yor Administrator for more information.', 'rublon')

                            . '</p>';
                        break;
                }

                $result .= $this->getDeviceWidget();
            } else {
                $current_user = wp_get_current_user();
                $status = $mobile_user_status = RublonHelper::getUserProtectionStatus($current_user);

                if ($status) {
                    $result = '<p>' . sprintf(__('Your account is protected by <a href="%s" target="_blank">Rublon</a>.', 'rublon'), RublonHelper::rubloncomUrl()) . '</p>';
                } else {
                    $result = '<p>' . __('Your account isn\'t protected by Rublon and thus vulnerable to password theft and brute force attacks. Please contact your administrator. ') . '</p>';
                }
            }

        }

        return $result;

    }


    /**
     * Create Trusted Devices Widget container for WP Dashboard
     *
     * @return string
     */
    public function getACMWidget()
    {
        return $this->getShareAccessWidget();
    }


    public function renderConsumerScript()
    {

        wp_enqueue_script('jquery');

        // Consumer script
        echo parent::getConsumerScript();

    }

    public function getBadgeWidget()
    {
        return '';//new RublonBadge();
    }

    public function userBox()
    {

        return '';

    }


}
