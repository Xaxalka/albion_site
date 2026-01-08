@props(['skill'])

<div class="skill-hover-popup" x-data="skillHoverPopup(@json($skill))">
    <button 
        type="button"
        class="skill-trigger"
        @mouseenter="showPopup()"
        @mouseleave="hidePopup()"
        @focus="showPopup()"
        @blur="hidePopup()"
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
        class="skill-tooltip" 
        :class="{ 'is-visible': isVisible }"
        role="tooltip"
    >
        @if($skill->description)
            <div class="tooltip-description">{{ $skill->description }}</div>
        @endif

        @if($skill->author_notes)
            <div class="tooltip-notes">{{ $skill->author_notes }}</div>
        @endif

        @if($skill->media && $skill->media->count() > 0)
            <div class="tooltip-media-indicator">
                📷 {{ $skill->media->count() }} медиа
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('skillHoverPopup', (skill) => ({
        isVisible: false,
        hideTimeout: null,

        showPopup() {
            clearTimeout(this.hideTimeout);
            this.isVisible = true;
        },

        hidePopup() {
            this.hideTimeout = setTimeout(() => {
                this.isVisible = false;
            }, 200);
        }
    }));
});
</script>

<style scoped>
.skill-hover-popup {
    position: relative;
    display: inline-block;
}

.skill-trigger {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: rgba(242, 200, 124, 0.15);
    border: 1px solid rgba(242, 200, 124, 0.4);
    border-radius: 8px;
    color: #f2c87c;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    letter-spacing: 0.02em;
}

.skill-trigger:hover,
.skill-trigger:focus {
    background: rgba(242, 200, 124, 0.25);
    border-color: rgba(242, 200, 124, 0.6);
    outline: none;
    transform: translateY(-2px);
}

.skill-icon {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.skill-tooltip {
    position: absolute;
    bottom: calc(100% + 12px);
    left: 50%;
    transform: translateX(-50%) translateY(8px);
    opacity: 0;
    pointer-events: none;
    transition: all 0.2s ease;
    background: var(--panel);
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 12px 14px;
    max-width: 280px;
    white-space: normal;
    word-wrap: break-word;
    z-index: 1000;
    box-shadow: 0 10px 30px var(--shadow);
}

.skill-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid var(--line);
}

.skill-tooltip.is-visible {
    opacity: 1;
    pointer-events: auto;
    transform: translateX(-50%) translateY(0);
}

.tooltip-description {
    font-size: 13px;
    line-height: 1.5;
    color: #e5e7eb;
    margin-bottom: 8px;
}

.tooltip-notes {
    font-size: 12px;
    color: #b6c2cf;
    font-style: italic;
    padding-top: 8px;
    border-top: 1px solid rgba(215, 182, 118, 0.2);
    margin-top: 8px;
}

.tooltip-media-indicator {
    font-size: 12px;
    color: #f2c87c;
    padding-top: 8px;
    border-top: 1px solid rgba(215, 182, 118, 0.2);
    margin-top: 8px;
    font-weight: 500;
}

@media (max-width: 640px) {
    .skill-tooltip {
        max-width: 200px;
        padding: 10px 12px;
        font-size: 12px;
    }
}
</style>
