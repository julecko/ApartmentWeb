<script setup lang="ts">
import { computed } from 'vue';
import type { NewsItem } from '$types/news';
import { useI18n } from '$lib/i18n';
import NewsHeader from '$components/news/NewsHeader.vue';
import NewsSection from '$components/news/NewsSection.vue';
import NewsCard from '$components/news/NewsCard.vue';

const { t } = useI18n();

// ── Replace with API / store data ────────────────────────────────────────────
const items: NewsItem[] = [
    {
        id: 1,
        badge: 'investment',
        date: '2026-06-10',
        pinned: true,
        titleSk: 'Výmena výťahu — schválené',
        titleEn: 'Elevator replacement — approved',
        summarySk: 'Vlastníci odhlasovali financovanie nového výťahu za 1 200 000 Kč.',
        summaryEn: 'Owners voted to finance a new elevator for 1,200,000 CZK.',
        contentSk: 'Na schôdzi vlastníkov dňa 10. 6. 2026 bolo nadpolovičnou väčšinou schválené financovanie nového výťahu. Práce začnú v septembri 2026.',
        contentEn: 'At the owners\' meeting on 10 June 2026, financing for a new elevator was approved by majority vote. Work will begin in September 2026.',
        attachments: [
            { id: 1, name: 'zapisnica-schodzа.pdf', size: 204800, mimeType: 'application/pdf', url: '#' },
            { id: 2, name: 'cenova-ponuka-vytah.pdf', size: 512000, mimeType: 'application/pdf', url: '#' },
        ],
    },
    {
        id: 2,
        badge: 'repairFund',
        date: '2026-06-05',
        titleSk: 'Navýšenie príspevku do fondu opráv od júla',
        titleEn: 'Repair fund contribution increase from July',
        summarySk: 'Mesačný príspevok do fondu opráv sa od 1. júla zvyšuje o 15 %.',
        summaryEn: 'The monthly repair fund contribution will increase by 15% from 1 July.',
        contentSk: 'Z dôvodu rastúcich nákladov na údržbu a plánovaných investícií sa mesačný príspevok do fondu opráv zvyšuje o 15 %.',
        contentEn: 'Due to rising maintenance costs and planned investments, the monthly repair fund contribution will increase by 15%.',
        attachments: [],
    },
    {
        id: 3,
        badge: 'repair',
        date: '2026-05-28',
        titleSk: 'Oprava strechy — 2. etapa dokončená',
        titleEn: 'Roof repair — 2nd phase completed',
        summarySk: 'Druhá etapa rekonštrukcie strechy úspešne dokončená, tretia na jeseň.',
        summaryEn: 'The second phase of roof reconstruction was completed successfully; the third phase is planned for autumn.',
        contentSk: 'Zhotoviteľ dokončil druhú etapu opravy strechy v plánovanom termíne a bez viacnákladov.',
        contentEn: 'The contractor completed the second phase of roof repairs on schedule and within budget.',
        attachments: [
            { id: 3, name: 'fotodokumentacia-strecha.jpg', size: 1048576, mimeType: 'image/jpeg', url: '#' },
        ],
    },
    {
        id: 4,
        badge: 'general',
        date: '2026-05-20',
        titleSk: 'Zmena správcovskej spoločnosti',
        titleEn: 'Change of management company',
        summarySk: 'Od 1. júla 2026 prevezme správu domu nová spoločnosť Správa SK s.r.o.',
        summaryEn: 'From 1 July 2026, building management will be taken over by Správa SK s.r.o.',
        contentSk: 'Po výberovom konaní bola ako nový správca domu zvolená spoločnosť Správa SK s.r.o.',
        contentEn: 'Following a competitive tender, Správa SK s.r.o. was selected as the new building manager.',
        attachments: [],
    },
];

const pinned = computed(() => items.filter(i => i.pinned));
const others = computed(() => items.filter(i => !i.pinned));
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