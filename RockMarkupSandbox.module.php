<?php namespace ProcessWire;
/**
 * RockMarkupSandbox Module
 *
 * @author Bernhard Baumrock, 18.06.2019
 * @license Licensed under MIT
 */
class RockMarkupSandbox extends Process {

  public $exampleDir;
  private $rm;

  /**
   * Init. Optional.
   */
  public function init() {
    parent::init(); // always remember to call the parent init

    // setup example dir
    /** @var InputfieldRockMarkup $rm */
    $this->rm = $this->modules->get('InputfieldRockMarkup');
    $this->exampleDir = $this->rm->toUrl(__DIR__."/examples");
  }

  /**
   * Main execute method
   */
  public function execute() {
    $name = $this->input->get('name', 'text');
    if($name) {
      return $this->files->render(__DIR__ . '/views/renderExample', [
        'sandbox' => $this,
        'name' => $name,
      ]);
    }
    
    return $this->files->render(__DIR__ . '/views/execute', [
      'path' => __DIR__."/examples",
    ]);
  }

  /**
   * Get dirs array from module config
   *
   * @return array
   */
  public function getDirs($dirs = null) {
    /** @var InputfieldRockMarkup $rm */
    $rm = $this->modules->get('InputfieldRockMarkup');

    $dirs = trim($dirs ?: $this->dirs);
    $dirs = explode("\n", $dirs);

    // loop all lines
    $arr = [];
    foreach($dirs as $dir) {
      if(!$dir) continue;
      // make sure it is a directory
      $arr[] = $rm->toUrl($dir);
    }
    return $arr;
  }

  /**
   * Get dirs including the example dir
   * 
   * @param int $index Element that should be returned
   * @return array
   */
  public function getExampleDirs($index = null) {
    $dirs = $this->getDirs();
    $dirs[] = $this->exampleDir;
    return $index !== null ? $dirs[$index] : $dirs;
  }

  /**
   * Render code of given file
   * 
   * Todo: Move to file via $files->render()
   *
   * @param object $file
   * @return string
   */
  public function renderCode($file) {
    $out = '';
    $dir = $this->rm->toPath($this->getExampleDirs($this->input->get('dir', 'int')));
    $path = $dir.$file;
    
    // setup editor link
    $link = 'vscode://file/%file:%line';
    $tracy = $this->modules->get('TracyDebugger');
    if($tracy AND $tracy->editor) $link = $tracy->editor;
    
    // show field name
    $out .= "<table class='uk-table uk-table-small uk-table-divider'>"
      ."<tbody>";
      
    $out .=
    "<tr>"
      ."<td class='uk-width-auto uk-text-nowrap'>Name</td>"
      ."<td class='uk-width-expand'><a href=# class='copy'>"
        .'<i class="fa fa-clone uk-margin-small-right" aria-hidden="true"></i>'
        ."<span>$file</span>"
      ."</a></td>"
    ."</tr>";

    $out .=
      "<tr>"
        ."<td class='uk-width-auto uk-text-nowrap'>Inputfield ID</td>"
        ."<td class='uk-width-expand'><a href=# class='copy'>"
          .'<i class="fa fa-clone uk-margin-small-right" aria-hidden="true"></i>'
          ."<span>#Inputfield_$file</span>"
        ."</a></td>"
      ."</tr>";
      
    $out .=
    "<tr>"
      ."<td class='uk-width-auto uk-text-nowrap'>Directory</td>"
      ."<td class='uk-width-expand'><a href=# class='copy'>"
        .'<i class="fa fa-clone uk-margin-small-right" aria-hidden="true"></i>'
        ."<span>$dir</span>"
      ."</a></td>"
    ."</tr>";

    // show code of all files
    foreach(['php', 'hooks', 'js', 'css'] as $ext) {
      if(!is_file("$path.$ext")) continue;
      $lang = $ext;
      if($lang == 'hooks') $lang = 'php';

      $url = str_replace("%file", "$path.$ext", $link);
      $url = str_replace("%line", "1", $url);
      $code = $this->sanitizer->entities(file_get_contents("$path.$ext"));
      
      $out .= "<tr>"
        ."<td class='uk-text-nowrap'>"
          .'<i class="fa fa-file-code-o uk-margin-small-right" aria-hidden="true"></i>'
          ."<a href='$url'>$file.$ext</a>"
        ."</td>"
        ."<td>"
          ."<pre class='uk-margin-small'><code class='$lang'>$code</code></pre>"
        ."</td>"
        ."</tr>";
    }
    
    $out .= "</tbody></table>";

    return $out;
  }
}

