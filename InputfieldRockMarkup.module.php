<?php namespace ProcessWire;
/**
 * Inputfield for RockMarkup Fieldtype
 *
 * @author Bernhard Baumrock, 11.02.2019
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class InputfieldRockMarkup extends InputfieldMarkup {

  // property to set the directory of the file to render
  // by default this is not set and the default path is used
  public $path;

  
  public function renderReady(Inputfield $parent = null, $renderValueMode = false) {
    // load field-specific scripts and styles
    if(is_file($this->getFilePath().$this->name.'.js'))
      $this->config->scripts->add($this->getFileUrl().$this->name.'.js');
    if(is_file($this->getFilePath().$this->name.'.css'))
      $this->config->scripts->add($this->getFileUrl().$this->name.'.css');

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
    $name = str_replace("Inputfield", "", $this->className());
    $path = $this->path ?: $this->config->paths->assets . $name;
    $path = rtrim($path,"/")."/";
    return $path;
  }

  /**
   * Get file url for current field.
   *
   * @return void
   */
  public function getFileUrl() {
    $path = $this->getFilePath();
    return str_replace($this->config->paths->root, $this->config->urls->root, $path);
  }
}
