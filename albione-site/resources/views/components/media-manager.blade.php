@props(['skillable', 'skillableType' => 'weapon-skill'])

<div class="media-manager" x-data="mediaManager()">
    <div class="media-upload-section">
        <h4 class="section-title">Управление медиа</h4>
        
        <div class="upload-options">
            <div class="upload-option">
                <label class="upload-label">
                    <input 
                        type="file" 
                        class="file-input"
                        accept="image/png,image/jpeg,image/webp"
                        @change="handleImageUpload"
                    >
                    <span class="upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <path d="M21 15l-5-5L5 21"></path>
                        </svg>
                        Загрузить изображение
                    </span>
                </label>
                <p class="upload-hint">PNG, JPG, WebP (макс. 5 МБ)</p>
            </div>

            <div class="upload-option">
                <label class="upload-label">
                    <input 
                        type="file" 
                        class="file-input"
                        accept="image/gif"
                        @change="handleGifUpload"
                    >
                    <span class="upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <text x="6" y="16" font-size="8" font-weight="bold">GIF</text>
                        </svg>
                        Загрузить GIF
                    </span>
                </label>
                <p class="upload-hint">GIF (макс. 10 МБ)</p>
            </div>

            <div class="upload-option">
                <label class="upload-label">
                    <input 
                        type="file" 
                        class="file-input"
                        accept="video/mp4,video/webm,video/ogg"
                        @change="handleVideoUpload"
                    >
                    <span class="upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="5 3 19 12 5 21"></polygon>
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        </svg>
                        Загрузить видео
                    </span>
                </label>
                <p class="upload-hint">MP4, WebM, OGG (макс. 50 МБ)</p>
            </div>
        </div>

        <div x-show="uploading" class="upload-progress">
            <div class="progress-bar">
                <div class="progress-fill" :style="{ width: uploadProgress + '%' }"></div>
            </div>
            <p class="progress-text"><span x-text="uploadProgress"></span>%</p>
        </div>

        <template x-if="uploadError">
            <div class="upload-error" @click="uploadError = null">
                <p x-text="uploadError"></p>
                <button type="button" aria-label="Закрыть">✕</button>
            </div>
        </template>

        <template x-if="uploadSuccess">
            <div class="upload-success" @click="uploadSuccess = null">
                <p>✓ Файл успешно загружен</p>
                <button type="button" aria-label="Закрыть">✕</button>
            </div>
        </template>
    </div>

    <div class="media-list-section">
        <h4 class="section-title">Загруженные медиа</h4>
        
        <template x-if="media.length === 0">
            <p class="empty-state">Нет загруженных медиа</p>
        </template>

        <div class="media-grid">
            <template x-for="item in media" :key="item.id">
                <div class="media-card">
                    <div class="media-preview">
                        <template x-if="item.type === 'image'">
                            <img :src="item.preview" :alt="item.original_name" class="media-thumbnail">
                        </template>
                        <template x-if="item.type === 'gif'">
                            <div class="gif-badge">GIF</div>
                            <img :src="item.preview" :alt="item.original_name" class="media-thumbnail">
                        </template>
                        <template x-if="item.type === 'video'">
                            <div class="video-badge">
                                <svg viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21"></polygon>
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div class="media-info">
                        <p class="media-name" :title="item.original_name" x-text="truncate(item.original_name, 20)"></p>
                        <p class="media-type" x-text="item.type"></p>
                    </div>
                    <button 
                        type="button"
                        class="media-delete"
                        @click="deleteMedia(item.id)"
                        aria-label="Удалить"
                        title="Удалить"
                    >
                        🗑️
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaManager', () => ({
        media: [],
        uploading: false,
        uploadProgress: 0,
        uploadError: null,
        uploadSuccess: false,

        async handleImageUpload(event) {
            await this.uploadFile(event, 'image', 5);
        },

        async handleGifUpload(event) {
            await this.uploadFile(event, 'gif', 10);
        },

        async handleVideoUpload(event) {
            await this.uploadFile(event, 'video', 50);
        },

        async uploadFile(event, type, maxMB) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > maxMB * 1024 * 1024) {
                this.uploadError = `Файл слишком большой. Максимум: ${maxMB} МБ`;
                return;
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', type);
            formData.append('skillable_id', '{{ $skillable->id }}');
            formData.append('skillable_type', '{{ $skillableType }}');

            this.uploading = true;
            this.uploadError = null;
            this.uploadSuccess = false;

            try {
                const response = await fetch('/api/media/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Ошибка загрузки');
                }

                const data = await response.json();
                this.media.push(data);
                this.uploadSuccess = true;
                setTimeout(() => this.uploadSuccess = false, 3000);
            } catch (error) {
                this.uploadError = error.message;
            } finally {
                this.uploading = false;
                this.uploadProgress = 0;
                event.target.value = '';
            }
        },

        async deleteMedia(mediaId) {
            if (!confirm('Вы уверены?')) return;

            try {
                const response = await fetch(`/api/media/${mediaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Ошибка удаления');

                this.media = this.media.filter(item => item.id !== mediaId);
            } catch (error) {
                this.uploadError = 'Ошибка при удалении медиа';
            }
        },

        truncate(str, length) {
            return str.length > length ? str.substring(0, length) + '...' : str;
        }
    }));
});
</script>

<style scoped>
.media-manager {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 20px;
}

.section-title {
    margin: 0 0 16px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.media-upload-section {
    margin-bottom: 30px;
}

.upload-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.upload-option {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.file-input {
    display: none;
}

.upload-label {
    cursor: pointer;
}

.upload-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: rgba(242, 200, 124, 0.1);
    border: 2px dashed rgba(242, 200, 124, 0.3);
    border-radius: 8px;
    color: #f2c87c;
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    transition: all 0.2s ease;
}

.upload-btn svg {
    width: 24px;
    height: 24px;
}

.upload-label:hover .upload-btn {
    background: rgba(242, 200, 124, 0.2);
    border-color: rgba(242, 200, 124, 0.5);
}

.upload-hint {
    margin: 0;
    font-size: 11px;
    color: var(--text-tertiary);
    text-align: center;
}

.upload-progress {
    margin-top: 16px;
}

.progress-bar {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #f2c87c, #f2a440);
    transition: width 0.3s ease;
}

.progress-text {
    margin: 0;
    font-size: 12px;
    color: var(--text-secondary);
    text-align: center;
}

.upload-error,
.upload-success {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-radius: 8px;
    margin-top: 12px;
    cursor: pointer;
    font-size: 13px;
}

.upload-error {
    background: rgba(255, 82, 82, 0.1);
    border: 1px solid rgba(255, 82, 82, 0.3);
    color: #ff5252;
}

.upload-error button {
    background: transparent;
    border: none;
    color: #ff5252;
    cursor: pointer;
    font-size: 18px;
}

.upload-success {
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: #4caf50;
}

.upload-success button {
    background: transparent;
    border: none;
    color: #4caf50;
    cursor: pointer;
    font-size: 18px;
}

.media-list-section {
    margin-top: 30px;
}

.empty-state {
    text-align: center;
    padding: 20px;
    color: var(--text-tertiary);
    font-size: 13px;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 12px;
}

.media-card {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--line);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.media-card:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--line-strong);
}

.media-preview {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    border-radius: 6px;
    background: rgba(0, 0, 0, 0.3);
}

.media-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gif-badge,
.video-badge {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(0, 0, 0, 0.7);
    padding: 4px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
    color: white;
    z-index: 1;
}

.video-badge {
    padding: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-badge svg {
    width: 12px;
    height: 12px;
}

.media-info {
    flex: 1;
    min-width: 0;
}

.media-name {
    margin: 0;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.media-type {
    margin: 0;
    font-size: 11px;
    color: var(--text-tertiary);
    text-transform: uppercase;
}

.media-delete {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 14px;
    padding: 4px;
    transition: opacity 0.2s ease;
}

.media-delete:hover {
    opacity: 0.7;
}
</style>
