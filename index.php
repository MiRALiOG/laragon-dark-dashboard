<?php

if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
        case 'info':
            $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
            $isLocal = in_array($remoteIp, ['127.0.0.1', '::1']);
            if ($isLocal) {
                phpinfo();
            } else {
                http_response_code(403);
                echo 'دسترسی فقط از localhost مجاز است. / Access allowed from localhost only.';
            }
            exit;
    }
}

function detectProjectType($path) {
    $checks = [
        'artisan'          => ['label' => 'Laravel',    'icon' => '🔴'],
        'wp-config.php'    => ['label' => 'WordPress',  'icon' => '🔵'],
        'wp-login.php'     => ['label' => 'WordPress',  'icon' => '🔵'],
        'symfony.lock'     => ['label' => 'Symfony',    'icon' => '⚫'],
        'angular.json'     => ['label' => 'Angular',    'icon' => '🔴'],
        'next.config.js'   => ['label' => 'Next.js',    'icon' => '⚫'],
        'vite.config.js'   => ['label' => 'Vite',       'icon' => '🟣'],
        'composer.json'    => ['label' => 'Composer',   'icon' => '🟠'],
        'package.json'     => ['label' => 'Node.js',    'icon' => '🟢'],
    ];

    foreach ($checks as $file => $info) {
        if (file_exists($path . '/' . $file)) {
            if ($file === 'composer.json') {
                $content = @file_get_contents($path . '/' . $file);
                if ($content && strpos($content, 'laravel/framework') !== false) {
                    return ['label' => 'Laravel', 'icon' => '🔴'];
                }
            }
            return $info;
        }
    }
    return null;
}

function getProjectFolders() {
    $path = __DIR__;
    $folders = [];

    foreach (new DirectoryIterator($path) as $item) {
        if (!$item->isDot() && $item->isDir() && $item->isReadable()) {
            $name = $item->getFilename();
            $hiddenFolders = ['.git', '.vscode', 'vendor', 'node_modules', '.idea', 'tmp', 'temp', 'cache'];

            if (!in_array($name, $hiddenFolders)) {
                $fullPath = $item->getPathname();
                $hasIndex = file_exists($fullPath . '/index.php') ||
                    file_exists($fullPath . '/index.html');
                $type = detectProjectType($fullPath);

                $folders[] = [
                    'name'     => $name,
                    'path'     => $fullPath,
                    'hasIndex' => $hasIndex,
                    'modified' => date('Y-m-d H:i:s', $item->getMTime()),
                    'type'     => $type,
                ];
            }
        }
    }

    usort($folders, function ($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });

    return $folders;
}

$documentRoot = __DIR__;
$projects = getProjectFolders();
$githubUrl = 'https://github.com/MiRALiOG/laragon-dark-dashboard';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="lang-fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laragon - مدیریت پروژه‌های محلی</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;900&family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        :root {
            --page-bg: radial-gradient(circle at top right, #1e2230 0%, #0d0f17 60%);
            --text-color: #d1d5db;
            --container-bg: #151823;
            --container-border: #262b3d;
            --info-bar-bg: #11131c;
            --info-bar-border: #262b3d;
            --info-item-color: #8b8fa3;
            --link-color: #a78bfa;
            --section-title-color: #e5e7eb;
            --search-bg: #0d0f17;
            --search-border: #2e3346;
            --search-color: #e5e7eb;
            --placeholder-color: #6b7280;
            --card-bg: #1a1d2b;
            --card-border: #262b3d;
            --card-hover-border: #7c3aed;
            --card-hover-shadow: rgba(124, 58, 237, 0.15);
            --project-name-color: #f3f4f6;
            --project-type-bg: #2e2150;
            --project-type-color: #c4b5fd;
            --status-active: #4ade80;
            --status-inactive: #fbbf24;
            --date-color: #6b7280;
            --footer-bg: #11131c;
            --footer-border: #262b3d;
            --footer-color: #8b8fa3;
            --empty-color: #6b7280;
            --btn-secondary-bg: rgba(167, 139, 250, 0.12);
            --btn-secondary-border: rgba(167, 139, 250, 0.3);
            --btn-secondary-hover-bg: rgba(167, 139, 250, 0.22);
            --btn-secondary-color: #e0d9ff;
        }

        body.light {
            --page-bg: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            --text-color: #374151;
            --container-bg: #ffffff;
            --container-border: #e5e7eb;
            --info-bar-bg: #f9fafb;
            --info-bar-border: #e5e7eb;
            --info-item-color: #6b7280;
            --link-color: #7c3aed;
            --section-title-color: #1f2937;
            --search-bg: #ffffff;
            --search-border: #d1d5db;
            --search-color: #1f2937;
            --placeholder-color: #9ca3af;
            --card-bg: #f9fafb;
            --card-border: #e5e7eb;
            --card-hover-border: #7c3aed;
            --card-hover-shadow: rgba(124, 58, 237, 0.12);
            --project-name-color: #111827;
            --project-type-bg: #ede9fe;
            --project-type-color: #6d28d9;
            --status-active: #16a34a;
            --status-inactive: #b45309;
            --date-color: #9ca3af;
            --footer-bg: #f9fafb;
            --footer-border: #e5e7eb;
            --footer-color: #6b7280;
            --empty-color: #9ca3af;
            --btn-secondary-bg: rgba(124, 58, 237, 0.08);
            --btn-secondary-border: rgba(124, 58, 237, 0.25);
            --btn-secondary-hover-bg: rgba(124, 58, 237, 0.16);
            --btn-secondary-color: #6d28d9;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Vazirmatn', 'Inter', sans-serif;
            background: var(--page-bg);
            color: var(--text-color);
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        html.lang-en body {
            font-family: 'Inter', 'Vazirmatn', sans-serif;
        }

        html.lang-en .i18n-fa {
            display: none;
        }

        html.lang-fa .i18n-en {
            display: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--container-bg);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            border: 1px solid var(--container-border);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .header {
            background: linear-gradient(135deg, #6d28d9 0%, #2c1e52 100%);
            color: #f3f0ff;
            padding: 40px;
            text-align: center;
            position: relative;
            border-bottom: 1px solid #2e2545;
        }

        .title {
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #e9e2ff, #c4b5fd);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .subtitle {
            font-size: 16px;
            color: #d7d0f5;
        }

        .header-actions {
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 10px;
        }

        .header-controls-top {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 10px;
        }

        .btn-phpmyadmin,
        .btn-github {
            background: rgba(255, 255, 255, 0.12);
            color: #f3f0ff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-phpmyadmin:hover,
        .btn-github:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-icon {
            background: rgba(255, 255, 255, 0.12);
            color: #f3f0ff;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            cursor: pointer;
            font-size: 15px;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .info-bar {
            background: var(--info-bar-bg);
            padding: 15px 40px;
            border-bottom: 1px solid var(--info-bar-border);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 14px;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .info-item {
            color: var(--info-item-color);
        }

        .info-item a {
            color: var(--link-color);
            text-decoration: none;
        }

        .info-item a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-title {
            font-size: 24px;
            color: var(--section-title-color);
            padding-bottom: 10px;
            border-bottom: 2px solid #a78bfa;
            display: inline-block;
        }

        .header-controls {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--search-border);
            background: var(--search-bg);
            color: var(--search-color);
            font-family: inherit;
            font-size: 14px;
            width: 220px;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .search-box::placeholder {
            color: var(--placeholder-color);
        }

        .search-box:focus {
            border-color: #a78bfa;
        }

        .btn-add-project {
            background: #7c3aed;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        .btn-add-project:hover {
            background: #6d28d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 58, 237, 0.4);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .project-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 1px solid var(--card-border);
            text-decoration: none;
            display: block;
            color: inherit;
            position: relative;
            cursor: pointer;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--card-hover-shadow);
            border-color: var(--card-hover-border);
        }

        .project-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .project-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--project-name-color);
            margin-bottom: 8px;
            word-break: break-all;
        }

        .project-type {
            display: inline-block;
            font-size: 11px;
            background: var(--project-type-bg);
            color: var(--project-type-color);
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .project-status {
            font-size: 12px;
            color: var(--status-active);
            margin-bottom: 10px;
        }

        .project-status.inactive {
            color: var(--status-inactive);
        }

        .project-date {
            font-size: 11px;
            color: var(--date-color);
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: var(--empty-color);
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .footer {
            background: var(--footer-bg);
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid var(--footer-border);
            color: var(--footer-color);
            font-size: 14px;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .footer a {
            color: var(--link-color);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .header {
                padding: 30px 20px;
            }

            .title {
                font-size: 32px;
            }

            .content {
                padding: 20px;
            }

            .info-bar {
                flex-direction: column;
                padding: 15px 20px;
            }

            .header-actions,
            .header-controls-top {
                position: static;
                transform: none;
                justify-content: center;
                margin-top: 15px;
            }

            .btn-phpmyadmin,
            .btn-github {
                padding: 8px 16px;
                font-size: 12px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-controls-top">
                <button type="button" class="btn-icon" id="themeToggleBtn" onclick="toggleTheme()" title="تغییر تم / Toggle theme">🌙</button>
                <button type="button" class="btn-icon" id="langToggleBtn" onclick="toggleLanguage()" title="تغییر زبان / Change language">EN</button>
            </div>

            <div class="title">🚀 Laragon</div>
            <div class="subtitle">
                <span class="i18n-fa">محیط توسعه محلی سریع و قدرتمند</span>
                <span class="i18n-en">Fast &amp; powerful local development environment</span>
            </div>

            <div class="header-actions">
                <a href="/phpmyadmin" target="_blank" class="btn-phpmyadmin">
                    <span>🐬</span> phpMyAdmin
                </a>
                <a href="<?= htmlspecialchars($githubUrl) ?>" target="_blank" class="btn-github">
                    <span>🐙</span>
                    <span class="i18n-fa">مشاهده در گیت‌هاب</span>
                    <span class="i18n-en">View on GitHub</span>
                </a>
            </div>
        </div>

        <div class="info-bar">
            <div class="info-item">🖥️ <?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?></div>
            <div class="info-item">
                🐘
                <span class="i18n-fa">نسخه‌ی PHP:</span><span class="i18n-en">PHP version:</span>
                <?= phpversion() ?>
                <a href="/?q=info" title="phpinfo()">📄 <span class="i18n-fa">اطلاعات</span><span class="i18n-en">info</span></a>
            </div>
            <div class="info-item">
                📂 <span class="i18n-fa">مسیر ریشه:</span><span class="i18n-en">Document Root:</span>
                <?= htmlspecialchars($documentRoot) ?>
            </div>
            <div class="info-item">
                📦 <span class="i18n-fa">تعداد پروژه‌ها:</span><span class="i18n-en">Projects:</span>
                <?= count($projects) ?>
            </div>
        </div>

        <div class="content">
            <div class="section-header">
                <div class="section-title">
                    📁
                    <span class="i18n-fa">پروژه‌های محلی شما</span>
                    <span class="i18n-en">Your Local Projects</span>
                </div>
                <div class="header-controls">
                    <input type="text" class="search-box" id="searchInput" oninput="filterProjects()">
                    <button type="button" class="btn-add-project" onclick="newProjectAlert()">
                        ➕
                        <span class="i18n-fa">پروژه جدید</span>
                        <span class="i18n-en">New Project</span>
                    </button>
                </div>
            </div>

            <?php if (count($projects) > 0): ?>
                <div class="projects-grid" id="projectsGrid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card" data-name="<?= htmlspecialchars(strtolower($project['name'])) ?>" onclick="window.location.href='/<?= urlencode($project['name']) ?>'">
                            <div class="project-icon">
                                <?= $project['hasIndex'] ? '📁' : '📂' ?>
                            </div>
                            <div class="project-name"><?= htmlspecialchars($project['name']) ?></div>
                            <?php if ($project['type']): ?>
                                <div class="project-type"><?= $project['type']['icon'] ?> <?= htmlspecialchars($project['type']['label']) ?></div>
                            <?php endif; ?>
                            <div class="project-status <?= $project['hasIndex'] ? '' : 'inactive' ?>">
                                <?php if ($project['hasIndex']): ?>
                                    <span class="i18n-fa">✅ فایل index دارد</span>
                                    <span class="i18n-en">✅ Has index file</span>
                                <?php else: ?>
                                    <span class="i18n-fa">⚠️ فایل index ندارد</span>
                                    <span class="i18n-en">⚠️ No index file</span>
                                <?php endif; ?>
                            </div>
                            <div class="project-date">
                                🕒
                                <span class="i18n-fa">آخرین تغییر:</span>
                                <span class="i18n-en">Last modified:</span>
                                <?= $project['modified'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="empty-state" id="noResults" style="display:none;">
                    <div class="empty-state-icon">🔍</div>
                    <div>
                        <span class="i18n-fa">پروژه‌ای با این نام پیدا نشد</span>
                        <span class="i18n-en">No project found with this name</span>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📂</div>
                    <div>
                        <span class="i18n-fa">هیچ پروژه‌ای یافت نشد</span>
                        <span class="i18n-en">No projects found</span>
                    </div>
                    <div style="font-size: 14px; margin-top: 10px;">
                        <span class="i18n-fa">پوشه‌های جدید را در</span>
                        <span class="i18n-en">Create new folders in</span>
                        <code><?= htmlspecialchars($documentRoot) ?></code>
                        <span class="i18n-fa">ایجاد کنید</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <div>
                💡
                <span class="i18n-fa">نکته: برای شروع یک پروژه جدید، یک پوشه در مسیر بالا ایجاد کنید و فایل index.php را در آن قرار دهید</span>
                <span class="i18n-en">Tip: to start a new project, create a folder in the path above and place an index.php file inside it</span>
            </div>
            <div style="margin-top: 5px; font-size: 12px;">
                🔗 <a href="/phpmyadmin" target="_blank">phpMyAdmin</a> |
                🐙 <a href="<?= htmlspecialchars($githubUrl) ?>" target="_blank">
                    <span class="i18n-fa">مخزن گیت‌هاب</span><span class="i18n-en">GitHub Repo</span>
                </a> |
                📚 <a href="https://laragon.org/docs/" target="_blank">
                    <span class="i18n-fa">مستندات Laragon</span><span class="i18n-en">Laragon Docs</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        const documentRootPath = <?= json_encode($documentRoot) ?>;
        const titles = {
            fa: 'Laragon - مدیریت پروژه‌های محلی',
            en: 'Laragon - Local Project Dashboard'
        };
        const searchPlaceholders = {
            fa: '🔍 جستجوی پروژه...',
            en: '🔍 Search projects...'
        };
        const newProjectMessages = {
            fa: 'یک پوشه جدید در مسیر زیر ایجاد کنید:\n' + documentRootPath,
            en: 'Create a new folder in the path below:\n' + documentRootPath
        };

        function filterProjects() {
            const query = document.getElementById('searchInput').value.trim().toLowerCase();
            const cards = document.querySelectorAll('.project-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const match = name.includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        function newProjectAlert() {
            const lang = document.documentElement.classList.contains('lang-en') ? 'en' : 'fa';
            alert(newProjectMessages[lang]);
        }

        function currentLang() {
            return document.documentElement.classList.contains('lang-en') ? 'en' : 'fa';
        }

        function applyLanguage(lang) {
            const html = document.documentElement;
            if (lang === 'en') {
                html.classList.remove('lang-fa');
                html.classList.add('lang-en');
                html.setAttribute('lang', 'en');
                html.setAttribute('dir', 'ltr');
                document.getElementById('langToggleBtn').textContent = 'EN';
            } else {
                html.classList.remove('lang-en');
                html.classList.add('lang-fa');
                html.setAttribute('lang', 'fa');
                html.setAttribute('dir', 'rtl');
                document.getElementById('langToggleBtn').textContent = 'فا';
            }
            document.title = titles[lang];
            document.getElementById('searchInput').placeholder = searchPlaceholders[lang];
        }

        function toggleLanguage() {
            const next = currentLang() === 'fa' ? 'en' : 'fa';
            applyLanguage(next);
            localStorage.setItem('dashboardLang', next);
        }

        function applyTheme(theme) {
            const themeIcon = document.getElementById('themeToggleBtn');
            if (theme === 'light') {
                document.body.classList.add('light');
                themeIcon.textContent = '☀️';
            } else {
                document.body.classList.remove('light');
                themeIcon.textContent = '🌙';
            }
        }

        function toggleTheme() {
            const isLight = document.body.classList.contains('light');
            const next = isLight ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem('dashboardTheme', next);
        }

        (function init() {
            const savedLang = localStorage.getItem('dashboardLang') || 'fa';
            const savedTheme = localStorage.getItem('dashboardTheme') || 'dark';
            applyLanguage(savedLang);
            applyTheme(savedTheme);
        })();
    </script>
</body>

</html>
