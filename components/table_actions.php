<?php
function rowDropdown($editUrl = '#', $deleteId = null)
{ ?>
  <div class="dropdown">
    <a href="<?= $editUrl ?>"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
    <?php if ($deleteId): ?>
      <form action="/auth/delete_slip.php" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this slip?')">
        <input type="hidden" name="id" value="<?= $deleteId ?>">
        <button type="submit" class="dropdown-delete">
          <i class="fa-regular fa-trash-can"></i> Delete
        </button>
      </form>
    <?php endif; ?>
  </div>
<?php } ?>