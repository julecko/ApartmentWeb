<script setup lang="ts">
import { ref, computed } from 'vue';
import type { NewsItem } from '$types/news';
import { useI18n } from '$lib/i18n';
import NewsBadge from './NewsBadge.vue';
import NewsAttachments from './NewsAttachments.vue';

const props = defineProps<{ item: NewsItem }>();

const { t, localeStore } = useI18n();
const expanded = ref(false);

const title = computed(() => localeStore.locale === 'sk' ? props.item.titleSk : props.item.titleEn);
const summary = computed(() => localeStore.locale === 'sk' ? props.item.summarySk : props.item.summaryEn);
const content = computed(() => localeStore.locale === 'sk' ? props.item.contentSk : props.item.contentEn);

const formattedDate = computed(() => {
    const d = new Date(props.item.date);
    return d.toLocaleDateString('sk-SK', { day: '2-digit', month: '2-digit', year: 'numeric' }).replace(/\s/g, '');
});

function toggle() {
    expanded.value = !expanded.value;
}
</script>

<template>
    <article :class="['card', { 'card--pinned': item.pinned }]">
        <div class="card__header">
            <NewsBadge :type="item.badge" />
            <time class="card__date" :datetime="item.date">
                <span class="card__date-icon" aria-hidden="true">⊟</span>
                {{ formattedDate }}
            </time>
        </div>

        <h2 class="card__title">{{ title }}</h2>
        <p class="card__summary">{{ summary }}</p>

        <div v-if="expanded" class="card__content">
            <p>{{ content }}</p>
        </div>

        <button class="card__toggle" @click="toggle" :aria-expanded="expanded">
            <span>{{ expanded ? t.readLess : t.readMore }}</span>
            <svg class="card__toggle-chevron" :class="{ 'card__toggle-chevron--up': expanded }" width="14" height="14"
                viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <path d="M2.5 5L7 9.5L11.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <NewsAttachments v-if="item.attachments?.length" :attachments="item.attachments" />
    </article>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.card {
    background: $color-surface;
    border: 1px solid $color-border-card;
    border-radius: $radius-lg;
    overflow: hidden;
    transition: box-shadow 0.18s ease;

    &:hover {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
    }

    &--pinned {
        background: $color-surface-pinned;
        border-color: #C8D6EC;
    }

    &__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: $spacing-md $spacing-md $spacing-sm;
    }

    &__date {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: $font-body;
        font-size: $font-size-sm;
        color: $color-text-muted;
        letter-spacing: 0.02em;
    }

    &__date-icon {
        font-size: 0.8rem;
        opacity: 0.7;
    }

    &__title {
        padding: 0 $spacing-md $spacing-sm;
        font-family: $font-display;
        font-size: $font-size-xl;
        font-weight: $font-weight-bold;
        line-height: $line-height-tight;
        color: $color-text-primary;
    }

    &__summary {
        padding: 0 $spacing-md $spacing-md;
        font-size: $font-size-base;
        color: $color-text-secondary;
        line-height: $line-height-relaxed;
    }

    &__content {
        padding: $spacing-md;
        font-size: $font-size-base;
        color: $color-text-secondary;
        line-height: $line-height-relaxed;
        border-top: 1px solid $color-border;
    }

    &__toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 12px $spacing-md;
        background: transparent;
        border: none;
        border-top: 1px solid $color-border;
        font-family: $font-body;
        font-size: $font-size-sm;
        font-weight: $font-weight-medium;
        color: $color-text-link;
        letter-spacing: 0.03em;
        transition: background 0.15s ease;

        &:hover {
            background: rgba(74, 111, 165, 0.05);
        }

        &:focus-visible {
            outline: 2px solid $color-text-link;
            outline-offset: -2px;
        }
    }

    &__toggle-chevron {
        transition: transform 0.2s ease;
        color: $color-text-link;

        &--up {
            transform: rotate(180deg);
        }
    }
}
</style>