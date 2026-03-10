<div class="card">
    <h2>Edit Task</h2>
    <form method="post" action="/tasks/<?= $task['id'] ?>" data-ajax-form>
        <div class="notice" data-ajax-message data-auto-dismiss="4000" style="<?= !empty($errors['title']) ? '' : 'display:none;' ?>">
            <span data-ajax-message-text>
                <?php if (!empty($errors['title'])): ?>
                    <?= $errors['title'] ?>
                <?php endif; ?>
            </span>
            <button type="button" class="close" data-ajax-message-close aria-label="Close">×</button>
        </div>
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <label>
            <input type="checkbox" name="is_completed" value="1" <?= !empty($task['isCompleted']) ? 'checked' : '' ?>> Completed
        </label>

        <div class="actions">
            <button class="btn primary" type="submit">Update</button>
            <a class="btn" href="/tasks/<?= $task['id'] ?>">Cancel</a>
        </div>
    </form>
</div>
