<p>
  Here you see all files inside folders that are listed in the 
  <a href='#'>module's config</a>.
</p>

<ul uk-accordion>
  <?php
  $dirs = $this->modules->get('RockMarkupSandbox')->getDirs();
  $rm = $this->modules->get('InputfieldRockMarkup');
  foreach($dirs as $dir): ?>
    <li class="uk-open">
      <a class="uk-accordion-title" href="#"><?= $dir ?></a>
      <div class="uk-accordion-content">
        <?= $rm->toPath($dir); ?>
      </div>
    </li>
    <?php
  endforeach;
  ?>
</ul>

<ul>
  <?php
  foreach($this->files->find($path, [
    'extensions' => ['php'],
  ]) as $file) {
    $info = (object)pathinfo($file);
    $name = $info->filename;
    echo "<li><a href='./?name=$name'>$name</a></li>";
  }
  ?>
</ul>
