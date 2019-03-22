<?php

namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Utils;

/**
 * Class PermissionsPlugin
 * @package Grav\Plugin
 */
class PermissionsPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onTwigSiteVariables()
    {
        if ($this->isAdmin()) {
            $this->grav['locator']->addPath('blueprints', '', __DIR__ . DS . 'blueprints');
        }
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }
    }

    private function shouldRedirect($header) {
        $requireLogin = (bool)Utils::getDotNotation(isset($header) ? (array)$header : [], 'login.visibility_requires_access');
        if (!$requireLogin) {
            return false;
        }

        $isLoginPage = $this->grav['page']->route()===$this->grav['config']['plugins']['login']['route'];
        if ($isLoginPage) {
            return false;
        }

        return !$this->grav['user']['authenticated'];
    }

}
