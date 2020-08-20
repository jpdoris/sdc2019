<?php

namespace AventriEventSync;

use AventriEventSync\Service\Synchronizer;

class SyncCronjob
{
    const KEY = Aventri::KEY . '_sync_cronjob';
    const NAME = 'Synchronization';

    // NOTICE: Re-activate the plugin when changing the interval
    // This is the default interval when no interval has been set
    // through the admin panel.
    const DEFAULT_INTERVAL = Aventri::CRON_DAILY;

    /**
     * @var Synchronizer
     */
    private $synchronizer;

    public function __construct()
    {
        $this->synchronizer = new Synchronizer();
    }

    /**
     * Run the cronjob.
     * This method will be executed each time the scheduled cronjob is being called.
     */
    public function run()
    {
        if ($this->synchronizer !== null) {
            try {
                $this->synchronizer->sync();
            } catch (Exception\ConnectionException $e) {
                /** @noinspection ForgottenDebugOutputInspection */
                error_log($e->getMessage());
            } catch (Exception\MissingAccessToken $e) {
                /** @noinspection ForgottenDebugOutputInspection */
                error_log($e->getMessage());
            } catch (Exception\RequestMethodNotImplemented $e) {
                /** @noinspection ForgottenDebugOutputInspection */
                error_log($e->getMessage());
            }
        }
    }

    /**
     * Returns the interval for this cronjob.
     * It could be either the one set from the admin panel,
     * or the default one if the former does not exist.
     */
    public static function getInterval()
    {
        $plugin_options = get_option(Aventri::KEY);

        return $plugin_options['cronjob_intervals'][self::KEY] ?? self::DEFAULT_INTERVAL;
    }
}
