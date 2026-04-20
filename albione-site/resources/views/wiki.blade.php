<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.site.name') }}</title>
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
        .layout-grid {
            display: grid;
            gap: 24px;
            margin-top: 28px;
            justify-items: center;
        }
        .layout-grid { display: grid; gap: 24px; margin-top: 28px; }
        .sigils {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            width: 100%;
            max-width: 960px;
            justify-items: center;
        }
        .sigil-card {
            position: relative;
            padding: 18px 16px;
            border-radius: 16px;
            background: linear-gradient(150deg, rgba(16,23,35,0.95), rgba(13,19,31,0.8));
            border: 1px solid var(--line);
            box-shadow: 0 14px 30px var(--shadow);
            overflow: hidden;
            width: 100%;
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
            width: 100%;
            max-width: 900px;
            justify-self: center;
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
        .content-body {
            margin-top: 16px;
            display: grid;
            gap: 14px;
        }
        .info-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            background: linear-gradient(120deg, rgba(255,255,255,0.02), rgba(13,19,31,0.8));
            box-shadow: 0 10px 26px var(--shadow);
            display: grid;
            gap: 10px;
        }
        .info-card__header {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .info-card__title {
            margin: 0;
            font-size: 17px;
            color: #fff;
        }
        .info-card__meta {
            color: var(--muted);
            font-size: 14px;
        }
        .info-card__img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 8px;
        }
        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .pill {
            padding: 6px 10px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 13px;
            color: var(--text);
            letter-spacing: 0.01em;
        }
        .branch-grid, .gallery-grid {
            display: grid;
            gap: 12px;
        }
        .gallery-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .gallery-grid img {
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--line);
        }
        .variants-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .variant {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 10px;
            display: grid;
            gap: 6px;
            background: rgba(255,255,255,0.03);
            min-width: 180px;
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
        .cta-stack {
            display: grid;
            gap: 12px;
            width: 100%;
            max-width: 1080px;
            margin-top: 12px;
        }
        .cta-bar {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
            width: 100%;
        }
        .cta-admin {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .cta-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 2px solid var(--line);
            background: linear-gradient(140deg, rgba(242,200,124,0.14), rgba(16,23,35,0.9));
            color: #fff;
            font-weight: 650;
            letter-spacing: 0.02em;
            text-decoration: none;
            box-shadow: 0 10px 26px var(--shadow);
            transition: 160ms ease;
            text-align: center;
        }
        .cta-link--admin {
            min-width: 240px;
            justify-content: center;
            text-align: center;
        }
        .cta-link:hover { border-color: var(--gold-strong); transform: translateY(-1px); box-shadow: 0 12px 30px rgba(242,200,124,0.22); }
        .topbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 18px;
        }
        .locale-switcher {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: rgba(13,19,31,0.78);
            box-shadow: 0 10px 24px var(--shadow);
        }
        .locale-switcher a {
            padding: 8px 10px;
            border-radius: 10px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.06em;
            border: 1px solid transparent;
        }
        .locale-switcher a.is-active {
            color: #fff;
            border-color: var(--gold-strong);
            background: rgba(242,200,124,0.12);
        }
        @media (max-width: 700px) {
            .tab-bar { top: 10px; grid-template-columns: 1fr; max-width: 420px; }
            .tab { font-size: 14px; padding: 12px 14px; min-height: 70px; }
            .sigil-title { font-size: 20px; }
            .cta-bar { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="topbar">
        <div class="locale-switcher" aria-label="{{ __('ui.site.language') }}">
            @foreach(['en', 'ru'] as $locale)
                <a href="{{ route('locale.switch', ['locale' => $locale]) }}" class="{{ app()->getLocale() === $locale ? 'is-active' : '' }}">{{ __('ui.locales.'.$locale) }}</a>
            @endforeach
        </div>
    </div>
    <header class="hero">
        <div class="hero__crest">{{ __('ui.wiki.crest') }}</div>
        <h1 class="hero__title">{{ __('ui.wiki.hero_title') }}</h1>
        <p class="hero__subtitle">{{ __('ui.wiki.hero_subtitle') }}</p>

        <div id="tabBar" class="tab-bar">
            <button class="tab active" data-tab="mobs">{{ __('ui.wiki.tabs.mobs.label') }} <span>{{ __('ui.wiki.tabs.mobs.sub') }}</span></button>
            <button class="tab" data-tab="gear">{{ __('ui.wiki.tabs.gear.label') }} <span>{{ __('ui.wiki.tabs.gear.sub') }}</span></button>
            <button class="tab" data-tab="content">{{ __('ui.wiki.tabs.content.label') }} <span>{{ __('ui.wiki.tabs.content.sub') }}</span></button>
            <button class="tab" data-tab="builds">{{ __('ui.wiki.tabs.builds.label') }} <span>{{ __('ui.wiki.tabs.builds.sub') }}</span></button>
        </div>

        <div class="cta-stack">
            <nav class="cta-bar" aria-label="{{ __('ui.wiki.quick_nav') }}">
                <a class="cta-link" href="{{ route('mobs.index') }}">{{ __('ui.nav.mobs') }}</a>
                <a class="cta-link" href="{{ route('weapon-lines.index') }}">{{ __('ui.nav.weapon_lines') }}</a>
                <a class="cta-link" href="{{ route('weapons.index') }}">{{ __('ui.nav.weapons') }}</a>
                <a class="cta-link" href="{{ route('armor.index') }}">{{ __('ui.nav.armor') }}</a>
                <a class="cta-link" href="{{ route('wiki') }}#builds">{{ __('ui.wiki.builds') }}</a>
            </nav>
            @if(Auth::user()?->is_admin)
                <div class="cta-admin">
                    <a class="cta-link cta-link--admin" href="{{ route('admin.dashboard') }}">{{ __('ui.wiki.admin') }}</a>
                </div>
            @endif
        </div>
    </header>

    <main class="layout-grid">
        <section class="sigils" aria-label="{{ __('ui.wiki.guild.heroes') }}">
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
                <h3 id="contentTitle">{{ __('ui.wiki.panels.mobs_title') }}</h3>
                <p id="contentText">{{ __('ui.wiki.panels.mobs_text') }}</p>
            </div>
            <div class="content-actions" id="contentActions">
                <span class="chip">{{ __('ui.wiki.chips.mobs_category') }}</span>
                <span class="chip">{{ __('ui.wiki.chips.mobs_style') }}</span>
            </div>
            <div id="contentArea" class="content-body" aria-label="Динамический контент"></div>
            <div class="scroll-hint">{{ __('ui.wiki.scroll_hint') }}</div>
        </section>
    </main>
</div>

<script>
    const wikiText = {
        qSkills: @json(__('ui.wiki.q_skills')),
        wSkills: @json(__('ui.wiki.w_skills')),
        passive: @json(__('ui.wiki.passive')),
        mobsTitle: @json(__('ui.wiki.panels.mobs_title')),
        mobsText: @json(__('ui.wiki.panels.mobs_text')),
        mobsChips: @json([__('ui.wiki.chips.mobs_category'), __('ui.wiki.chips.mobs_style')]),
        gearTitle: @json(__('ui.wiki.panels.gear_title')),
        gearText: @json(__('ui.wiki.panels.gear_text')),
        gearChips: @json([__('ui.wiki.chips.gear_category'), __('ui.wiki.chips.gear_style')]),
        contentTitle: @json(__('ui.wiki.panels.content_title')),
        contentText: @json(__('ui.wiki.panels.content_text')),
        contentChips: @json([__('ui.wiki.chips.content_category'), __('ui.wiki.chips.content_style')]),
        buildsTitle: @json(__('ui.wiki.panels.builds_title')),
        buildsText: @json(__('ui.wiki.panels.builds_text')),
        buildsChips: @json([__('ui.wiki.chips.builds_category'), __('ui.wiki.chips.builds_style')]),
    };

    const mobsData = @json($mobs);
    const gearData = {
        branches: @json($weaponBranches),
        armors: @json($armors)
    };
    const contentsData = @json($contents);
    const buildsData = @json($builds);

    const tabs = document.querySelectorAll('.tab');
    const contentTitle = document.getElementById('contentTitle');
    const contentText = document.getElementById('contentText');
    const contentActions = document.getElementById('contentActions');
    const contentArea = document.getElementById('contentArea');

    const escapeHtml = (str) => (str || '').replace(/[&<>'"]/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;',
    })[char]);

    const renderMobs = () => mobsData.map(mob => `
        <article class="info-card">
            <div class="info-card__header">
                <img src="${escapeHtml(mob.image)}" alt="${escapeHtml(mob.name)}" class="info-card__img" loading="lazy">
                <div>
                    <h4 class="info-card__title">${escapeHtml(mob.name)}</h4>
                    <div class="info-card__meta">${escapeHtml(mob.tier_range)}</div>
                </div>
            </div>
            <p class="content-placeholder__text">${escapeHtml(mob.description)}</p>
            <div class="pill-row">${mob.skills.map(skill => `<span class="pill">${escapeHtml(skill)}</span>`).join('')}</div>
        </article>
    `).join('');

    const renderGear = () => {
        const branches = gearData.branches.map(branch => `
            <article class="info-card">
                <div class="info-card__header">
                    <div class="pill">${escapeHtml(branch.icon)}</div>
                    <div>
                        <h4 class="info-card__title">${escapeHtml(branch.name)}</h4>
                        <div class="info-card__meta">${escapeHtml(branch.summary)}</div>
                    </div>
                </div>
                <div class="branch-grid">
                    <div><strong>${escapeHtml(wikiText.qSkills)}:</strong> ${branch.q_skills.map(skill => escapeHtml(skill.name)).join(', ')}</div>
                    <div><strong>${escapeHtml(wikiText.wSkills)}:</strong> ${branch.w_skills.map(skill => escapeHtml(skill.name)).join(', ')}</div>
                    <div><strong>${escapeHtml(wikiText.passive)}:</strong> ${escapeHtml(branch.passive.name)}</div>
                </div>
                <div class="variants-row">
                    ${branch.variants.map(variant => `
                        <div class="variant">
                            <div class="info-card__meta">${escapeHtml(variant.role)}</div>
                            <div class="info-card__title">${escapeHtml(variant.name)}</div>
                        </div>
                    `).join('')}
                </div>
            </article>
        `).join('');

        const armors = gearData.armors.map(armor => `
            <article class="info-card">
                <div class="info-card__header">
                    <div class="pill">${escapeHtml(armor.type)}</div>
                    <h4 class="info-card__title">${escapeHtml(armor.name)}</h4>
                </div>
                <p class="content-placeholder__text">${escapeHtml(armor.description)}</p>
            </article>
        `).join('');

        return branches + armors;
    };

    const renderContents = () => contentsData.map(entry => `
        <article class="info-card">
            <div class="info-card__header">
                <img src="${escapeHtml(entry.icon)}" alt="${escapeHtml(entry.name)}" class="info-card__img" loading="lazy">
                <div>
                    <h4 class="info-card__title">${escapeHtml(entry.name)}</h4>
                    <div class="info-card__meta">${escapeHtml(entry.description)}</div>
                </div>
            </div>
            <div class="gallery-grid">
                ${entry.gallery.map(src => `<img src="${escapeHtml(src)}" alt="${escapeHtml(entry.name)}" loading="lazy">`).join('')}
            </div>
        </article>
    `).join('');

    const renderBuilds = () => buildsData.map(build => `
        <article class="info-card">
            <h4 class="info-card__title">${escapeHtml(build.name)}</h4>
            <p class="content-placeholder__text">${escapeHtml(build.description)}</p>
            <div class="pill-row">${build.tags.map(tag => `<span class="pill">${escapeHtml(tag)}</span>`).join('')}</div>
        </article>
    `).join('');

    const tabContent = {
        mobs: {
            title: wikiText.mobsTitle,
            text: wikiText.mobsText,
            chips: wikiText.mobsChips,
            renderer: renderMobs,
        },
        gear: {
            title: wikiText.gearTitle,
            text: wikiText.gearText,
            chips: wikiText.gearChips,
            renderer: renderGear,
        },
        content: {
            title: wikiText.contentTitle,
            text: wikiText.contentText,
            chips: wikiText.contentChips,
            renderer: renderContents,
        },
        builds: {
            title: wikiText.buildsTitle,
            text: wikiText.buildsText,
            chips: wikiText.buildsChips,
            renderer: renderBuilds,
        },
    };

    const setActiveTab = (tab) => {
        tabs.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === tab));
        const data = tabContent[tab];
        if (!data) return;
        contentTitle.textContent = data.title;
        contentText.textContent = data.text;
        contentActions.innerHTML = data.chips.map(chip => `<span class="chip">${chip}</span>`).join('');
        contentArea.innerHTML = data.renderer ? data.renderer() : '';
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
