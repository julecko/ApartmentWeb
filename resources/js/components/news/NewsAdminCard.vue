<script setup lang="ts">
import { computed } from 'vue';
import type { NewsItem } from '$types/news';
import { useI18n } from '$lib/i18n';
import NewsBadge from './NewsBadge.vue';

const props = defineProps<{ item: NewsItem }>();
const emit = defineEmits<{
    pin: [id: number];
    delete: [id: number];
    edit: [id: number];
}>();

const { t, localeStore } = useI18n();

const title = computed(() => localeStore.locale === 'sk' ? props.item.titleSk : props.item.titleEn);
const summary = computed(() => localeStore.locale === 'sk' ? props.item.summarySk : props.item.summaryEn);

const formattedDate = computed(() => {
    const d = new Date(props.item.date);
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\s/g, '');
});
</script>

<template>
    <div class="admin-card">
        <div class="admin-card__main">
            <div class="admin-card__meta">
                <NewsBadge :type="item.badge" />
                <span v-if="item.pinned" class="admin-card__pinned-tag">{{ t.pinned }}</span>
            </div>

            <h3 class="admin-card__title">{{ title }}</h3>
            <p class="admin-card__summary">{{ summary }}</p>

            <div class="admin-card__footer">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                    <rect x="1" y="2" width="10" height="9" rx="1.2" stroke="currentColor" stroke-width="1.2" />
                    <path d="M4 1v2M8 1v2M1 5h10" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>
                <time :datetime="item.date">{{ formattedDate }}</time>
                <span v-if="item.attachments?.length" class="admin-card__attachments">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                        <path
                            d="M10 5.5L6 9.5C4.9 10.6 3.1 10.6 2 9.5C0.9 8.4 0.9 6.6 2 5.5L6.5 1C7.3 0.2 8.5 0.2 9.3 1C10.1 1.8 10.1 3 9.3 3.8L4.8 8.3C4.4 8.7 3.7 8.7 3.3 8.3C2.9 7.9 2.9 7.2 3.3 6.8L7.5 2.5"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                    </svg>
                    {{ item.attachments.length }}
                </span>
            </div>
        </div>

        <div class="admin-card__actions">
            <button class="admin-card__btn admin-card__btn--edit" @click="emit('edit', item.id)"
                :aria-label="t.editPost" :title="t.editPost">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M9.5 2.5L11.5 4.5L4.5 11.5H2.5V9.5L9.5 2.5Z" stroke="currentColor" stroke-width="1.3"
                        stroke-linejoin="round" />
                    <path d="M8 4L10 6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                </svg>
            </button>

            <button class="admin-card__btn admin-card__btn--pin" :class="{ 'admin-card__btn--pinned': item.pinned }"
                @click="emit('pin', item.id)" :aria-label="item.pinned ? t.unpin : t.pin"
                :title="item.pinned ? t.unpin : t.pin">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M9.5 1.5L12.5 4.5L8 9L5 9L5 6L9.5 1.5Z" stroke="currentColor" stroke-width="1.3"
                        stroke-linejoin="round" :fill="item.pinned ? 'currentColor' : 'none'" />
                    <path d="M2 12L5 9" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                </svg>
            </button>

            <button class="admin-card__btn admin-card__btn--delete" @click="emit('delete', item.id)"
                :aria-label="t.delete" :title="t.delete">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M2 4h10M5 4V2.5h4V4M5.5 6.5v4M8.5 6.5v4M3 4l.7 7.5h6.6L11 4" stroke="currentColor"
                        stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.admin-card {
    display: flex;
    align-items: flex-start;
    gap: $spacing-md;
    padding: $spacing-md;
    background: $color-surface;
    border: 1px solid $color-border-card;
    border-radius: $radius-md;
    transition: box-shadow 0.15s;

    &:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    &__main {
        flex: 1;
        min-width: 0;
    }

    &__meta {
        display: flex;
        align-items: center;
        gap: $spacing-sm;
        margin-bottom: $spacing-sm;
    }

    &__pinned-tag {
        font-size: $font-size-xs;
        font-weight: $font-weight-semibold;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: $color-text-muted;
    }

    &__title {
        font-family: $font-display;
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
        color: $color-text-primary;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__summary {
        font-size: $font-size-sm;
        color: $color-text-secondary;
        margin-bottom: $spacing-sm;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__footer {
        display: flex;
        align-items: center;
        gap: $spacing-sm;
        font-size: $font-size-xs;
        color: $color-text-muted;
    }

    &__attachments {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    &__actions {
        display: flex;
        flex-direction: column;
        gap: $spacing-xs;
        flex-shrink: 0;
    }

    &__btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: 1px solid $color-border;
        border-radius: $radius-sm;
        background: $color-bg;
        cursor: pointer;
        color: $color-text-muted;
        transition: background 0.15s, color 0.15s, border-color 0.15s;

        &--edit {
            &:hover {
                background: #EFF6FF;
                color: $color-text-link;
                border-color: #BFDBFE;
            }
        }

        &--pin {

            &:hover,
            &--pinned {
                color: $color-text-primary;
                border-color: $color-text-primary;
                background: rgba(0, 0, 0, 0.04);
            }
        }

        &--delete {
            &:hover {
                background: #FEE2E2;
                color: #DC2626;
                border-color: #FCA5A5;
            }
        }
    }
}
</style>