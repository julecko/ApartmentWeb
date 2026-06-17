<script setup lang="ts">
import { ref, computed } from 'vue'
import type { BadgeType, NewsItem } from '$types/news'
import { useI18n } from '$lib/i18n'
import NewsHeader from '$components/news/NewsHeader.vue'
import NewsForm from '$components/news/NewsForm.vue'
import NewsAdminCard from '$components/news/NewsAdminCard.vue'
import NewsEditModal from '$components/news/NewsEditModal.vue'
import { usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const { t } = useI18n()

type PageProps = {
    items: {
        data: NewsItem[]
    }
}

type CreatePayload = {
    badge: BadgeType
    date: string
    titleSk: string
    titleEn: string
    summarySk: string
    summaryEn: string
    contentSk: string
    contentEn: string
    pinned: boolean
    files: File[]
}

type EditPayload = {
    id: number
    badge: BadgeType
    date: string
    titleSk: string
    titleEn: string
    summarySk: string
    summaryEn: string
    contentSk: string
    contentEn: string
    pinned: boolean
    newFiles: File[]
    deleteAttachmentIds: number[]
}

const page = usePage<PageProps>()
const items = computed(() => page.props.items.data)

const showForm = ref(false)
const editingItem = ref<NewsItem | null>(null)

function handleSubmit(data: CreatePayload) {
    const formData = new FormData()

    formData.append('titleSk', data.titleSk)
    formData.append('titleEn', data.titleEn)
    formData.append('summarySk', data.summarySk)
    formData.append('summaryEn', data.summaryEn)
    formData.append('contentSk', data.contentSk ?? '')
    formData.append('contentEn', data.contentEn ?? '')
    formData.append('badge', data.badge)
    formData.append('date', data.date)
    formData.append('pinned', data.pinned ? '1' : '0')

    data.files.forEach((file: File) => {
        formData.append('attachments[]', file)
    })

    router.post(route('admin.news.store'), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['items'] })
            showForm.value = false
        },
    })
}

function handleCancel() {
    showForm.value = false
}

function handleEdit(id: number) {
    editingItem.value = items.value.find(i => i.id === id) ?? null
}

function handleSave(data: EditPayload) {
    const formData = new FormData()

    formData.append('titleSk', data.titleSk)
    formData.append('titleEn', data.titleEn)
    formData.append('summarySk', data.summarySk)
    formData.append('summaryEn', data.summaryEn)
    formData.append('contentSk', data.contentSk ?? '')
    formData.append('contentEn', data.contentEn ?? '')
    formData.append('badge', data.badge)
    formData.append('date', data.date)
    formData.append('pinned', data.pinned ? '1' : '0')

    data.newFiles.forEach((file: File) => {
        formData.append('attachments[]', file)
    })

    data.deleteAttachmentIds.forEach((id: number) => {
        formData.append('deleteAttachmentIds[]', String(id))
    })

    router.post(route('admin.news.update', data.id), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['items'] })
            editingItem.value = null
        },
    })
}

function handlePin(id: number) {
    router.patch(route('admin.news.pin', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['items'] })
        },
    })
}

function handleDelete(id: number) {
    router.delete(route('admin.news.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['items'] })
        },
    })
}

function handleLogout() {
    router.post(route('admin.logout'))
}

const sorted = computed(() => [
    ...items.value.filter(i => i.pinned),
    ...items.value.filter(i => !i.pinned),
])
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