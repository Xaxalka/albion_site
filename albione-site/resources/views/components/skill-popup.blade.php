@props(['skill'])

<div class="skill-popup" x-data="skillPopup(@json($skill))" @click.outside="closePopup()">
    <button 
        type="button"
        class="skill-trigger"
        @click="openPopup()"
        :aria-expanded="isOpen"
        aria-haspopup="dialog"
    >
        <span class="skill-name">{{ $skill->name }}</span>
        <svg class="skill-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
    </button>

    <div 
        class="skill-popup-content" 
        :class="{ 'is-open': isOpen }"
        role="dialog"
        aria-labelledby="skill-title"
        @click.stop
    >
        <div class="popup-header">
            <h3 id="skill-title" class="popup-title">{{ $skill->name }}</h3>
            <button 
                type="button"
                class="popup-close"
                @click="closePopup()"
                aria-label="Закрыть"
            >
                ✕
            </button>
        </div>

        <div class="popup-body">
            @if($skill->description)
                <div class="skill-description">
                    <p>{{ $skill->description }}</p>
                </div>
            @endif

            @if($skill->media && $skill->media->count() > 0)
                <div class="skill-media-section">
                    <div class="media-tabs">
                        @foreach($skill->media as $media)
                            <button 
                                type="button"
                                class="media-tab"
                                :class="{ 'is-active': activeMediaId === {{ $media->id }} }"
                                @click="activeMediaId = {{ $media->id }}"
                            >
                                <svg class="media-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($media->type === 'image')
                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <path d="M21 15l-5-5L5 21"></path>
                                    @elseif($media->type === 'gif')
                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                        <text x="9" y="15" font-size="10" font-weight="bold">GIF</text>
                                    @elseif($media->type === 'video')
                                        <polygon points="5 3 19 12 5 21"></polygon>
                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    @endif
                                </svg>
                                {{ ucfirst($media->type) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="media-viewer">
                        @foreach($skill->media as $media)
                            <div 
                                class="media-item"
                                :class="{ 'is-active': activeMediaId === {{ $media->id }} }"
                            >
                                @if($media->type === 'image')
                                    <img 
                                        src="{{ $media->disk === 'url' ? $media->path : route('media.show', ['media' => $media]) }}"
                                        alt="{{ $skill->name }}"
                                        class="media-image"
                                    >
                                @elseif($media->type === 'gif')
                                    <img 
                                        src="{{ $media->disk === 'url' ? $media->path : route('media.show', ['media' => $media]) }}"
                                        alt="{{ $skill->name }} GIF"
                                        class="media-gif"
                                    >
                                @elseif($media->type === 'video')
                                    <video 
                                        class="media-video"
                                        controls
                                        autoplay
                                        muted
                                        loop
                                    >
                                        <source 
                                            src="{{ $media->disk === 'url' ? $media->path : route('media.show', ['media' => $media]) }}"
                                            type="video/mp4"
                                        >
                                        Ваш браузер не поддерживает видео.
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($skill->author_notes)
                <div class="skill-notes">
                    <details>
                        <summary>Примечания автора</summary>
                        <p>{{ $skill->author_notes }}</p>
                    </details>
                </div>
            @endif
        </div>
    </div>

    <!-- Backdrop -->
    <div 
        class="skill-popup-backdrop"
        :class="{ 'is-visible': isOpen }"
        @click="closePopup()"
    ></div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('skillPopup', (skill) => ({
        isOpen: false,
        activeMediaId: skill.media && skill.media.length > 0 ? skill.media[0].id : null,

        openPopup() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closePopup() {
            this.isOpen = false;
            document.body.style.overflow = 'auto';
        }
    }));
});
</script>

<style scoped>
.skill-popup {
    position: relative;
}

.skill-trigger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(242, 200, 124, 0.1);
    border: 1px solid rgba(242, 200, 124, 0.3);
    border-radius: 6px;
    color: #f2c87c;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.skill-trigger:hover {
    background: rgba(242, 200, 124, 0.2);
    border-color: rgba(242, 200, 124, 0.5);
}

.skill-icon {
    width: 16px;
    height: 16px;
}

.skill-popup-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 999;
}

.skill-popup-backdrop.is-visible {
    opacity: 1;
    pointer-events: auto;
}

.skill-popup-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.95);
    opacity: 0;
    pointer-events: none;
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 20px 60px var(--shadow);
    max-width: 600px;
    width: 90vw;
    max-height: 80vh;
    overflow-y: auto;
    z-index: 1000;
    transition: all 0.3s ease;
}

.skill-popup-content.is-open {
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--line);
    gap: 12px;
    position: sticky;
    top: 0;
    background: linear-gradient(160deg, rgba(13,19,31,0.95), rgba(11,16,25,0.92));
    z-index: 1;
}

.popup-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #fff;
}

.popup-close {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: 1px solid var(--line);
    border-radius: 6px;
    color: var(--text);
    cursor: pointer;
    font-size: 18px;
    transition: all 0.2s ease;
}

.popup-close:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--gold-strong);
}

.popup-body {
    padding: 20px;
}

.skill-description {
    margin-bottom: 20px;
}

.skill-description p {
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
    color: var(--text);
}

.skill-media-section {
    margin-bottom: 20px;
}

.media-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.media-tab {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--line);
    border-radius: 6px;
    color: var(--text);
    cursor: pointer;
    font-size: 13px;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.media-tab:hover {
    background: rgba(255, 255, 255, 0.1);
}

.media-tab.is-active {
    background: rgba(242, 200, 124, 0.2);
    border-color: #f2c87c;
    color: #f2c87c;
}

.media-icon {
    width: 14px;
    height: 14px;
}

.media-viewer {
    position: relative;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    overflow: hidden;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-item {
    position: absolute;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.media-item.is-active {
    opacity: 1;
    pointer-events: auto;
    position: relative;
}

.media-image,
.media-gif {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.media-video {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.skill-notes {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--line);
}

.skill-notes details {
    cursor: pointer;
}

.skill-notes summary {
    font-weight: 500;
    font-size: 14px;
    color: #fff;
    padding: 8px;
    margin: -8px;
    user-select: none;
}

.skill-notes p {
    margin: 12px 0 0 0;
    font-size: 13px;
    line-height: 1.6;
    color: var(--text);
}
</style>
