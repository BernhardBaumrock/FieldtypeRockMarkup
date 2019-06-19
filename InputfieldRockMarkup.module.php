<?php namespace ProcessWire;
/**
 * Inputfield for RockMarkup Fieldtype
 *
 * @author Bernhard Baumrock, 11.02.2019
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class InputfieldRockMarkup extends InputfieldMarkup {

  /**
   * Property to set the directory of the file to render
   * 
   * By default this is not set and the default path is used.
   *
   * @var string
   */
  public $path;

  /**
   * Default path of assets files
   *
   * @var string
   */
  public $defaultPath;

  /**
   * Init this module
   *
   * @return void
   */
  public function init() {
    parent::init();
    $folder = 'RockMarkup';
    $this->defaultPath = $this->toPath($this->config->paths->assets . $folder);

    // hooks can be applied via files named like this: yourField.hook.php
    $this->loadHooks();
  }

  /**
   * Load hooks
   *
   * @return void
   */
  public function loadHooks() {
    $files = $this->files->find($this->config->paths->site, [
      'extensions' => ['hooks'],
      'excludeDirNames' => ['cache', 'files', 'backups'],
    ]);
    foreach($files as $file) {
      $this->files->include($file, [
        'wire' => $this->wire,
      ]);
    }
  }
  
  public function renderReady(Inputfield $parent = null, $renderValueMode = false) {
    // load field-specific scripts and styles
    $file = $this->getFilePath().$this->name.'.js';
    if(is_file($file))
      $this->config->scripts->add($this->toUrl($file).'?t='.filemtime($file));
      
    $file = $this->getFilePath().$this->name.'.css';
    if(is_file($file))
      $this->config->styles->add($this->toUrl($file).'?t='.filemtime($file));

    return parent::renderReady($parent, $renderValueMode);
  }
  
  /**
   * Render file in assets folder
   *
   * @return void
   */
  public function ___render() {
    if(!$this->label) {
      // no label was set
      // if label is not NULL we set the field name as label
      if(!$this->hideLabel) $this->label = $this->name;
    }

    // if a value was set return it
    if($this->value) $out = $this->value;
    else {
      // otherwise try to render the file
      try {
        $path = $this->getFilePath();
        $out = $this->files->render($path.$this->name, [
          'that' => $this, // can be used to attach hooks
        ], [
          'allowedPaths' => [$path],
        ]);
      } catch (\Throwable $th) {
        $out = $th->getMessage();
      }
    }

    return $out.$this->initScriptTag();
  }

  /**
   * Wrap script tags around the output.
   *
   * @param string $out
   * @return void
   */
  protected function initScriptTag() {
    // if javascript events are disabled we return the original markup
    // not implemented yet
    if($this->noEvents) return;

    // javascript events are ON
    // show spinner and fire loaded event
    return "<script>$('#Inputfield_{$this->name}').trigger('loaded');</script>";
  }

  /**
   * Get file path for current field.
   *
   * @return void
   */
  public function getFilePath() {
    // if no path is set we try to get the path from the fields config
    $path = $this->path;
    
    // no path set, try to get it from field config
    if(!$path) $path = $this->getPathFromFieldConfig();

    // still no path, use default path
    if(!$path) $path = $this->defaultPath;
    
    return $this->toPath($path);
  }

  /**
   * Get path from field's config
   *
   * @return string|null
   */
  public function getPathFromFieldConfig() {
    // try to get field
    $field = $this->fields->get($this->name);
    if(!$field) return;
    return $field->path;
  }

  /**
   * Get file url for current field.
   *
   * @return void
   */
  public function getFileUrl() {
    return $this->toUrl($this->getFilePath());
  }

  /**
   * Convert path to url relative to root
   *
   * @param string $path
   * @return string
   */
  public function toUrl($path) {
    $url = str_replace($this->config->paths->root, $this->config->urls->root, $path);
    $url = ltrim($url, "/");
    $url = rtrim($url,"/");

    // is it a file or a directory?
    $info = pathinfo($url);
    if(array_key_exists("extension", $info)) return "/$url";
    else return "/$url/";
  }

  /**
   * Convert url to path and make sure it exists
   *
   * @param string $url
   * @return string
   */
  public function toPath($url) {
    $url = $this->toUrl($url);
    return $this->config->paths->root.ltrim($url,"/");
  }
}
