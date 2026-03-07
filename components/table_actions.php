<?php
function rowDropdown($editUrl = '#', $deleteUrl = '#')
{ ?>
  <div class="dropdown">
    <a href="<?= $editUrl ?>"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
    <a href="<?= $deleteUrl ?>" class="danger"><i class="fa-regular fa-trash-can"></i> Delete</a>
  </div>
<?php } ?>