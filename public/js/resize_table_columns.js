document.querySelectorAll('.table-card table').forEach((table) => {
  const headers = Array.from(table.querySelectorAll('thead th'));
  if (headers.length < 2) return;

  const tableWidth = table.getBoundingClientRect().width;
  if (!tableWidth) return;

  const widths = headers.map((header) => header.getBoundingClientRect().width);
  const userKey = document.body.dataset.userId || 'anonymous';
  const routeKey = window.location.pathname.replace(/\/(create|edit)\/?$/, '');
  const columnKey = headers.map((header) => header.textContent.trim()).join('|');
  const storageKey = `pass-slip-table-columns:${userKey}:${routeKey}:${columnKey}`;
  const minWidths = headers.map((header) => {
    const label = header.textContent.trim();
    if (!label) return 1;
    return label === 'Actions' ? 220 : 80;
  });
  let initialWidths = widths;

  try {
    const savedWidths = JSON.parse(localStorage.getItem(storageKey) || 'null');
    if (
      Array.isArray(savedWidths) &&
      savedWidths.length === headers.length &&
      savedWidths.every((width, index) => Number.isFinite(width) && width >= minWidths[index] && width <= 3000)
    ) {
      initialWidths = savedWidths;
    }
  } catch (error) {
    // Keep the current layout when local storage is unavailable or invalid.
  }

  // Give unused table width to a trailing filler column. Without it, the
  // browser redistributes that space among the data columns when one shrinks.
  const fillerHeader = document.createElement('th');
  fillerHeader.className = 'table-filler';
  fillerHeader.setAttribute('aria-hidden', 'true');
  headers[headers.length - 1].parentElement.appendChild(fillerHeader);
  table.querySelectorAll('tbody tr').forEach((row) => {
    if (row.cells.length === headers.length) {
      const fillerCell = document.createElement('td');
      fillerCell.className = 'table-filler';
      fillerCell.setAttribute('aria-hidden', 'true');
      row.appendChild(fillerCell);
    }
  });

  const colgroup = document.createElement('colgroup');
  const columns = headers.map(() => document.createElement('col'));
  const fillerColumn = document.createElement('col');

  columns.forEach((column, index) => {
    column.style.width = `${initialWidths[index]}px`;
    colgroup.appendChild(column);
  });
  colgroup.appendChild(fillerColumn);
  table.insertBefore(colgroup, table.firstChild);
  table.classList.add('resizable-table');

  const persistWidths = () => {
    try {
      localStorage.setItem(storageKey, JSON.stringify(columns.map((column) => Number.parseFloat(column.style.width))));
    } catch (error) {
      // Resizing remains available when local storage is unavailable.
    }
  };

  headers.forEach((header, index) => {
    if (index === headers.length - 1 || !header.textContent.trim()) return;

    const handle = document.createElement('span');
    handle.className = 'table-column-resizer';
    header.classList.add('has-column-resizer');
    handle.setAttribute('role', 'separator');
    handle.setAttribute('aria-orientation', 'vertical');
    handle.setAttribute('aria-label', `Resize ${header.textContent.trim()} column`);
    handle.setAttribute('tabindex', '0');
    header.appendChild(handle);

    const resizeBy = (delta) => {
      const currentWidth = columns[index].getBoundingClientRect().width;
      const newWidth = Math.max(minWidths[index], currentWidth + delta);
      columns[index].style.width = `${newWidth}px`;
      persistWidths();
    };

    handle.addEventListener('pointerdown', (event) => {
      event.preventDefault();
      let previousX = event.clientX;
      handle.classList.add('is-resizing');
      handle.setPointerCapture(event.pointerId);

      const onMove = (moveEvent) => {
        resizeBy(moveEvent.clientX - previousX);
        previousX = moveEvent.clientX;
      };
      const onUp = () => {
        handle.classList.remove('is-resizing');
        handle.removeEventListener('pointermove', onMove);
        handle.removeEventListener('pointerup', onUp);
        handle.removeEventListener('pointercancel', onUp);
      };

      handle.addEventListener('pointermove', onMove);
      handle.addEventListener('pointerup', onUp);
      handle.addEventListener('pointercancel', onUp);
    });

    handle.addEventListener('keydown', (event) => {
      if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
      event.preventDefault();
      resizeBy(event.key === 'ArrowRight' ? 16 : -16);
    });
  });
});
