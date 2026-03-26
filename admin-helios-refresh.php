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

        $preset = <<<'YAML'
- name: Helios
  accents:
    primary-accent: button
    secondary-accent: notice
    tertiary-accent: critical
  colors:
    logo-bg: '#18181B'
    logo-link: '#FFFFFF'
    nav-bg: '#27272A'
    nav-text: '#9CA3AF'
    nav-link: '#F9FAFB'
    nav-selected-bg: '#18181B'
    nav-selected-link: '#FFFFFF'
    nav-hover-bg: '#3F3F46'
    nav-hover-link: '#FFFFFF'
    toolbar-bg: '#FFFFFF'
    toolbar-text: '#1F2937'
    page-bg: '#F4F4F5'
    page-text: '#374151'
    page-link: '#2563EB'
    content-bg: '#FAFAFA'
    content-text: '#374151'
    content-link: '#2563EB'
    content-link2: '#7A5EBD'
    content-header: '#111827'
    content-tabs-bg: '#E5E7EB'
    content-tabs-text: '#4B5563'
    content-highlight: '#ffffd7'
    button-bg: '#2563EB'
    button-text: '#FFFFFF'
    notice-bg: '#059669'
    notice-text: '#FFFFFF'
    update-bg: '#7A5EBD'
    update-text: '#FFFFFF'
    critical-bg: '#DC2626'
    critical-text: '#FFFFFF'
YAML;

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
