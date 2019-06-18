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
  }

  
  public function renderReady(Inputfield $parent = null, $renderValueMode = false) {
    // load field-specific scripts and styles
    $file = $this->getFilePath().$this->name.'.js';
    if(is_file($file))
      $this->config->scripts->add($this->getFileUrl().$this->name.'.js?t='.filemtime($file));
      
    $file = $this->getFilePath().$this->name.'.css';
    if(is_file($file))
      $this->config->scripts->add($this->getFileUrl().$this->name.'.css?t='.filemtime($file));

    return parent::renderReady($parent, $renderValueMode);
  }
  
  /**
   * Render file in assets folder
   *
   * @return void
   */
  public function ___render() {
    // if a value was set return it
    if($this->value) $out = $this->value;
    else {
      // otherwise try to render the file
      try {
        $path = $this->getFilePath();
        bd($path, 'path');
        $out = $this->files->render($path.$this->name, [], [
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
    // show spinner and fire init event
    return "<script>$('#Inputfield_{$this->name}').trigger('RockMarkup.init');</script>";
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
    return "$url/";
  }

  /**
   * Convert url to path and make sure it exists
   *
   * @param string $url
   * @return string
   */
  public function toPath($url) {
    $url = $this->toUrl($url);
    return $this->config->paths->root.$url;
  }
}
