<?php foreach (take_flash() as $message): ?>
<aside class="alert alert-<?= e($message['type']) ?>" role="<?= $message['type'] === 'error' ? 'alert' : 'status' ?>">
    <?= e($message['message']) ?>
</aside>
<?php endforeach; ?>