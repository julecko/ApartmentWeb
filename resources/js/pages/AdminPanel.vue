<script setup lang="ts">
import { ref, computed } from 'vue';
import type { NewsItem } from '$types/news';
import { useI18n } from '$lib/i18n';
import NewsHeader from '$components/news/NewsHeader.vue';
import NewsForm from '$components/news/NewsForm.vue';
import NewsAdminCard from '$components/news/NewsAdminCard.vue';
import NewsEditModal from '$components/news/NewsEditModal.vue';

const { t } = useI18n();

// ── Replace with store/API ───────────────────────────────────────────────────
const items = ref<NewsItem[]>([
    {
        id: 1,
        badge: 'investment',
        date: '2026-06-10',
        pinned: true,
        titleSk: 'Výmena výťahu — schválené',
        titleEn: 'Elevator replacement — approved',
        summarySk: 'Vlastníci odhlasovali financovanie nového výťahu za 1 200 000 Kč.',
        summaryEn: 'Owners voted to finance a new elevator for 1,200,000 CZK.',
        contentSk: '',
        contentEn: '',
        attachments: [],
    },
    {
        id: 2,
        badge: 'repairFund',
        date: '2026-06-05',
        titleSk: 'Navýšenie príspevku do fondu opráv od júla',
        titleEn: 'Repair fund contribution increase from July',
        summarySk: 'Mesačný príspevok do fondu opráv sa od 1. júla zvyšuje o 15 %.',
        summaryEn: 'The monthly repair fund contribution will increase by 15% from 1 July.',
        contentSk: '',
        contentEn: '',
        attachments: [],
    },
    {
        id: 3,
        badge: 'repair',
        date: '2026-05-28',
        titleSk: 'Oprava strechy — 2. etapa dokončená',
        titleEn: 'Roof repair — 2nd phase completed',
        summarySk: 'Druhá etapa rekonštrukcie strechy úspešne dokončená, tretia na jeseň.',
        summaryEn: 'The second phase of roof reconstruction was completed successfully.',
        contentSk: '',
        contentEn: '',
        attachments: [],
    },
    {
        id: 4,
        badge: 'general',
        date: '2026-05-20',
        titleSk: 'Schôdza vlastníkov — 25. júna 2026',
        titleEn: 'Owners\' meeting — 25 June 2026',
        summarySk: 'Schôdza sa koná 25. júna od 18:00 v prízemí domu.',
        summaryEn: 'The meeting takes place on 25 June at 18:00 on the ground floor.',
        contentSk: '',
        contentEn: '',
        attachments: [],
    },
]);

let nextId = items.value.length + 1;

// ── Add form ─────────────────────────────────────────────────────────────────
const showForm = ref(false);

function handleSubmit(data: Omit<NewsItem, 'id'>) {
    items.value.unshift({ ...data, id: nextId++ });
    showForm.value = false;
}

function handleCancel() {
    showForm.value = false;
}

// ── Edit modal ────────────────────────────────────────────────────────────────
const editingItem = ref<NewsItem | null>(null);

function handleEdit(id: number) {
    editingItem.value = items.value.find(i => i.id === id) ?? null;
}

function handleSave(updated: NewsItem) {
    const idx = items.value.findIndex(i => i.id === updated.id);
    if (idx !== -1) items.value[idx] = updated;
    editingItem.value = null;
}

// ── Pin / delete ──────────────────────────────────────────────────────────────
function handlePin(id: number) {
    const item = items.value.find(i => i.id === id);
    if (item) item.pinned = !item.pinned;
}

function handleDelete(id: number) {
    items.value = items.value.filter(i => i.id !== id);
}

function handleLogout() {
    // implement logout logic
}

const sorted = computed(() => [
    ...items.value.filter(i => i.pinned),
    ...items.value.filter(i => !i.pinned),
]);
</script>

<template>
    <div class="admin-page">
        <NewsHeader variant="admin" @logout="handleLogout" />

        <div class="admin-page__divider" role="separator" />

        <main class="admin-page__content">

            <!-- Collapsible add form -->
            <Transition name="expand">
                <div v-if="showForm" class="admin-page__form-wrap">
                    <NewsForm @submit="handleSubmit" @cancel="handleCancel" />
                </div>
            </Transition>

            <button v-if="!showForm" class="add-btn" @click="showForm = true">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M7 1v12M1 7h12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                {{ t.addPost }}
            </button>

            <!-- Post list -->
            <div class="admin-page__list">
                <NewsAdminCard v-for="item in sorted" :key="item.id" :item="item" @edit="handleEdit" @pin="handlePin"
                    @delete="handleDelete" />
                <p v-if="!sorted.length" class="admin-page__empty">—</p>
            </div>
        </main>

        <!-- Edit modal -->
        <NewsEditModal v-if="editingItem" :item="editingItem" @save="handleSave" @close="editingItem = null" />
    </div>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.admin-page {
    max-width: 820px;
    margin: 0 auto;

    &__divider {
        height: 1px;
        background: $color-border;
        margin: 0 $spacing-xl;
    }

    &__content {
        padding: $spacing-xl;
        display: flex;
        flex-direction: column;
        gap: $spacing-md;
    }

    &__form-wrap {
        overflow: hidden;
    }

    &__list {
        display: flex;
        flex-direction: column;
        gap: $spacing-sm;
    }

    &__empty {
        font-size: $font-size-sm;
        color: $color-text-muted;
        text-align: center;
        padding: $spacing-xl;
    }
}

.add-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: $spacing-sm;
    width: 100%;
    padding: 14px;
    background: transparent;
    border: 2px dashed $color-text-link;
    border-radius: $radius-md;
    font-family: $font-body;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    color: $color-text-link;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;

    &:hover {
        background: rgba(74, 111, 165, 0.05);
        border-color: darken(#4A6FA5, 10%);
    }

    &:focus-visible {
        outline: 2px solid $color-text-link;
        outline-offset: 2px;
    }
}

.expand-enter-active,
.expand-leave-active {
    transition: max-height 0.28s ease, opacity 0.2s ease;
    max-height: 900px;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
}
</style>