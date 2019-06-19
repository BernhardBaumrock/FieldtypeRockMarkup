<?php namespace ProcessWire;
/**
 * RockMarkupSandbox Config
 *
 * @author Bernhard Baumrock, 18.06.2019
 * @license Licensed under MIT
 */
class RockMarkupSandboxConfig extends ModuleConfig {

  // public function __construct() {
  //   bd($this->dirs, 'dirs');
  // }

  // public function getInputfields() {
  //   bd($this->dirs, 'getconf');
  //   $this->dirs = 'blaa';

  //   $inputfields = parent::getInputfields();

  //   /** @var InputfieldRockMarkup $rm */
  //   $rm = $this->modules->get('InputfieldRockMarkup');
  //   $examples = $rm->toUrl(__DIR__."/examples");

  //   $f = $this->modules->get('InputfieldTextarea');
  //   $f->name = 'dirs';
  //   $f->label = 'Directories for the Sandbox Module';
  //   $f->description = 'Directories that are listed here will be scanned for files in the Sandbox Module.';
  //   $f->notes = "Enter one by line!\nWill always be appended: $examples";
  //   $inputfields->add($f);

  //   return $inputfields;
    
    // $this->addHookAfter('Inputfield(name=dirs)::processInput', function(HookEvent $event) {
    //   $f = $event->object;

    //   /** @var InputfieldRockMarkup $rm */
    //   $rm = $this->modules->get('RockMarkupSandbox');
    //   $dirs = $rm->getDirs($f->value);
    //   $f->value = implode("\n", $dirs);
    // });
  // }
}

