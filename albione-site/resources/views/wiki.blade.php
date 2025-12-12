<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albion Codex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            --bg: #0c0f17;
            --bg-2: #0f1624;
            --gold: #d7b676;
            --gold-strong: #f2c87c;
            --text: #e5e7eb;
            --muted: #b6c2cf;
            --panel: #0d131f;
            --shadow: rgba(0, 0, 0, 0.45);
            --line: rgba(215, 182, 118, 0.35);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: radial-gradient(circle at 18% 22%, rgba(215,182,118,0.08), transparent 35%),
                        radial-gradient(circle at 82% 12%, rgba(255,189,89,0.08), transparent 32%),
                        linear-gradient(145deg, var(--bg) 0%, var(--bg-2) 100%);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }
        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px 72px;
            position: relative;
        }
        .page::before {
            content: "";
            position: absolute;
            inset: 18% auto 10% 5%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(242,200,124,0.16), transparent 70%);
            filter: blur(8px);
            pointer-events: none;
        }
        header.hero {
            text-align: center;
            display: grid;
            gap: 16px;
            justify-items: center;
        }
        .hero__crest {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(13,19,31,0.7);
            box-shadow: 0 10px 30px var(--shadow);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--gold-strong);
        }
        .hero__title {
            font-family: 'Cinzel', 'Inter', serif;
            margin: 0;
            font-size: clamp(28px, 4vw, 42px);
            letter-spacing: 0.02em;
            text-shadow: 0 4px 12px rgba(0,0,0,0.45);
        }
        .hero__subtitle {
            margin: 0;
            max-width: 780px;
            color: var(--muted);
            line-height: 1.6;
        }
        .tab-bar {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(2, minmax(170px, 1fr));
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
            padding: 12px;
            background: linear-gradient(120deg, rgba(13,19,31,0.9), rgba(13,19,31,0.75));
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 12px 36px var(--shadow);
            max-width: 560px;
            width: 100%;
            justify-self: center;
        }
        .tab {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px 16px 12px;
            background: radial-gradient(circle at 20% 18%, rgba(242,200,124,0.08), rgba(16,23,35,0.9));
            color: var(--text);
            font-weight: 700;
            letter-spacing: 0.02em;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            cursor: pointer;
            transition: 180ms ease;
            text-align: left;
            min-height: 78px;
            position: relative;
            overflow: hidden;
        }
        .tab::after {
            content: "";
            position: absolute;
            inset: 6px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.03);
            opacity: 0;
            transition: 180ms ease;
        }
        .tab span { color: var(--muted); font-size: 13px; font-weight: 600; }
        .tab.active {
            border-color: var(--gold-strong);
            box-shadow: 0 10px 26px rgba(242,200,124,0.22), 0 0 0 1px rgba(242,200,124,0.2) inset;
            background: linear-gradient(140deg, rgba(242,200,124,0.18), rgba(16,23,35,0.96));
            color: #fff;
        }
        .tab.active::after { opacity: 1; }
        .tab:hover { border-color: var(--gold); transform: translateY(-1px); }
        .layout-grid { display: grid; gap: 24px; margin-top: 28px; }
        .sigils {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .sigil-card {
            position: relative;
            padding: 18px 16px;
            border-radius: 16px;
            background: linear-gradient(150deg, rgba(16,23,35,0.95), rgba(13,19,31,0.8));
            border: 1px solid var(--line);
            box-shadow: 0 14px 30px var(--shadow);
            overflow: hidden;
        }
        .sigil-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 20%, rgba(242,200,124,0.12), transparent 45%);
            opacity: 0.8;
            pointer-events: none;
        }
        .sigil-title {
            font-family: 'Cinzel', 'Inter', serif;
            font-size: 22px;
            margin: 6px 0 4px;
            color: var(--gold-strong);
            text-shadow: 0 4px 12px rgba(242,200,124,0.15);
        }
        .sigil-name {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.03em;
            color: #fff;
        }
        .sigil-rune {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.04);
            color: var(--gold);
            letter-spacing: 0.08em;
            font-weight: 700;
            text-transform: uppercase;
        }
        .content-panel {
            position: relative;
            border-radius: 18px;
            padding: 20px;
            background: linear-gradient(160deg, rgba(13,19,31,0.95), rgba(11,16,25,0.92));
            border: 1px solid var(--line);
            box-shadow: 0 14px 34px var(--shadow);
            overflow: hidden;
        }
        .content-panel::before {
            content: "";
            position: absolute;
            inset: 14px;
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 14px;
            pointer-events: none;
        }
        .content-placeholder h3 {
            margin: 0 0 6px;
            font-size: 20px;
            font-family: 'Cinzel', 'Inter', serif;
            color: var(--gold);
        }
        .content-placeholder p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }
        .content-actions {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .chip {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.03);
            color: var(--text);
            font-size: 14px;
            letter-spacing: 0.02em;
        }
        .scroll-hint {
            margin-top: 10px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .scroll-hint::before {
            content: "⇣";
            color: var(--gold-strong);
        }
        @media (max-width: 700px) {
            .tab-bar { top: 10px; grid-template-columns: 1fr; max-width: 420px; }
            .tab { font-size: 14px; padding: 12px 14px; min-height: 70px; }
            .sigil-title { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="hero">
        <div class="hero__crest">Albion Codex — Дом Русской Гильдии</div>
        <h1 class="hero__title">Главная страница фанатской wiki Albion Online</h1>
        <p class="hero__subtitle">Тёмная атмосфера средневековья, быстрые разделы и герои, которых ждут новые легенды. Навигация остаётся под рукой — выберите вкладку, чтобы загрузить нужную часть базы знаний.</p>

        <div id="tabBar" class="tab-bar">
            <button class="tab active" data-tab="mobs">Мобы <span>Угроза земель</span></button>
            <button class="tab" data-tab="gear">Снаряжение <span>Сталь и ткань</span></button>
            <button class="tab" data-tab="content">Контент <span>Приключения</span></button>
            <button class="tab" data-tab="builds">Билды <span>Тактика</span></button>
        </div>
    </header>

    <main class="layout-grid">
        <section class="sigils" aria-label="Герои гильдии">
            <div class="sigil-card">
                <div class="sigil-rune">Rune • North</div>
                <h3 class="sigil-title">Xaxalka</h3>
                <p class="sigil-name">Мастер мечей и ночных вылазок. Лидер охоты на закате.</p>
            </div>
            <div class="sigil-card">
                <div class="sigil-rune">Rune • East</div>
                <h3 class="sigil-title">Nothing1231</h3>
                <p class="sigil-name">Следопыт дорожных катакомб, чует серебро за стенами тумана.</p>
            </div>
            <div class="sigil-card">
                <div class="sigil-rune">Sigil • West</div>
                <h3 class="sigil-title">@Xaxalka</h3>
                <p class="sigil-name">Глашатай гильдии, передающий весть через свитки и хроники.</p>
            </div>
            <div class="sigil-card">
                <div class="sigil-rune">Sigil • South</div>
                <h3 class="sigil-title">@Noname_12312</h3>
                <p class="sigil-name">Алхимик тени: делится рецептами, усиливающими силы братства.</p>
            </div>
        </section>

        <section class="content-panel" aria-live="polite">
            <div class="content-placeholder">
                <h3 id="contentTitle">Мобы — обзор угроз</h3>
                <p id="contentText">Выберите раздел для просмотра информации: данные загрузятся в эту область и сохранят атмосферу темного Albion Online.</p>
            </div>
            <div class="content-actions" id="contentActions">
                <span class="chip">Категория: Мобы</span>
                <span class="chip">Стиль: Темное фэнтези</span>
            </div>
            <div class="scroll-hint">Навигация закрепится сверху при прокрутке.</div>
        </section>
    </main>
</div>

<script>
    const tabs = document.querySelectorAll('.tab');
    const contentTitle = document.getElementById('contentTitle');
    const contentText = document.getElementById('contentText');
    const contentActions = document.getElementById('contentActions');

    const tabContent = {
        mobs: {
            title: 'Мобы — обзор угроз',
            text: 'Выберите раздел для просмотра информации. Здесь появятся подборки боссов, рейдовых монстров и их умения.',
            chips: ['Категория: Мобы', 'Тактика: Контроль и уклонение'],
        },
        gear: {
            title: 'Снаряжение — кузница силы',
            text: 'Просматривайте уникальные сетовые бонусы, сравнивайте артефактные предметы и собирайте собственные комплекты.',
            chips: ['Категория: Снаряжение', 'Стили: Пластинa, кожа, ткань'],
        },
        content: {
            title: 'Контент — где искать славу',
            text: 'Данжи, дороги Авалона, вторжения и особые события появятся в этом блоке с картами и мини-галереей.',
            chips: ['Категория: Контент', 'Режимы: PvE и PvP'],
        },
        builds: {
            title: 'Билды — стратегии и роли',
            text: 'Фильтруйте по ролям и активности: соло PvP, группы, или масштабные ZvZ. Заглушка готова принять ваши сетапы.',
            chips: ['Категория: Билды', 'Фокус: Роли и навыки'],
        },
    };

    const setActiveTab = (tab) => {
        tabs.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === tab));
        const data = tabContent[tab];
        if (!data) return;
        contentTitle.textContent = data.title;
        contentText.textContent = data.text;
        contentActions.innerHTML = data.chips.map(chip => `<span class="chip">${chip}</span>`).join('');
    };

    tabs.forEach(btn => btn.addEventListener('click', () => setActiveTab(btn.dataset.tab)));
    setActiveTab('mobs');

    const tabBar = document.getElementById('tabBar');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            tabBar.classList.toggle('is-stuck', !entry.isIntersecting);
        });
    }, { threshold: 1 });

    observer.observe(tabBar);
</script>
</body>
</html>
