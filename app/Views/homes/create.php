<div class="card">
    <h2>Create Task</h2>
    <form method="post" action="/tasks" data-ajax-form data-reset-on-success="true">
        <div class="notice" data-ajax-message data-auto-dismiss="4000" style="<?= !empty($errors['title']) ? '' : 'display:none;' ?>">
            <span data-ajax-message-text>
                <?php if (!empty($errors['title'])): ?>
                    <?= $errors['title'] ?>
                <?php endif; ?>
            </span>
            <button type="button" class="close" data-ajax-message-close aria-label="Close">×</button>
        </div>
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <label>
            <input type="checkbox" name="is_completed" value="1"> Completed
        </label>

        <div class="actions">
            <button class="btn primary" type="submit">Create</button>
            <a class="btn" href="/tasks">Cancel</a>
        </div>
    </form>
</div>
