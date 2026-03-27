<?php

// Developed with the assistance of Claude Code (claude.ai)

namespace Grav\Plugin;

use Grav\Common\Plugin;

class AdminHeliosRefreshPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        if (!$this->isAdmin()) {
            return;
        }

        $this->enable([
            'onPageInitialized' => ['onPageInitialized', 0],
        ]);
    }

    public function onPageInitialized()
    {
        $assets = $this->grav['assets'];

        if ($this->config->get('plugins.admin-helios-refresh.admin_styling_enhancements', true)) {
            $assets->addCss('plugin://admin-helios-refresh/assets/admin.css');
        }

        $fontSize = $this->config->get('plugins.admin-helios-refresh.admin_font_size', 'large');
        if ($fontSize !== 'default') {
            $assets->addCss("plugin://admin-helios-refresh/assets/admin-fonts-{$fontSize}.css");
        }

        $this->injectHeliosPreset();
        $this->injectLoginCss();
    }

    protected function injectHeliosPreset()
    {
        // Only inject if no custom presets are already defined by the user
        $existing = $this->config->get('plugins.admin.whitelabel.custom_presets');
        if (!empty($existing)) {
            return;
        }

        $preset = file_get_contents(__DIR__ . '/helios-preset.yaml');
        $this->config->set('plugins.admin.whitelabel.custom_presets', $preset);
    }

    protected function injectLoginCss()
    {
        // Only inject if no custom_css is already defined by the user
        $existing = $this->config->get('plugins.admin.whitelabel.custom_css');
        if (!empty($existing)) {
            return;
        }

        $this->config->set(
            'plugins.admin.whitelabel.custom_css',
            '#admin-login h1 svg path:first-child { fill: rgba(255, 255, 255, 0.10); }'
        );
    }
}
