<div class="card">
    <h2><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></h2>
    <p><?= nl2br(htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
    <p>
        <?php if (!empty($task['isCompleted'])): ?>
            <span class="badge done">Completed</span>
        <?php else: ?>
            <span class="badge open">Open</span>
        <?php endif; ?>
    </p>
    <div class="actions">
        <a class="btn" href="/tasks/<?= $task['id'] ?>/edit">Edit</a>
        <a class="btn" href="/tasks">Back</a>
    </div>
</div>
