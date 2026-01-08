# Примеры использования Popup системы

## 1. Отображение Popup на публичной странице

### Оружие (weapons/show.blade.php)

```blade
@if($weapon->weaponSkill)
    <section class="panel">
        <div class="subtle-title">Скиллы оружия</div>
        <div class="content-area">
            <x-skill-popup :skill="$weapon->weaponSkill" />
        </div>
    </section>
@endif
```

### Броня (armor/show.blade.php)

```blade
@if($item->armorSkills && $item->armorSkills->count() > 0)
    <section class="panel">
        <div class="subtle-title">Скиллы брони</div>
        <div class="content-area" style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($item->armorSkills as $skill)
                <x-skill-popup :skill="$skill" />
            @endforeach
        </div>
    </section>
@endif
```

## 2. Управление Скиллами в Админ Панели

### Создание нового скилла

```php
// routes/web.php
Route::get('/armor/{armorId}/skills/create', [AdminArmorSkillController::class, 'create'])->name('armor-skills.create');

// Переход:
// /admin/armor/{id}/edit -> нажать "+ Add Skill"
```

### Редактирование скилла с медиа

```blade
<!-- admin/armor-skills/edit.blade.php -->
<form action="{{ route('admin.armor-skills.update', $skill->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div>
        <label class="block text-sm font-medium">Name</label>
        <input name="name" value="{{ old('name', $skill->name) }}" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="4">{{ old('description', $skill->description) }}</textarea>
    </div>

    <div>
        <x-media-manager :skillable="$skill" skillable-type="armor-skill" />
    </div>

    <button type="submit">Save</button>
</form>
```

## 3. API для загрузки медиа

### JavaScript пример

```javascript
// Загрузка изображения
async function uploadImage(file, skillId) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', 'image');
    formData.append('skillable_id', skillId);
    formData.append('skillable_type', 'armor-skill');

    const response = await fetch('/api/media/upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    });

    const data = await response.json();
    console.log('Uploaded:', data);
    return data;
}

// Загрузка GIF
async function uploadGif(file, skillId) {
    // То же самое, но type: 'gif'
}

// Загрузка видео
async function uploadVideo(file, skillId) {
    // То же самое, но type: 'video'
}

// Удаление медиа
async function deleteMedia(mediaId) {
    const response = await fetch(`/api/media/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    });

    console.log('Deleted');
}
```

## 4. PHP пример создания скилла с медиа

```php
use App\Models\ArmorItem;
use App\Models\ArmorSkill;
use App\Models\SkillMedia;

// Получить броню
$armor = ArmorItem::find(1);

// Создать скилл
$skill = $armor->armorSkills()->create([
    'name' => 'Fire Resistance',
    'description' => 'Increases resistance to fire damage',
    'author_notes' => 'Tested with enchantment level 2',
]);

// Добавить изображение
$skill->media()->create([
    'path' => 'path/to/image.jpg',
    'disk' => 'local',
    'original_name' => 'image.jpg',
    'type' => 'image',
    'is_private' => true,
]);

// Добавить видео
$skill->media()->create([
    'path' => 'path/to/demo.mp4',
    'disk' => 'local',
    'original_name' => 'demo.mp4',
    'type' => 'video',
    'is_private' => true,
]);
```

## 5. Структура базы данных

### armor_skills таблица
```sql
CREATE TABLE armor_skills (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    armor_item_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description LONGTEXT,
    author_notes LONGTEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (armor_item_id) REFERENCES armor_items(id) ON DELETE CASCADE
);
```

### skill_media таблица (обновлена)
```sql
CREATE TABLE skill_media (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    skillable_type VARCHAR(255) NOT NULL,
    skillable_id BIGINT NOT NULL,
    path VARCHAR(255) NOT NULL,
    disk VARCHAR(255) DEFAULT 'private',
    original_name VARCHAR(255),
    is_private BOOLEAN DEFAULT 1,
    type ENUM('image', 'gif', 'video') DEFAULT 'image',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX skillable (skillable_type, skillable_id)
);
```

## 6. Стили и переменные CSS

```css
/* Переменные из layout/app.blade.php */
:root {
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
```

## 7. Миграции

### Установка
```bash
php artisan migrate
```

### Миграции
- `2025_12_20_000100_create_armor_skills_table.php`
- `2025_12_20_000200_add_type_to_skill_media_table.php`

## 8. Routes

```php
// Public routes
Route::get('/weapons/{slug}', [WeaponController::class, 'show'])->name('weapons.show');
Route::get('/armor/{slug}', [ArmorController::class, 'show'])->name('armor.show');
Route::get('/media/{media}', [SkillMediaController::class, 'show'])->middleware('signed')->name('media.show');

// Admin routes
Route::get('/armor/{armorId}/skills/create', [AdminArmorSkillController::class, 'create'])->name('armor-skills.create');
Route::post('/armor/{armorId}/skills', [AdminArmorSkillController::class, 'store'])->name('armor-skills.store');
Route::get('/armor-skills/{id}/edit', [AdminArmorSkillController::class, 'edit'])->name('armor-skills.edit');
Route::put('/armor-skills/{id}', [AdminArmorSkillController::class, 'update'])->name('armor-skills.update');
Route::delete('/armor-skills/{id}', [AdminArmorSkillController::class, 'destroy'])->name('armor-skills.destroy');

// API routes
Route::post('/api/media/upload', [ApiMediaController::class, 'upload'])->name('api.media.upload');
Route::get('/api/media', [ApiMediaController::class, 'index'])->name('api.media.index');
Route::delete('/api/media/{media}', [ApiMediaController::class, 'destroy'])->name('api.media.destroy');
```

## 9. Часто задаваемые вопросы

### Q: Как изменить максимальный размер файла?
A: Отредактируйте в `app/Http/Controllers/Api/MediaController.php`:
```php
'file' => 'required|file|max:51200', // размер в КБ
```

### Q: Как добавить свой тип медиа?
A: 
1. Добавьте в миграцию: `'type' => 'enum(..., 'your-type')`
2. Обновите компоненты для отображения

### Q: Как работает кеширование?
A: Используется Laravel Cache с TTL 10 минут. При обновлении скилла, кеш очищается автоматически через инвалидацию.

### Q: Поддерживается ли загрузка с URL?
A: Да, для URL используется `disk: 'url'` в skill_media таблице.

## 10. Troubleshooting

### Popup не открывается
- Проверьте, что Alpine.js загружен: `<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>`
- Проверьте браузерную консоль на ошибки

### Медиа не загружается
- Проверьте права доступа к папке `storage/app/media/`
- Проверьте лимит размера файла в nginx/php

### CSS не применяется
- Убедитесь, что layout наследует CSS переменные
- Проверьте, что `scoped` стиль работает в вашей версии Laravel/Vue

---

Готово! Система полностью функциональна и готова к использованию.
