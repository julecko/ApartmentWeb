<script setup lang="ts">
import { computed, ref } from 'vue';
import type { NewsItem } from '$types/news';
import { useI18n } from '$lib/i18n';
import NewsHeader from '$components/news/NewsHeader.vue';
import NewsSection from '$components/news/NewsSection.vue';
import NewsCard from '$components/news/NewsCard.vue';
import { usePage } from '@inertiajs/vue3';

const { t } = useI18n();

// ── Replace with API / store data ────────────────────────────────────────────
const page = usePage<{ items: { data: NewsItem[] } }>()
console.log(page);

const items = computed(() => page.props.items.data)

const pinned = computed(() =>
    items.value.filter(i => i.pinned)
)

const others = computed(() =>
    items.value.filter(i => !i.pinned)
)
</script>

<template>
    <div class="news-page">
        <NewsHeader variant="public" />

        <div class="news-page__divider" role="separator" />

        <main class="news-page__content">
            <NewsSection v-if="pinned.length" :label="t.pinned">
                <NewsCard v-for="item in pinned" :key="item.id" :item="item" />
            </NewsSection>

            <NewsSection v-if="others.length" :label="t.others">
                <NewsCard v-for="item in others" :key="item.id" :item="item" />
            </NewsSection>
        </main>
    </div>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.news-page {
    max-width: 820px;
    margin: 0 auto;

    &__divider {
        height: 1px;
        background: $color-border;
        margin: 0 $spacing-xl;
    }

    &__content {
        display: flex;
        flex-direction: column;
        gap: $spacing-xl;
        padding: $spacing-xl;
    }
}

@media (max-width: 600px) {
    .news-page {
        &__divider {
            margin: 0 $spacing-md;
        }

        &__content {
            padding: $spacing-lg $spacing-md;
            gap: $spacing-lg;
        }
    }
}
</style>