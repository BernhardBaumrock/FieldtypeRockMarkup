# FieldtypeRockMarkup

The Inputfield of this Fieldtype will render a file with the name of the field located in a specified folder. If no folder is specified it will look for the files in /site/assets/RockMarkup

## Setting the file path

You can either set the file path via a field's config screen, or if you are only using the Inputfield (for example in a ProcessModule) just set the `path` property:

```php
  public function ___execute() {
    /** @var InputfieldForm $form */
    $form = $this->modules->get('InputfieldForm');

    $form->add([
      'name' => 'demo',
      'type' => 'RockMarkup',
      'label' => 'RockMarkup Field With Custom Path',
      'path' => 'site/templates/markupfields',
    ]);

    return $form->render();
  }
```

The field will then load all asset files that are stored in this folder and have the name of the field, in this case `demo`:

```
/site/templates/markupfields/demo.php
/site/templates/markupfields/demo.css
/site/templates/markupfields/demo.js
```