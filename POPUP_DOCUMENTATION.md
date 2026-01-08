# Skill Popup и Media Management - Документация

## Обзор

Реализована система для отображения скиллов оружия и брони в красивом popup окне с поддержкой трех типов медиа: изображения, GIF и видео.

## Компоненты

### 1. `skill-popup` компонент
Компонент отображает информацию о скилле и его медиа в интерактивном popup.

**Использование:**
```blade
<x-skill-popup :skill="$weapon->weaponSkill" />
```

**Функции:**
- Отображение названия и описания скилла
- Табы для переключения между медиа (Image, GIF, Video)
- Примечания автора в свернутом виде
- Backdrop для затемнения фона
- Поддержка клавиши ESC для закрытия

### 2. `media-manager` компонент
Админ-компонент для управления медиа (загрузка, удаление).

**Использование:**
```blade
<x-media-manager :skillable="$skill" skillable-type="armor-skill" />
```

**Функции:**
- Загрузка 3 типов файлов (Image, GIF, Video)
- Прогресс-бар загрузки
- Список загруженного контента
- Удаление медиа

## Модели и Связи

### WeaponSkill
```php
public function weapon() { return $this->belongsTo(Weapon::class); }
public function media() { return $this->morphMany(SkillMedia::class, 'skillable'); }
```

### ArmorSkill (новая)
```php
public function armorItem() { return $this->belongsTo(ArmorItem::class); }
public function media() { return $this->morphMany(SkillMedia::class, 'skillable'); }
```

### LineSkill
```php
public function media() { return $this->morphMany(SkillMedia::class, 'skillable'); }
```

### SkillMedia
```php
public function skillable() { return $this->morphTo(); }
```

**Поля:**
- `id` - ID медиа
- `skillable_type` - Тип модели (WeaponSkill, ArmorSkill, LineSkill)
- `skillable_id` - ID модели
- `path` - Путь к файлу или URL
- `disk` - Диск хранилища (local, url)
- `original_name` - Оригинальное имя файла
- `type` - Тип медиа (image, gif, video)
- `is_private` - Приватность
- `timestamps` - Время создания/обновления

## API Endpoints

### POST /api/media/upload
Загрузка медиа.

**Параметры:**
- `file` - Файл (required)
- `type` - image|gif|video (required)
- `skillable_id` - ID скилла (required)
- `skillable_type` - weapon-skill|armor-skill|line-skill (required)

**Ответ:**
```json
{
  "id": 1,
  "type": "image",
  "original_name": "skill.png",
  "preview": "url_to_preview"
}
```

### DELETE /api/media/{id}
Удаление медиа (только для админов).

### GET /api/media
Получение списка медиа.

**Параметры:**
- `skillable_type` - Тип модели
- `skillable_id` - ID модели

## Views

### Публичные views

#### weapons/show.blade.php
Добавлен блок со скиллами оружия:
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

#### armor/show.blade.php
Добавлен блок со скиллами брони:
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

### Админ views

#### admin/armor-skills/create.blade.php
Форма создания нового скилла брони.

#### admin/armor-skills/edit.blade.php
Форма редактирования скилла с интегрированным media-manager.

#### admin/armor-items/armor-form.blade.php
Обновленная форма с секцией управления скиллами брони.

## Routes

### Web Routes
```php
// Armor Skills
Route::get('/armor/{armorId}/skills/create', [AdminArmorSkillController::class, 'create'])->name('armor-skills.create');
Route::post('/armor/{armorId}/skills', [AdminArmorSkillController::class, 'store'])->name('armor-skills.store');
Route::get('/armor-skills/{id}/edit', [AdminArmorSkillController::class, 'edit'])->name('armor-skills.edit');
Route::put('/armor-skills/{id}', [AdminArmorSkillController::class, 'update'])->name('armor-skills.update');
Route::delete('/armor-skills/{id}', [AdminArmorSkillController::class, 'destroy'])->name('armor-skills.destroy');

// API Media
Route::post('/api/media/upload', [ApiMediaController::class, 'upload'])->name('api.media.upload');
Route::get('/api/media', [ApiMediaController::class, 'index'])->name('api.media.index');
Route::delete('/api/media/{media}', [ApiMediaController::class, 'destroy'])->name('api.media.destroy');
```

## Database Migrations

1. `2025_12_20_000100_create_armor_skills_table.php`
   - Создает таблицу armor_skills

2. `2025_12_20_000200_add_type_to_skill_media_table.php`
   - Добавляет колонку `type` в skill_media

## Файловая структура

```
app/
  Http/
    Controllers/
      Admin/
        ArmorSkillController.php (новый)
      Api/
        MediaController.php (новый)
  Models/
    ArmorSkill.php (новая)
    SkillMedia.php (обновлена)

database/
  migrations/
    2025_12_20_000100_create_armor_skills_table.php
    2025_12_20_000200_add_type_to_skill_media_table.php

resources/
  views/
    components/
      skill-popup.blade.php (новая)
      media-manager.blade.php (существует)
    admin/
      armor-skills/
        create.blade.php (новая)
        edit.blade.php (новая)
      armor-items/
        armor-form.blade.php (обновлена)
    weapons/
      show.blade.php (обновлена)
    armor/
      show.blade.php (обновлена)
```

## Стиль и Дизайн

Все компоненты используют CSS переменные из главного layout:
- `--panel` - фон панели
- `--line` - цвет границ
- `--text` - основной цвет текста
- `--gold-strong` - золотистый акцент
- `--shadow` - тень

Popup имеет:
- Модальный backdrop с затемнением
- Плавные анимации открытия/закрытия
- Адаптивный дизайн (работает на мобильных)
- Доступность (aria атрибуты, клавиатурная навигация)

## Использование

### Для пользователей
1. Откройте страницу оружия или брони
2. Нажмите на скилл
3. Popup откроется с информацией и медиа
4. Переключайтесь между медиа вкладками
5. Нажмите ESC или на фон для закрытия

### Для администраторов
1. Перейдите в админ панель
2. Отредактируйте броню
3. Нажмите "+ Add Skill"
4. Заполните информацию о скилле
5. Используйте media-manager для загрузки изображений, GIF, видео
6. Сохраните

## Лимиты

- Максимальный размер файла: 50 МБ
- Поддерживаемые форматы:
  - Image: PNG, JPG, JPEG, GIF, WebP
  - Video: MP4, WebM, OGG

## Готово к использованию

Система полностью готова к использованию. Выполните миграции:
```bash
php artisan migrate
```

И начните добавлять скиллы и медиа через админ панель!
