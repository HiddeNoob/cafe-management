<div>
    <?php if (isset($e) && $e instanceof Exception): ?>
        <p style="color: red;"><?= htmlspecialchars($e->getMessage()) ?></p>
    <?php endif; ?> 
</div>