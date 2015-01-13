<?php namespace Grav\Plugin;

use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

use RocketTheme\Toolbox\Session\Session;

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

    if (!isset($this->grav['themes_preview']) or !$this->grav['themes_preview']) {
      $this->grav->redirect('/' . $this->grav['config']->get('system.pages.theme'));
    }

    $this->enable([
      'onPagesInitialized'    => ['onPagesInitialized', 0],
      'onPageNotFound'        => ['onPageNotFound', 100],
      'onTwigInitialized'     => ['onTwigInitialized', 0],
      'onTwigTemplatePaths'   => ['onTwigTemplatePaths', 0],
      'onTwigSiteVariables'   => ['onTwigSiteVariables', 0],
      'onOutputGenerated'     => ['onOutputGenerated', 0]
    ]);
  }

  public function onPagesInitialized()
  {
    $pages = $this->grav['pages'];
    $uri = $this->grav['uri'];

    $base = $pages->base();

    $path = '/' . $this->grav['themes_preview'] . '/' . $base;

    $pages->base($path);
  }

  public function onPageNotFound(Event $event)
  {
    $uri    = $this->grav['uri'];
    $pages  = $this->grav['pages'];

    $path = str_replace('/' . $this->grav['themes_preview'], '', $uri->path());

    $page = $pages->dispatch($path, true);

    if (!$page) {
      return;
    }

    $event->page = $page;
    $event->stopPropagation();
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

  public function onTwigSiteVariables()
  {
    $this->grav['twig']->twig_vars['current_theme'] = $this->grav['themes_preview'];
  }

  public function onOutputGenerated()
  {
    $this->template_file = 'plugins/themes_preview/bar.html.twig';
    $this->template_vars = [
      'root_url'      => $this->grav['uri']->rootUrl(false),
      'themes'        => $this->grav['themes']->all(),
      'current_theme' => $this->grav['themes_preview']
    ];

    $content = $this->grav['twig']->twig()->render($this->template_file, $this->template_vars);

    echo $content;
  }
}
