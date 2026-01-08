# 🚀 Быстрый старт - Skill Popup система

## За 5 минут до первого popup

### 1. Выполнить миграции
```bash
cd /home/nikita/Documents/albion_site/albione-site
php artisan migrate
```

### 2. Убедиться, что папка storage доступна
```bash
chmod -R 775 storage/app
chmod -R 775 storage/framework
```

### 3. Готово! 🎉

## Как добавить первый скилл с видео

### В админ панели:

1. **Перейдите в Admin → Armor Items**
   ```
   /admin/armor
   ```

2. **Откройте броню для редактирования**
   ```
   /admin/armor/{id}/edit
   ```

3. **Нажмите "+ Add Skill"** (внизу страницы в новой секции)

4. **Заполните информацию:**
   - Name: "Fire Shield"
   - Description: "Creates a shield of flames"
   - Author Notes: "Very effective against ice"

5. **Нажмите Create**

6. **На странице редактирования скилла:**
   - Загрузите изображение (📷)
   - Загрузите видео (🎥)
   - Загрузите GIF (🎬)

7. **Нажмите Save**

### На публичной странице:

1. **Откройте броню**
   ```
   /armor/your-armor-slug
   ```

2. **Нажмите на кнопку скилла** (в секции "Скиллы брони")

3. **Popup откроется с информацией!** 🎉

## Структура компонента

```blade
<!-- Использование в view -->
<x-skill-popup :skill="$armor->armorSkills->first()" />
```

## Что поддерживается?

| Тип | Форматы | Макс. размер |
|-----|---------|-------------|
| 📷 Image | PNG, JPG, JPEG, WebP | 50 МБ |
| 🎬 GIF | GIF | 50 МБ |
| 🎥 Video | MP4, WebM, OGG | 50 МБ |

## API для программистов

### Загрузить медиа
```javascript
const formData = new FormData();
formData.append('file', file);
formData.append('type', 'video'); // image, gif, video
formData.append('skillable_id', 1);
formData.append('skillable_type', 'armor-skill');

fetch('/api/media/upload', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').content
    },
    body: formData
});
```

### Удалить медиа
```javascript
fetch(`/api/media/${mediaId}`, {
    method: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('[name=csrf-token]').content
    }
});
```

## Файлы для изучения

1. **Компонент popup:**
   ```
   resources/views/components/skill-popup.blade.php
   ```

2. **Компонент загрузки:**
   ```
   resources/views/components/media-manager.blade.php
   ```

3. **API контроллер:**
   ```
   app/Http/Controllers/Api/MediaController.php
   ```

4. **Admin контроллер:**
   ```
   app/Http/Controllers/Admin/ArmorSkillController.php
   ```

## Часто возникающие вопросы

**Q: Где мои файлы сохраняются?**
A: В папке `storage/app/media/` в подпапках:
- `images/` - для изображений
- `gifs/` - для GIF
- `videos/` - для видео

**Q: Как изменить максимальный размер файла?**
A: В `app/Http/Controllers/Api/MediaController.php`:
```php
'file' => 'required|file|max:102400', // 100 МБ вместо 50
```

**Q: Почему медиа не видно на публичной странице?**
A: Убедитесь:
1. Скилл создан и сохранен
2. Медиа загружено и присутствует в таблице `skill_media`
3. Броня имеет связь с `armorSkills`

**Q: Как удалить скилл?**
A: На странице редактирования брони, найдите скилл и нажмите "Delete".

## Структура данных

```
ArmorItem
  └─ armorSkills (HasMany)
      └─ ArmorSkill
          └─ media (MorphMany)
              └─ SkillMedia (image, gif, video)
```

## Troubleshooting

### Popup не открывается
```bash
# Проверьте, загружен ли Alpine.js в layout
grep "alpinejs" resources/views/layouts/app.blade.php
```

### Файлы не загружаются
```bash
# Проверьте права доступа
sudo chmod -R 775 storage/
# Проверьте владельца
sudo chown -R www-data:www-data storage/
```

### Ошибка "Unauthorized"
```
Убедитесь, что вы админ (is_admin = 1 в таблице users)
```

## Готово! 🎊

Теперь у вас есть полностью функциональная система popup для отображения скиллов с поддержкой трех типов медиа!

Для полной документации смотрите:
- `POPUP_DOCUMENTATION.md` - Техническая документация
- `POPUP_EXAMPLES.md` - Примеры кода
- `IMPLEMENTATION_SUMMARY.md` - Полный отчет

---

**Остались вопросы?** Проверьте файл `POPUP_DOCUMENTATION.md`! 📚
