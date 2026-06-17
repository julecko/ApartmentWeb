<script setup lang="ts">
import { ref, computed } from 'vue';
import type { NewsAttachment } from '$types/news';
import { useI18n } from '$lib/i18n';

const props = defineProps<{ attachments: NewsAttachment[] }>();

const { t } = useI18n();
const open = ref(false);

function formatSize(bytes: number): string {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

const ICON: Record<string, string> = {
    'application/pdf': '📄',
    'image/jpeg': '🖼️',
    'image/png': '🖼️',
    'image/gif': '🖼️',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': '📝',
    'application/msword': '📝',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': '📊',
    'application/vnd.ms-excel': '📊',
};

function icon(mimeType: string): string {
    return ICON[mimeType] ?? '📎';
}

const label = computed(() =>
    open.value ? t.value.attachmentsHide : t.value.attachmentsShow
);
</script>

<template>
    <div v-if="attachments.length" class="attachments">
        <button class="attachments__toggle" @click="open = !open" :aria-expanded="open">
            <span class="attachments__toggle-left">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M11.5 6.5L7.5 2C6.7 1.2 5.4 1.2 4.6 2L1.5 5.1C0.7 5.9 0.7 7.2 1.5 8L7 13.5"
                        stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                    <path d="M4 9L9 4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                </svg>
                {{ attachments.length }}
                {{ attachments.length === 1 ? t.attachmentSingular : t.attachmentPlural }}
            </span>
            <svg class="attachments__chevron" :class="{ 'attachments__chevron--up': open }" width="12" height="12"
                viewBox="0 0 12 12" fill="none" aria-hidden="true">
                <path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <Transition name="slide">
            <ul v-if="open" class="attachments__list">
                <li v-for="file in attachments" :key="file.id" class="attachments__item">
                    <span class="attachments__icon" aria-hidden="true">{{ icon(file.mimeType) }}</span>
                    <span class="attachments__name">{{ file.name }}</span>
                    <span class="attachments__size">{{ formatSize(file.size) }}</span>
                    <a :href="file.url" :download="file.name" class="attachments__download"
                        :aria-label="`${t.download} ${file.name}`">
                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
                            <path d="M6.5 1v8M3.5 6.5l3 3 3-3" stroke="currentColor" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M1.5 11h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
                        </svg>
                        {{ t.download }}
                    </a>
                </li>
            </ul>
        </Transition>
    </div>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.attachments {
    border-top: 1px solid $color-border;

    &__toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px $spacing-md;
        background: transparent;
        border: none;
        font-family: $font-body;
        font-size: $font-size-sm;
        font-weight: $font-weight-medium;
        color: $color-text-secondary;
        cursor: pointer;
        transition: background 0.15s;

        &:hover {
            background: rgba(0, 0, 0, 0.025);
        }

        &:focus-visible {
            outline: 2px solid $color-text-link;
            outline-offset: -2px;
        }
    }

    &__toggle-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    &__chevron {
        color: $color-text-muted;
        transition: transform 0.2s ease;
        flex-shrink: 0;

        &--up {
            transform: rotate(180deg);
        }
    }

    &__list {
        list-style: none;
        border-top: 1px solid $color-border;
        overflow: hidden;
    }

    &__item {
        display: flex;
        align-items: center;
        gap: $spacing-sm;
        padding: 9px $spacing-md;
        border-bottom: 1px solid $color-border;

        &:last-child {
            border-bottom: none;
        }
    }

    &__icon {
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    &__name {
        flex: 1;
        font-size: $font-size-sm;
        color: $color-text-primary;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__size {
        font-size: $font-size-xs;
        color: $color-text-muted;
        flex-shrink: 0;
    }

    &__download {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: $radius-sm;
        border: 1px solid $color-border-card;
        background: $color-bg;
        font-size: $font-size-xs;
        font-weight: $font-weight-medium;
        color: $color-text-link;
        text-decoration: none;
        flex-shrink: 0;
        transition: background 0.15s, border-color 0.15s;

        &:hover {
            background: $color-text-link;
            color: #fff;
            border-color: $color-text-link;
        }
    }
}

.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.22s ease, opacity 0.18s ease;
    max-height: 400px;
    opacity: 1;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}
</style>