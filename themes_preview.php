<?php namespace Grav\Plugin;

use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

use Grav\Common\Plugin;
use Grav\Common\Data\Data;

class Themes_PreviewPlugin extends Plugin
{
  protected $template_vars = [];

  protected $current_theme;

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

    // Validate presence of params.
    if (!$this->grav['uri']->params() or !$this->grav['uri']->param('theme')) {
      return;
    }

    // Save current theme.
    $this->current_theme = $this->grav['uri']->param('theme');

    // Save current theme into config.
    $this->grav['config']->set('system.pages.theme', $this->current_theme);
    $this->grav['config']->set('system.home.alias', '/' . $this->current_theme . $this->grav['config']->get('system.home.alias'));

    $this->enable([
      'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
      'onTwigInitialized'     => ['onTwigInitialized', 0],
      'onPageNotFound'        => ['onPageNotFound', 100],
      'onPagesInitialized'    => ['onPagesInitialized', 100],
      'onTwigSiteVariables'   => ['onTwigSiteVariables', 100]
    ]);
  }

  public function onTwigInitialized()
  {
    $this->grav['twig']->twig()->addFunction(
      new \Twig_SimpleFunction('themes_preview', [$this, 'themesPreviewFunction'], ['is_safe' => ['html']])
    );
  }

  public function onTwigTemplatePaths()
  {
    $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
  }

  public function themesPreviewFunction($params = [])
  {
    $this->template_file = 'plugins/themes_preview/bar.html.twig';
    $this->template_vars = [];

    $output = $this->grav['twig']->twig()->render($this->template_file, $this->template_vars);

    return $output;
  }

  public function onPageNotFound(Event $event)
  {
    $uri    = $this->grav['uri'];
    $pages  = $this->grav['pages'];

    $page = $pages->dispatch('/' . $this->current_theme . $uri->path(), true);

    if (!$page) {
      return;
    }

    $event->page = $page;
    $event->stopPropagation();
  }

  public function onPagesInitialized()
  {
    //$page = $this->grav['page'];

    //$this->grav['pages'] = $page->find('/' . $this->current_theme)->children();
  }

  public function onTwigSiteVariables()
  {
    $this->grav['twig']->twig_vars['current_theme'] = $this->current_theme;
  }

  private function mergeConfig(Page $page, $params = [])
  {
    $this->config = new Data((array) $this->grav['config']->get('plugins.simple_form'));

    if (isset($page->header()->simple_form)) {
      if (is_array($page->header()->simple_form)) {
        $this->config = new Data(array_replace_recursive($this->config->toArray(), $page->header()->simple_form));
      } else {
        $this->config->set('enabled', $page->header()->simple_form);
      }
    }

    $this->config = new Data(array_replace_recursive($this->config->toArray(), $params));
  }
}
