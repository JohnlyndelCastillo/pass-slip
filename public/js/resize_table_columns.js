document.querySelectorAll('.table-card table').forEach((table) => {
  const headers = Array.from(table.querySelectorAll('thead th'));
  if (headers.length < 2) return;

  const tableWidth = table.getBoundingClientRect().width;
  if (!tableWidth) return;

  const widths = headers.map((header) => header.getBoundingClientRect().width);
  const totalWidth = widths.reduce((sum, width) => sum + width, 0);
  const colgroup = document.createElement('colgroup');
  const columns = headers.map(() => document.createElement('col'));

  columns.forEach((column, index) => {
    column.style.width = `${(widths[index] / totalWidth) * 100}%`;
    colgroup.appendChild(column);
  });
  table.insertBefore(colgroup, table.firstChild);
  table.classList.add('resizable-table');

  headers.forEach((header, index) => {
    if (index === headers.length - 1 || !header.textContent.trim() || header.textContent.trim() === 'Actions') return;

    const handle = document.createElement('span');
    handle.className = 'table-column-resizer';
    header.classList.add('has-column-resizer');
    handle.setAttribute('role', 'separator');
    handle.setAttribute('aria-orientation', 'vertical');
    handle.setAttribute('aria-label', `Resize ${header.textContent.trim()} column`);
    handle.setAttribute('tabindex', '0');
    header.appendChild(handle);

    const resizeBy = (delta) => {
      const currentTableWidth = table.getBoundingClientRect().width;
      const currentWidth = columns[index].getBoundingClientRect().width;
      const nextWidth = columns[index + 1].getBoundingClientRect().width;
      const nextHeader = headers[index + 1];
      const nextLabel = nextHeader.textContent.trim();
      const requestedNextMinWidth = nextLabel === 'Actions' ? 260 : (nextLabel ? 80 : 72);
      const nextMinWidth = Math.min(requestedNextMinWidth, nextWidth);
      const minWidth = Math.min(80, currentWidth);
      const lowerBound = minWidth - currentWidth;
      const upperBound = nextWidth - nextMinWidth;
      if (upperBound < lowerBound) return;
      const adjustedDelta = Math.max(lowerBound, Math.min(delta, upperBound));
      const newWidth = currentWidth + adjustedDelta;
      const newNextWidth = nextWidth - adjustedDelta;

      columns[index].style.width = `${(newWidth / currentTableWidth) * 100}%`;
      columns[index + 1].style.width = `${(newNextWidth / currentTableWidth) * 100}%`;
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
