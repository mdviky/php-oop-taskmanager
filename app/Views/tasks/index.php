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
        <h2>Tasks</h2>
        <div data-ajax-global-message class="notice <?= $noticeText ? 'success' : '' ?>" data-auto-dismiss="4000" style="<?= $noticeText ? '' : 'display:none;' ?>">
            <span data-ajax-message-text><?= htmlspecialchars($noticeText, ENT_QUOTES, 'UTF-8') ?></span>
            <button type="button" class="close" data-ajax-message-close aria-label="Close">×</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task->title, ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if ($task->isCompleted): ?>
                                <span class="badge done">Completed</span>
                            <?php else: ?>
                                <span class="badge open">Open</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a class="btn" href="/tasks/<?= $task->id ?>">View</a>
                            <a class="btn" href="/tasks/<?= $task->id ?>/edit">Edit</a>
                            <form class="inline" method="post" action="/tasks/<?= $task->id ?>/delete" data-ajax-delete>
                                <button class="btn danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <aside class="card">
        <h3>Quick Tips</h3>
        <p>Use clear titles to keep your list searchable. Mark tasks as completed once done.</p>
        <div class="actions">
            <a class="btn primary" href="/tasks/create">Create Task</a>
            <a class="btn" href="/tasks">Refresh</a>
        </div>
    </aside>
</div>
