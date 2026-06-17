<script setup lang="ts">
    import { reactive, watch, ref } from 'vue'
    import type { BadgeType, NewsItem, NewsAttachment } from '$types/news'
    import { useI18n } from '$lib/i18n'

    type PendingFile = {
        id: number
        file: File
        name: string
        size: number
        mimeType: string
        url: string
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

    const props = defineProps<{ item: NewsItem }>()
    const emit = defineEmits<{
        save: [payload: EditPayload]
        close: []
    }>()

    const { t } = useI18n()

    const BADGE_OPTIONS: BadgeType[] = ['investment', 'repairFund', 'repair', 'general']

    type FormState = {
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
    }

    const form = reactive<FormState>({
        id: props.item.id,
        badge: props.item.badge,
        date: props.item.date,
        titleSk: props.item.titleSk,
        titleEn: props.item.titleEn,
        summarySk: props.item.summarySk,
        summaryEn: props.item.summaryEn,
        contentSk: props.item.contentSk ?? '',
        contentEn: props.item.contentEn ?? '',
        pinned: !!props.item.pinned,
    })

    const existingAttachments = ref<NewsAttachment[]>([])
    const newFiles = ref<PendingFile[]>([])
    const deletedIds = ref<number[]>([])
    const dragOver = ref(false)
    const fileInput = ref<HTMLInputElement | null>(null)
    let nextTempId = 1

    function resetFromItem(val: NewsItem) {
        form.id = val.id
        form.badge = val.badge
        form.date = val.date
        form.titleSk = val.titleSk
        form.titleEn = val.titleEn
        form.summarySk = val.summarySk
        form.summaryEn = val.summaryEn
        form.contentSk = val.contentSk ?? ''
        form.contentEn = val.contentEn ?? ''
        form.pinned = !!val.pinned

        existingAttachments.value = [...(val.attachments ?? [])]
        newFiles.value = []
        deletedIds.value = []
        nextTempId = 1
    }

    watch(() => props.item, resetFromItem, { immediate: true, deep: true })

    function handleFiles(fileList: FileList | null) {
        if (!fileList) return

        for (const file of Array.from(fileList)) {
            newFiles.value.push({
                id: nextTempId++,
                file,
                name: file.name,
                size: file.size,
                mimeType: file.type,
                url: URL.createObjectURL(file),
            })
        }

        if (fileInput.value) {
            fileInput.value.value = ''
        }
    }

    function onDrop(e: DragEvent) {
        dragOver.value = false
        handleFiles(e.dataTransfer?.files ?? null)
    }

    function openFilePicker() {
        fileInput.value?.click()
    }

    function removeExistingAttachment(id: number) {
        if (!deletedIds.value.includes(id)) {
            deletedIds.value.push(id)
        }

        existingAttachments.value = existingAttachments.value.filter(a => a.id !== id)
    }

    function removeNewFile(id: number) {
        const index = newFiles.value.findIndex(f => f.id === id)
        if (index === -1) return

        URL.revokeObjectURL(newFiles.value[index].url)
        newFiles.value.splice(index, 1)
    }

    function formatSize(bytes: number): string {
        if (bytes < 1024) return `${bytes} B`
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
    }

    function handleSave() {
        emit('save', {
            id: form.id,
            badge: form.badge,
            date: form.date,
            titleSk: form.titleSk,
            titleEn: form.titleEn,
            summarySk: form.summarySk,
            summaryEn: form.summaryEn,
            contentSk: form.contentSk,
            contentEn: form.contentEn,
            pinned: form.pinned,
            newFiles: newFiles.value.map(f => f.file),
            deleteAttachmentIds: deletedIds.value,
        })
    }

    function onOverlayClick(e: MouseEvent) {
        if ((e.target as HTMLElement).classList.contains('modal-overlay')) {
            emit('close')
        }
    }
</script>

<template>
    <Teleport to="body">
        <div class="modal-overlay" @click="onOverlayClick" role="dialog" aria-modal="true">
            <div class="modal">
                <div class="modal__header">
                    <h2 class="modal__title">{{ t.editPost }}</h2>
                    <button class="modal__close" @click="emit('close')" :aria-label="t.cancel">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                <div class="modal__body">
                    <div class="form-grid">
                        <!-- Title SK -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldTitleSk }}</label>
                            <input v-model="form.titleSk" class="form-field__input"
                                :placeholder="t.fieldTitleSkPlaceholder" />
                        </div>

                        <!-- Title EN -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldTitleEn }}</label>
                            <input v-model="form.titleEn" class="form-field__input"
                                :placeholder="t.fieldTitleEnPlaceholder" />
                        </div>

                        <!-- Badge + Date -->
                        <div class="form-field">
                            <label class="form-field__label">{{ t.fieldCategory }}</label>
                            <div class="form-field__select-wrap">
                                <select v-model="form.badge" class="form-field__select">
                                    <option v-for="b in BADGE_OPTIONS" :key="b" :value="b">
                                        {{ t[`badge${b.charAt(0).toUpperCase() + b.slice(1)}` as keyof typeof t] }}
                                    </option>
                                </select>
                                <svg class="form-field__select-icon" width="12" height="12" viewBox="0 0 12 12"
                                    fill="none" aria-hidden="true">
                                    <path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-field__label">{{ t.fieldDate }}</label>
                            <input v-model="form.date" type="date" class="form-field__input" />
                        </div>

                        <!-- Summary SK -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldSummarySk }}</label>
                            <input v-model="form.summarySk" class="form-field__input"
                                :placeholder="t.fieldSummarySkPlaceholder" />
                        </div>

                        <!-- Summary EN -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldSummaryEn }}</label>
                            <input v-model="form.summaryEn" class="form-field__input"
                                :placeholder="t.fieldSummaryEnPlaceholder" />
                        </div>

                        <!-- Content SK -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldContentSk }}</label>
                            <textarea v-model="form.contentSk" class="form-field__textarea"
                                :placeholder="t.fieldContentSkPlaceholder" rows="3" />
                        </div>

                        <!-- Content EN -->
                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldContentEn }}</label>
                            <textarea v-model="form.contentEn" class="form-field__textarea"
                                :placeholder="t.fieldContentEnPlaceholder" rows="3" />
                        </div>

                        <div class="form-field form-field--wide">
                            <label class="form-field__label">{{ t.fieldAttachments }}</label>

                            <div class="dropzone" :class="{ 'dropzone--over': dragOver }"
                                @dragover.prevent="dragOver = true" @dragleave="dragOver = false" @drop.prevent="onDrop"
                                @click="openFilePicker">
                                <input ref="fileInput" type="file" multiple class="dropzone__input"
                                    @change="e => handleFiles((e.target as HTMLInputElement).files)">
                                <svg width="20" height="20" viewBox="0 0 22 22" fill="none" aria-hidden="true"
                                    class="dropzone__icon">
                                    <path d="M19 13.5V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5" stroke="currentColor"
                                        stroke-width="1.4" stroke-linecap="round" />
                                    <path d="M11 3v10M7.5 6.5L11 3l3.5 3.5" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="dropzone__text">{{ t.dropzoneText }}</p>
                            </div>

                            <ul v-if="existingAttachments.length" class="upload-list">
                                <li v-for="file in existingAttachments" :key="file.id" class="upload-list__item">
                                    <span class="upload-list__name">{{ file.name }}</span>
                                    <span class="upload-list__size">{{ formatSize(file.size) }}</span>
                                    <button type="button" class="upload-list__remove"
                                        @click="removeExistingAttachment(file.id)" :aria-label="`Remove ${file.name}`">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                            <path d="M2 2l8 8M10 2L2 10" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </li>
                            </ul>

                            <ul v-if="newFiles.length" class="upload-list">
                                <li v-for="file in newFiles" :key="file.id" class="upload-list__item">
                                    <span class="upload-list__name">{{ file.name }}</span>
                                    <span class="upload-list__size">{{ formatSize(file.size) }}</span>
                                    <button type="button" class="upload-list__remove" @click="removeNewFile(file.id)"
                                        :aria-label="`Remove ${file.name}`">
                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true">
                                            <path d="M2 2l8 8M10 2L2 10" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Pinned -->
                    <label class="pin-check">
                        <input v-model="form.pinned" type="checkbox" class="pin-check__input" />
                        <span class="pin-check__box" aria-hidden="true">
                            <svg v-if="form.pinned" width="10" height="10" viewBox="0 0 10 10" fill="none">
                                <path d="M1.5 5L4 7.5L8.5 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                        {{ t.pinPost }}
                    </label>
                </div>

                <div class="modal__footer">
                    <button class="btn btn--primary" @click="handleSave">{{ t.save }}</button>
                    <button class="btn btn--ghost" @click="emit('close')">{{ t.cancel }}</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped lang="scss">
    @use '$styles/colors' as *;

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        padding: $spacing-md;
    }

    .modal {
        background: $color-surface;
        border: 1px solid $color-border-card;
        border-radius: $radius-lg;
        width: 100%;
        max-width: 660px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);

        &__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: $spacing-lg $spacing-lg $spacing-md;
            border-bottom: 1px solid $color-border;
            flex-shrink: 0;
        }

        &__title {
            font-family: $font-display;
            font-size: $font-size-lg;
            font-weight: $font-weight-bold;
            color: $color-text-primary;
        }

        &__close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            color: $color-text-muted;
            border-radius: $radius-sm;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;

            &:hover {
                background: $color-bg;
                color: $color-text-primary;
            }
        }

        &__body {
            padding: $spacing-lg;
            overflow-y: auto;
            flex: 1;
        }

        &__footer {
            display: flex;
            gap: $spacing-sm;
            padding: $spacing-md $spacing-lg;
            border-top: 1px solid $color-border;
            flex-shrink: 0;
        }
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: $spacing-md;
        margin-bottom: $spacing-md;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 6px;

        &--wide {
            grid-column: 1 / -1;
        }

        &__label {
            font-size: $font-size-xs;
            font-weight: $font-weight-semibold;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: $color-text-muted;
        }

        &__input,
        &__textarea,
        &__select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid $color-border-card;
            border-radius: $radius-sm;
            background: $color-bg;
            font-family: $font-body;
            font-size: $font-size-base;
            color: $color-text-primary;
            transition: border-color 0.15s;
            appearance: none;

            &::placeholder {
                color: $color-text-muted;
            }

            &:focus {
                outline: none;
                border-color: $color-text-link;
                background: $color-surface;
            }
        }

        &__textarea {
            resize: vertical;
        }

        &__select-wrap {
            position: relative;
        }

        &__select {
            cursor: pointer;
            padding-right: 32px;
        }

        &__select-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: $color-text-muted;
            pointer-events: none;
        }
    }

    .dropzone {
        border: 2px dashed $color-border-card;
        border-radius: $radius-md;
        padding: $spacing-md $spacing-lg;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        position: relative;

        &--over {
            border-color: $color-text-link;
            background: rgba(74, 111, 165, 0.04);
        }

        &:hover {
            border-color: $color-text-secondary;
        }

        &__input {
            position: absolute;
            inset: 0;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        &__icon {
            color: $color-text-muted;
            margin-bottom: 6px;
        }

        &__text {
            font-size: $font-size-sm;
            color: $color-text-secondary;
        }
    }

    .upload-list {
        list-style: none;
        margin-top: $spacing-sm;
        display: flex;
        flex-direction: column;
        gap: 6px;

        &__item {
            display: flex;
            align-items: center;
            gap: $spacing-sm;
            padding: 7px 10px;
            background: $color-bg;
            border: 1px solid $color-border;
            border-radius: $radius-sm;
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

        &__remove {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border: none;
            background: transparent;
            color: $color-text-muted;
            border-radius: $radius-sm;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.15s, color 0.15s;

            &:hover {
                background: #FEE2E2;
                color: #DC2626;
            }
        }
    }

    .pin-check {
        display: inline-flex;
        align-items: center;
        gap: $spacing-sm;
        font-size: $font-size-sm;
        color: $color-text-secondary;
        cursor: pointer;
        user-select: none;

        &__input {
            display: none;
        }

        &__box {
            width: 18px;
            height: 18px;
            border: 2px solid $color-border-card;
            border-radius: 4px;
            background: $color-bg;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.15s, border-color 0.15s;
        }
    }

    .pin-check:has(.pin-check__input:checked) .pin-check__box {
        background: $color-text-primary;
        border-color: $color-text-primary;
    }

    .btn {
        padding: 9px 20px;
        border-radius: $radius-sm;
        font-family: $font-body;
        font-size: $font-size-sm;
        font-weight: $font-weight-semibold;
        border: 1px solid transparent;
        cursor: pointer;
        transition: background 0.15s, color 0.15s, border-color 0.15s;

        &--primary {
            background: $color-text-primary;
            color: $color-surface;

            &:hover {
                background: #333;
            }
        }

        &--ghost {
            background: transparent;
            color: $color-text-secondary;
            border-color: $color-border-card;

            &:hover {
                background: $color-bg;
                color: $color-text-primary;
            }
        }
    }
</style>