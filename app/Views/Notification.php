<div id="notification" aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 1rem; right: 1rem; min-width: 300px; z-index: 1080;">
  <div class="toast align-items-center text-bg-<?= htmlspecialchars($type ?? 'info') ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div id="nf-text" class="toast-body">
        <?= $message ?? '' ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

