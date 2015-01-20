<?php namespace Grav\Plugin;

use Grav\Common\Plugin;


class Themes_PreviewPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        // Add current theme string to debugger.
        $current_theme_string = "Current theme: " . $this->grav['config']->get('system.pages.theme');
        $this->grav['debugger']->addMessage($current_theme_string, 'info');

        $this->enable([
            'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
            'onOutputRendered'      => ['onOutputRendered', 100000]
        ]);
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    public function onOutputRendered()
    {
        // Save most used objects.
        $grav   = $this->grav;
        $config = $grav['config'];
        $uri    = $grav['uri'];
        $twig   = $grav['twig'];

        $extension = $uri->extension();
        $invalid_extensions = ['json', 'xml', 'atom', 'rss'];

        // Validate mime extension.
        if (in_array($extension, $invalid_extensions)) {
            return;
        }

        // Working on themes object.
        $themes_obj = $grav['themes']->all();

        // Assign array to themes.
        $themes = [];

        // Get master domain from configuration or get latest segment in the host.
        if ($config->get('plugins.themes_preview.master_domain'))
        {
            $master_domain = $config->get('plugins.themes_preview.master_domain');
        } else {
            $host = $uri->host();

            $segments = explode('.', $host);

            $master_domain = str_replace($segments[0] . '.', '', $host);
        }

        // Save most used variables for template rendering.
        $theme_current  = $config->get('system.pages.theme');
        $root_url       = $uri->rootUrl(false);
        $request_scheme = $_SERVER['REQUEST_SCHEME'];

        // Cycle all themes.
        foreach ($themes_obj as $theme_key => $theme_obj) {
            $themes[] = [
                'name'      => $theme_obj->blueprints()->get('name'),
                'key'       => $theme_key,
                'url'       => "{$request_scheme}://{$theme_key}.{$master_domain}{$root_url}/",
                'author'    => $theme_obj->blueprints()->get('author.name'),
                'current'   => ($theme_current == $theme_key) ? true : false
            ];
        }

        $template_file = 'plugins/themes_preview/bar.html.twig';
        $template_vars = [
            'themes'        => $themes,
            'is_admin'      => $this->isAdmin(),
            'current_theme' => $theme_current
        ];

        // Render template file.
        $content = $twig->twig()->render($template_file, $template_vars);

        echo $content;
    }
}
