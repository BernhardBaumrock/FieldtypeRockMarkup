<?php namespace ProcessWire;
/** @var InputfieldForm $form */
/** @var RockMarkupSandbox $sandbox */
$form = $this->modules->get('InputfieldForm');
$form->name = 'renderExample';
$form->method = 'GET';

// setup example path
$path = $this->config->paths($sandbox)."examples/";
$info = (object)pathinfo($name);

// add hidden file field
$form->add([
  'type' => 'hidden',
  'name' => 'name',
  'value' => $info->filename,
]);

// ajax checkbox
$isAjax = $this->input->get('ajax', 'int');
$ajax = $this->modules->get('InputfieldCheckbox');
$ajax->name = 'ajax';
$ajax->label = ' AJAX';
$ajax->attr('checked', $isAjax ? 'checked' : '');

// add rendered grid
$f = $this->modules->get('InputfieldMarkup');
$f->name = 'navbar';
$f->value =
  "<div class='uk-child-width-1-1 uk-child-width-1-2@m' uk-grid>"
    ."<div class='uk-text-center uk-text-left@m'>"
      ."<a href='./'><i class='fa fa-arrow-left' aria-hidden='true'></i> Zurück</a>"
    ."</div>"
    ."<div class='uk-text-center uk-text-right@m'>"
      .$ajax->render()
    ."</div>"
  ."</div>";
$f->addClass('uk-margin-remove');
$form->add($f);

// add rendered grid
$form->add([
  'type' => 'RockMarkup',
  'name' => $info->filename,
  'path' => $path,
  'label' => 'Result',
  'collapsed' => $isAjax ? Inputfield::collapsedYesAjax : Inputfield::collapsedNo,
]);

// add code
$form->add([
  'type' => 'markup',
  'name' => $info->filename.'_code',
  'label' => '',
  'icon' => 'code',
  'value' => $sandbox->renderCode($info->filename),
]);
?>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
<?= $form->render(); ?>
<script>hljs.initHighlightingOnLoad();</script>
