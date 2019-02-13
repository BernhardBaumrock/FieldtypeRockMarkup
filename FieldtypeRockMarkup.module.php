<?php namespace ProcessWire;
/**
 * Fieldtype that can hold any custom markup.
 *
 * @author Bernhard Baumrock, 11.02.2019
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class FieldtypeRockMarkup extends Fieldtype {

  /**
   * module initialisation
   */
  public function init() {
  }

  /**
   * attach pagerender hook when api is ready
   */
  public function ready() {
  }

  /**
   * Return the associated Inputfield
   */
  public function getInputfield(Page $page, Field $field) {
    $f = $this->modules->get('InputfieldRockMarkup');
    bd($this, 'getinputfield');
    // $f->label = 'foo';
    $f->skipLabel = Inputfield::skipLabelBlank;
    return $f;
  }

  /**
   * the formatted value of this field
   * necessary to render the grid's markup on the frontend
   */
  public function sanitizeValue(Page $page, Field $field, $value) {
    if($this->process == 'ProcessPageView') {
      $f = $this->getInputfield($page, $field);
      $f->field = $field;
      return $f->render();
    }
  }

  /**
   * Load the necessary javascript.
   *
   * @return void
   */
  public function loadScripts() {
    $this->config->scripts->add($this->config->urls($this) . 'InputfieldRockMarkup.js');
  }

  ###########################################################################################

  /**
   * The following functions are defined as replacements to keep this fieldtype out of the DB
   *
   */

  public function ___wakeupValue(Page $page, Field $field, $value) {
    return $value;
  }

  public function ___sleepValue(Page $page, Field $field, $value) {
    return $value;
  }

  public function getLoadQuery(Field $field, DatabaseQuerySelect $query) {
    // prevent loading from DB
    return $query; 
  }

  public function ___loadPageField(Page $page, Field $field) {
    // generate value at runtime rather than loading from DB
    return null; 
  }

  public function ___savePageField(Page $page, Field $field) {
    // prevent saving of field
    return true;
  }

  public function ___deletePageField(Page $page, Field $field) {
    // deleting of page field not necessary
    return true; 
  }

  public function ___deleteField(Field $field) {
    // deleting of field not necessary
    return true; 
  }

  public function getDatabaseSchema(Field $field) {
    // no database schema necessary
    return array();
  }

  public function ___createField(Field $field) {
    // nothing necessary to create the field
    return true; 
  }

  public function getMatchQuery($query, $table, $subfield, $operator, $value) {
    // we don't allow this field to be queried
    throw new WireException("Field '{$query->field->name}' is runtime and not queryable");
  }
  
  public function ___getCompatibleFieldtypes(Field $field) {
    // no fieldtypes are compatible
    return new Fieldtypes();
  }

  public function getLoadQueryAutojoin(Field $field, DatabaseQuerySelect $query) {
    // we don't allow this field to be autojoined
    return null;
  }

}

