<?php
    $notice = $_GET['notice'] ?? '';
    $noticeText = '';
    if ($notice === 'created') {
        $noticeText = 'Task created successfully.';
    } elseif ($notice === 'updated') {
        $noticeText = 'Task updated successfully.';
    } elseif ($notice === 'deleted') {
        $noticeText = 'Task deleted successfully.';
    }
?>
<div class="two-col">
    <div>
        <h2>Home</h2>
        <div data-ajax-global-message class="notice <?= $noticeText ? 'success' : '' ?>" data-auto-dismiss="4000" style="<?= $noticeText ? '' : 'display:none;' ?>">
            <span data-ajax-message-text><?= htmlspecialchars($noticeText, ENT_QUOTES, 'UTF-8') ?></span>
            <button type="button" class="close" data-ajax-message-close aria-label="Close">×</button>
        </div>

    </div>
    <aside class="card">
        
    </aside>
</div>
