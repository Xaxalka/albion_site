<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albion Wiki Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --bg: #0b1220;
            --panel: #0f172a;
            --muted: #94a3b8;
            --primary: #fbbf24;
            --card: #111827;
            --border: #1f2937;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: radial-gradient(circle at 10% 20%, #1f2937, var(--bg) 55%); color: #e2e8f0; }
        header { padding: 32px 5vw 12px; display: flex; gap: 18px; align-items: center; justify-content: space-between; position: sticky; top: 0; background: rgba(11,18,32,0.9); backdrop-filter: blur(8px); border-bottom: 1px solid var(--border); z-index: 20; }
        header h1 { margin: 0; font-size: 26px; letter-spacing: .01em; }
        header nav { display: flex; gap: 12px; flex-wrap: wrap; }
        header a { color: #e5e7eb; text-decoration: none; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--border); background: #0c1524; transition: 150ms ease; }
        header a:hover { border-color: var(--primary); color: #fff; }
        main { padding: 10px 5vw 64px; display: flex; flex-direction: column; gap: 48px; }
        section { background: var(--panel); border: 1px solid var(--border); border-radius: 14px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        h2 { margin: 0 0 12px; font-size: 22px; }
        .section-desc { margin: 0 0 18px; color: var(--muted); }
        .grid { display: grid; gap: 14px; }
        .mob-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
        .content-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 14px; position: relative; transition: transform 140ms ease, border-color 140ms ease; }
        .card:hover { transform: translateY(-2px); border-color: var(--primary); }
        .card h3 { margin: 0 0 6px; font-size: 16px; }
        .card p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.5; }
        .mob-thumb { width: 100%; height: 140px; object-fit: contain; display: block; margin-bottom: 8px; background: #0a1220; border-radius: 10px; }
        .badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(251,191,36,0.15); color: var(--primary); border: 1px solid rgba(251,191,36,0.4); padding: 4px 10px; border-radius: 999px; font-size: 12px; }
        .pill { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 8px; background: #111827; border: 1px solid var(--border); color: #cbd5e1; font-size: 12px; }
        .skills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .flex { display: flex; gap: 16px; }
        .flex-wrap { flex-wrap: wrap; }
        .divider { height: 1px; background: var(--border); margin: 12px 0; }
        .weapon-branches { display: grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap: 12px; }
        .branch-card { cursor: pointer; }
        .branch-card.active { border-color: var(--primary); box-shadow: 0 6px 18px rgba(251,191,36,0.15); }
        .weapon-detail { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-top: 16px; }
        .ability-list { display: grid; gap: 10px; }
        .ability { padding: 10px; border-radius: 10px; border: 1px solid var(--border); background: #0f172a; cursor: pointer; }
        .variants { display: flex; gap: 12px; flex-wrap: wrap; }
        .variant { width: 180px; }
        .variant img { width: 100%; height: 120px; object-fit: contain; background: #0a1220; border-radius: 10px; margin-bottom: 6px; }
        .search { width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--border); background: #0c1524; color: #fff; }
        .list { display: grid; gap: 12px; margin-top: 14px; }
        .content-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 8px; margin-top: 8px; }
        .content-gallery img { width: 100%; border-radius: 10px; height: 90px; object-fit: cover; border: 1px solid var(--border); }
        .tag { display: inline-flex; align-items: center; gap: 6px; background: #0f172a; border: 1px solid var(--border); color: #cbd5e1; padding: 4px 8px; border-radius: 8px; font-size: 12px; }
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.65); display: none; align-items: center; justify-content: center; padding: 20px; z-index: 50; }
        .modal.active { display: flex; }
        .modal-card { background: #0b1220; border: 1px solid var(--border); border-radius: 14px; padding: 18px; max-width: 760px; width: min(760px, 96vw); box-shadow: 0 20px 45px rgba(0,0,0,0.45); }
        .modal-close { background: #111827; border: 1px solid var(--border); border-radius: 8px; color: #e2e8f0; padding: 8px 12px; cursor: pointer; }
        iframe { width: 100%; border: none; border-radius: 10px; aspect-ratio: 16/9; }
        @media (max-width: 720px) { header { flex-direction: column; align-items: flex-start; } .flex { flex-direction: column; } }
    </style>
</head>
<body>
<header>
    <div>
        <div class="badge">Albion Wiki / Database</div>
        <h1>Модульная база знаний Albion</h1>
        <p class="section-desc" style="margin: 6px 0 0; max-width: 720px;">Быстрая навигация по мобам, снаряжению, контенту и билдам. Каждый блок изолирован и легко расширяется новыми элементами.</p>
    </div>
    <nav>
        <a href="#mobs">Мобы</a>
        <a href="#gear">Снаряжение</a>
        <a href="#content">Контент</a>
        <a href="#builds">Билды</a>
    </nav>
</header>
<main>
    <section id="mobs">
        <h2>Мобы</h2>
        <p class="section-desc">Выбери моба чтобы посмотреть тир, скиллы и описание.</p>
        <div class="grid mob-grid">
            @foreach($mobs as $mob)
                <div class="card mob-card" data-name="{{ $mob['name'] }}" data-tier="{{ $mob['tier_range'] }}" data-skills="{{ implode(', ', $mob['skills']) }}" data-desc="{{ $mob['description'] }}" data-image="{{ $mob['image'] }}">
                    <img class="mob-thumb" src="{{ $mob['image'] }}" alt="{{ $mob['name'] }}">
                    <h3>{{ $mob['name'] }}</h3>
                    <div class="badge">{{ $mob['tier_range'] }}</div>
                    <div class="skills">
                        @foreach($mob['skills'] as $skill)
                            <span class="pill">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="gear">
        <h2>Снаряжение</h2>
        <p class="section-desc">Отдельные блоки для оружия и брони. Добавляйте новые ветки или предметы через массивы данных.</p>
        <div class="flex flex-wrap">
            <div style="flex: 2; min-width: 320px;">
                <h3 style="margin:0 0 10px;">Ветки оружия</h3>
                <div class="weapon-branches">
                    @foreach($weaponBranches as $index => $branch)
                        <div class="card branch-card" data-branch="{{ $index }}">
                            <div class="badge">{{ $branch['icon'] }} Ветка</div>
                            <h3>{{ $branch['name'] }}</h3>
                            <p>{{ $branch['summary'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="flex: 3; min-width: 320px;">
                <h3 style="margin:0 0 10px;">Детали оружия</h3>
                <div id="weapon-detail" class="weapon-detail"></div>
            </div>
        </div>

        <div class="divider"></div>

        <div>
            <h3 style="margin:0 0 10px;">Броня</h3>
            <input id="armor-search" class="search" placeholder="Поиск по названию или типу..." type="search">
            <div id="armor-list" class="list"></div>
        </div>
    </section>

    <section id="content">
        <h2>Контент</h2>
        <p class="section-desc">Выбор активностей Albion: открывай карточку и смотри галерею изображений.</p>
        <div class="grid content-grid">
            @foreach($contents as $content)
                <div class="card content-card" data-name="{{ $content['name'] }}" data-desc="{{ $content['description'] }}" data-gallery='@json($content['gallery'])'>
                    <div class="flex" style="align-items:center; justify-content:space-between;">
                        <div>
                            <div class="badge">Контент</div>
                            <h3>{{ $content['name'] }}</h3>
                        </div>
                        <img src="{{ $content['icon'] }}" alt="{{ $content['name'] }}" style="width:64px; height:64px; object-fit:contain; background:#0a1220; border-radius:12px; border:1px solid var(--border);">
                    </div>
                    <p>{{ $content['description'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="builds">
        <h2>Билды</h2>
        <p class="section-desc">Фильтр по названию и тегам работает на клиенте — просто начинайте печатать.</p>
        <input id="build-search" class="search" placeholder="Поиск билдов по названию или тегу..." type="search">
        <div id="build-list" class="list"></div>
    </section>
</main>

<div id="modal" class="modal" aria-modal="true" role="dialog">
    <div class="modal-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
            <div>
                <div id="modal-badge" class="badge"></div>
                <h3 id="modal-title" style="margin:8px 0 6px;"></h3>
                <p id="modal-text" class="section-desc" style="margin:0;"></p>
            </div>
            <button class="modal-close" id="modal-close">Закрыть</button>
        </div>
        <div id="modal-extra" style="margin-top:14px;"></div>
    </div>
</div>

<script>
    const weaponBranches = @json($weaponBranches);
    const armors = @json($armors);
    const builds = @json($builds);

    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const modalText = document.getElementById('modal-text');
    const modalBadge = document.getElementById('modal-badge');
    const modalExtra = document.getElementById('modal-extra');
    const closeModal = () => modal.classList.remove('active');
    document.getElementById('modal-close').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    const openModal = ({ title, text, badge, extraHtml = '' }) => {
        modalTitle.textContent = title;
        modalText.textContent = text;
        modalBadge.textContent = badge;
        modalExtra.innerHTML = extraHtml;
        modal.classList.add('active');
    };

    // Mobs modal
    document.querySelectorAll('.mob-card').forEach(card => {
        card.addEventListener('click', () => {
            const gallery = `<img src="${card.dataset.image}" alt="${card.dataset.name}" style="width:100%; max-height:260px; object-fit:contain; border-radius:12px; background:#0a1220; border:1px solid var(--border); margin-top:10px;">`;
            const skills = card.dataset.skills.split(', ').map(skill => `<span class="tag">${skill}</span>`).join(' ');
            openModal({
                title: card.dataset.name,
                text: card.dataset.desc,
                badge: card.dataset.tier,
                extraHtml: `<div class="skills" style="margin-top:8px;">${skills}</div>${gallery}`
            });
        });
    });

    // Weapon detail renderer
    const weaponDetail = document.getElementById('weapon-detail');
    const renderBranch = (branch) => {
        const abilityBlock = (title, list) => `
            <div>
                <div class="badge">${title}</div>
                <div class="ability-list">
                    ${list.map(ability => `
                        <div class="ability" data-title="${ability.name}" data-desc="${ability.description}" data-video="${ability.video}" data-badge="${title}">
                            <strong>${ability.name}</strong>
                            <p style="margin:4px 0 0; color: var(--muted);">${ability.description}</p>
                        </div>`).join('')}
                </div>
            </div>`;

        weaponDetail.innerHTML = `
            <div class="card" style="grid-column: 1 / -1;">
                <div class="flex" style="justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <div class="badge">${branch.icon} ${branch.name}</div>
                        <h3 style="margin:6px 0;">${branch.summary}</h3>
                    </div>
                    <div class="pill">Пассив: ${branch.passive.name}</div>
                </div>
                <p class="section-desc" style="margin:6px 0 0;">${branch.passive.description}</p>
                <div class="variants" style="margin-top:12px;">
                    ${branch.variants.map(variant => `
                        <div class="card variant ability" data-title="${variant.name}" data-desc="${variant.role}" data-video="${variant.video}" data-badge="${branch.name}">
                            <img src="${variant.image}" alt="${variant.name}">
                            <strong>${variant.name}</strong>
                            <p class="section-desc" style="margin:4px 0 0;">${variant.role}</p>
                        </div>`).join('')}
                </div>
            </div>
            ${abilityBlock('Q-способности', branch.q_skills)}
            ${abilityBlock('W-способности', branch.w_skills)}
            ${abilityBlock('Пассив', [branch.passive])}
        `;

        weaponDetail.querySelectorAll('.ability').forEach(el => {
            el.addEventListener('click', () => {
                const video = `<iframe src="${el.dataset.video}" allowfullscreen title="${el.dataset.title}"></iframe>`;
                openModal({ title: el.dataset.title, text: el.dataset.desc, badge: el.dataset.badge, extraHtml: video });
            });
        });
    };

    document.querySelectorAll('.branch-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.branch-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            const branchIndex = Number(card.dataset.branch);
            renderBranch(weaponBranches[branchIndex]);
        });
    });
    // initial selection
    const firstBranch = document.querySelector('.branch-card');
    if (firstBranch) {
        firstBranch.classList.add('active');
        renderBranch(weaponBranches[0]);
    }

    // Armor search
    const armorList = document.getElementById('armor-list');
    const armorSearch = document.getElementById('armor-search');
    const renderArmor = (items) => {
        armorList.innerHTML = items.map(item => `
            <div class="card ability" data-title="${item.name}" data-desc="${item.description}" data-video="${item.video}" data-badge="${item.type}">
                <div class="flex" style="justify-content:space-between; align-items:center;">
                    <div>
                        <div class="badge">${item.type}</div>
                        <h3>${item.name}</h3>
                        <p>${item.description}</p>
                    </div>
                    <span class="pill">Видео</span>
                </div>
            </div>
        `).join('');
        armorList.querySelectorAll('.ability').forEach(el => {
            el.addEventListener('click', () => {
                const video = `<iframe src="${el.dataset.video}" allowfullscreen title="${el.dataset.title}"></iframe>`;
                openModal({ title: el.dataset.title, text: el.dataset.desc, badge: el.dataset.badge, extraHtml: video });
            });
        });
    };
    const filterArmor = () => {
        const term = armorSearch.value.toLowerCase();
        renderArmor(armors.filter(item => `${item.name} ${item.type}`.toLowerCase().includes(term)));
    };
    armorSearch.addEventListener('input', filterArmor);
    filterArmor();

    // Content gallery
    document.querySelectorAll('.content-card').forEach(card => {
        card.addEventListener('click', () => {
            const gallery = JSON.parse(card.dataset.gallery).map(src => `<img src="${src}" alt="${card.dataset.name}" style="width:100%; border-radius:12px; border:1px solid var(--border); margin-top:8px;">`).join('');
            openModal({ title: card.dataset.name, text: card.dataset.desc, badge: 'Контент', extraHtml: gallery });
        });
    });

    // Build search
    const buildList = document.getElementById('build-list');
    const buildSearch = document.getElementById('build-search');
    const renderBuilds = (items) => {
        buildList.innerHTML = items.map(build => `
            <div class="card">
                <div class="flex" style="justify-content:space-between; align-items:center;">
                    <h3 style="margin:0;">${build.name}</h3>
                    <div class="skills">${build.tags.map(tag => `<span class="tag">${tag}</span>`).join('')}</div>
                </div>
                <p style="margin:8px 0 0; color: var(--muted);">${build.description}</p>
            </div>
        `).join('');
    };
    const filterBuilds = () => {
        const term = buildSearch.value.toLowerCase();
        renderBuilds(builds.filter(build => `${build.name} ${build.tags.join(' ')}`.toLowerCase().includes(term)));
    };
    buildSearch.addEventListener('input', filterBuilds);
    filterBuilds();
</script>
</body>
</html>
