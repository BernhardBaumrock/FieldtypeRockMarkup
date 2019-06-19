<?php namespace ProcessWire;
/**
 * RockMarkupSandbox Info
 *
 * @author Bernhard Baumrock, 18.06.2019
 * @license Licensed under MIT
 */
$info = [
  'title' => 'RockMarkup Sandbox',
  'summary' => 'RockMarkup Sandbox Process Module.',
  'version' => 1,
  'author' => 'Bernhard Baumrock',
  'icon' => 'wrench',
  'requires' => ['InputfieldRockMarkup'],
  'page' => [
    'name' => 'rockmarkup-sandbox',
    'title' => 'RockMarkup Sandbox',
    'parent' => 'setup',
  ],
];
