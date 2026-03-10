<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <style>
        :root {
            --bg: #f5f2ec;
            --surface: #ffffff;
            --ink: #1f2328;
            --muted: #6b7280;
            --accent: #2563eb;
            --accent-ink: #ffffff;
            --border: #e5e1da;
            --danger: #b42318;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 14px;
            --radius-sm: 10px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Source Sans 3", "Segoe UI", system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 800px at 15% -10%, #fff 0, transparent 60%),
                radial-gradient(900px 600px at 110% 10%, #eef2ff 0, transparent 55%),
                var(--bg);
        }
        .page {
            max-width: 1000px;
            margin: 32px auto 64px;
            padding: 0 20px;
        }
        header.app-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            position: sticky;
            top: 16px;
            z-index: 10;
        }
        .brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .brand h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 0.2px;
        }
        .brand span {
            color: var(--muted);
            font-size: 13px;
        }
        nav a {
            color: var(--ink);
            text-decoration: none;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid transparent;
        }
        nav a:hover {
            border-color: var(--border);
            background: #faf7f2;
        }
        main {
            margin-top: 24px;
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 24px;
            box-shadow: var(--shadow);
        }
        h2 { margin: 0 0 16px; }
        .card {
            background: #fbfaf7;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 16px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid var(--border);
        }
        .table th { color: var(--muted); font-size: 13px; letter-spacing: 0.2px; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--border);
            background: #fff;
        }
        .badge.done { color: #1a7f37; border-color: #b7e1c1; background: #e9f7ee; }
        .badge.open { color: #9a3412; border-color: #f2c6b6; background: #fff3ec; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--ink);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn.primary {
            background: var(--accent);
            color: var(--accent-ink);
            border-color: transparent;
        }
        .btn.danger {
            background: #fff5f5;
            color: var(--danger);
            border-color: #f2b8b5;
        }
        .btn[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-top-color: rgba(255, 255, 255, 1);
            border-radius: 50%;
            display: inline-block;
            animation: spin 0.8s linear infinite;
        }
        .btn:not(.primary) .spinner {
            border-color: rgba(31, 35, 40, 0.2);
            border-top-color: rgba(31, 35, 40, 0.7);
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        form.inline { display: inline; }
        label { display: block; font-weight: 600; margin: 14px 0 6px; }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            font: inherit;
        }
        textarea { min-height: 110px; resize: vertical; }
        .error {
            color: var(--danger);
            background: #fff5f5;
            border: 1px solid #f2b8b5;
            padding: 10px 12px;
            border-radius: 10px;
        }
        .notice {
            padding: 10px 12px;
            border-radius: 10px;
            margin: 12px 0;
            font-weight: 600;
            position: relative;
            display: none;
        }
        .notice.success {
            color: #0f5132;
            background: #e9f7ee;
            border: 1px solid #b7e1c1;
        }
        .notice.error {
            color: var(--danger);
            background: #fff5f5;
            border: 1px solid #f2b8b5;
        }
        .notice .close {
            position: absolute;
            top: 6px;
            right: 8px;
            border: none;
            background: transparent;
            color: inherit;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            padding: 2px 6px;
        }
        .two-col {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
        }
        @media (max-width: 860px) {
            header.app-header { position: static; }
            .two-col { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="app-header">
        <div class="brand">
            <h1>Task Manager</h1>
            <span>Plan, track, and finish work</span>
        </div>
        <nav>
            <a href="/tasks">All Tasks</a>
            <a href="/tasks/create">New Task</a>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</div>
<script>
    (function () {
        function setMessage(container, message, type) {
            if (!container) return;
            var textEl = container.querySelector('[data-ajax-message-text]') || container;
            textEl.textContent = message;
            container.className = type ? 'notice ' + type : 'notice';
            container.style.display = message ? 'block' : 'none';
            if (message) {
                autoDismiss(container);
            }
        }

        function autoDismiss(container) {
            var timeout = parseInt(container.getAttribute('data-auto-dismiss'), 10);
            if (!timeout) return;
            if (container.dataset.dismissTimer) {
                clearTimeout(parseInt(container.dataset.dismissTimer, 10));
            }
            var id = setTimeout(function () {
                container.style.display = 'none';
            }, timeout);
            container.dataset.dismissTimer = id.toString();
        }

        document.querySelectorAll('[data-ajax-message-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var container = btn.closest('.notice');
                if (container) container.style.display = 'none';
            });
        });

        document.querySelectorAll('.notice').forEach(function (notice) {
            var textEl = notice.querySelector('[data-ajax-message-text]');
            var text = textEl ? textEl.textContent.trim() : notice.textContent.trim();
            if (text !== '') {
                notice.style.display = 'block';
                autoDismiss(notice);
            } else {
                notice.style.display = 'none';
            }
        });

        function setButtonLoading(button, loadingText) {
            if (!button) return;
            if (!button.dataset.originalText) {
                button.dataset.originalText = button.textContent;
            }
            if (loadingText) {
                button.innerHTML = '<span class="spinner" aria-hidden="true"></span> ' + loadingText;
                button.disabled = true;
            } else {
                button.textContent = button.dataset.originalText;
                button.disabled = false;
            }
        }

        document.querySelectorAll('form[data-ajax-form]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var message = form.querySelector('[data-ajax-message]');
                var submit = form.querySelector('button[type="submit"]');
                setMessage(message, '');
                setButtonLoading(submit, 'Saving...');

                fetch(form.action, {
                    method: form.method.toUpperCase(),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(async function (response) {
                    var data = await response.json().catch(function () { return {}; });
                    if (!response.ok) {
                        var msg = (data.errors && data.errors.title) ? data.errors.title : 'Validation error.';
                        setMessage(message, msg, 'error');
                        setButtonLoading(submit, '');
                        return;
                    }

                    setMessage(message, 'Saved successfully.', 'success');
                    if (form.dataset.resetOnSuccess === 'true') {
                        form.reset();
                    }
                    setButtonLoading(submit, '');
                })
                .catch(function () {
                    setMessage(message, 'Request failed. Please try again.', 'error');
                    setButtonLoading(submit, '');
                });
            });
        });

        document.querySelectorAll('form[data-ajax-delete]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var submit = form.querySelector('button[type="submit"]');
                setButtonLoading(submit, 'Deleting...');
                var message = document.querySelector('[data-ajax-global-message]');
                setMessage(message, '');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(function (response) {
                    if (!response.ok) throw new Error('Failed');
                    var row = form.closest('tr');
                    if (row) row.remove();
                    setMessage(message, 'Deleted successfully.', 'success');
                    setButtonLoading(submit, '');
                })
                .catch(function () {
                    setMessage(message, 'Delete failed. Please try again.', 'error');
                    setButtonLoading(submit, '');
                });
            });
        });
    })();
</script>
</body>
</html>
