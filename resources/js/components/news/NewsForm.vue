<script setup lang="ts">
import { ref, reactive } from 'vue';
import type { BadgeType, NewsItem, NewsAttachment } from '$types/news';
import { useI18n } from '$lib/i18n';

type SubmitPayload = Omit<NewsItem, 'id'> & {
    files: File[];
    attachments: NewsAttachment[];
};

const emit = defineEmits<{
    submit: [item: SubmitPayload];
    cancel: [];
}>();

const { t } = useI18n();

const BADGE_OPTIONS: BadgeType[] = ['investment', 'repairFund', 'repair', 'general'];

const files = ref<File[]>([]);

const form = reactive({
    titleSk: '',
    titleEn: '',
    summarySk: '',
    summaryEn: '',
    contentSk: '',
    contentEn: '',
    badge: 'general' as BadgeType,
    date: new Date().toISOString().slice(0, 10),
    pinned: false,
});

const dragOver = ref(false);
const attachments = ref<NewsAttachment[]>([]);
let nextId = 1;

function handleFiles(fileList: FileList | null) {
    if (!fileList) return;

    for (const file of Array.from(fileList)) {
        files.value.push(file);

        attachments.value.push({
            id: nextId++,
            name: file.name,
            size: file.size,
            mimeType: file.type,
            url: URL.createObjectURL(file),
        });
    }
}

function onDrop(e: DragEvent) {
    dragOver.value = false;
    handleFiles(e.dataTransfer?.files ?? null);
}

function removeAttachment(id: number) {
    attachments.value = attachments.value.filter(a => a.id !== id);
}

function formatSize(bytes: number): string {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function handleSubmit() {
    emit('submit', {
        ...form,
        attachments: attachments.value,
        files: files.value,
    });
    resetForm();
}

function resetForm() {
    Object.assign(form, {
        titleSk: '', titleEn: '', summarySk: '', summaryEn: '',
        contentSk: '', contentEn: '',
        badge: 'general', date: new Date().toISOString().slice(0, 10), pinned: false,
    });
    attachments.value = [];
}
</script>

<template>
    <div class="form-card">
        <h2 class="form-card__title">{{ t.formTitle }}</h2>

        <div class="form-grid">
            <!-- Title SK -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldTitleSk }}</label>
                <input v-model="form.titleSk" class="form-field__input" :placeholder="t.fieldTitleSkPlaceholder" />
            </div>

            <!-- Title EN -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldTitleEn }}</label>
                <input v-model="form.titleEn" class="form-field__input" :placeholder="t.fieldTitleEnPlaceholder" />
            </div>

            <!-- Badge + Date row -->
            <div class="form-field">
                <label class="form-field__label">{{ t.fieldCategory }}</label>
                <div class="form-field__select-wrap">
                    <select v-model="form.badge" class="form-field__select">
                        <option v-for="b in BADGE_OPTIONS" :key="b" :value="b">
                            {{ t[`badge${b.charAt(0).toUpperCase() + b.slice(1)}` as keyof typeof t] }}
                        </option>
                    </select>
                    <svg class="form-field__select-icon" width="12" height="12" viewBox="0 0 12 12" fill="none"
                        aria-hidden="true">
                        <path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
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
                <input v-model="form.summarySk" class="form-field__input" :placeholder="t.fieldSummarySkPlaceholder" />
            </div>

            <!-- Summary EN -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldSummaryEn }}</label>
                <input v-model="form.summaryEn" class="form-field__input" :placeholder="t.fieldSummaryEnPlaceholder" />
            </div>

            <!-- Content SK -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldContentSk }}</label>
                <textarea v-model="form.contentSk" class="form-field__textarea"
                    :placeholder="t.fieldContentSkPlaceholder" rows="4" />
            </div>

            <!-- Content EN -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldContentEn }}</label>
                <textarea v-model="form.contentEn" class="form-field__textarea"
                    :placeholder="t.fieldContentEnPlaceholder" rows="4" />
            </div>

            <!-- Attachments dropzone -->
            <div class="form-field form-field--wide">
                <label class="form-field__label">{{ t.fieldAttachments }}</label>
                <div class="dropzone" :class="{ 'dropzone--over': dragOver }" @dragover.prevent="dragOver = true"
                    @dragleave="dragOver = false" @drop.prevent="onDrop"
                    @click="($refs.fileInput as HTMLInputElement).click()">
                    <input ref="fileInput" type="file" multiple class="dropzone__input"
                        @change="e => handleFiles((e.target as HTMLInputElement).files)" />
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" aria-hidden="true"
                        class="dropzone__icon">
                        <path d="M19 13.5V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3.5" stroke="currentColor"
                            stroke-width="1.4" stroke-linecap="round" />
                        <path d="M11 3v10M7.5 6.5L11 3l3.5 3.5" stroke="currentColor" stroke-width="1.4"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="dropzone__text">{{ t.dropzoneText }}</p>
                    <p class="dropzone__hint">{{ t.dropzoneHint }}</p>
                </div>

                <ul v-if="attachments.length" class="upload-list">
                    <li v-for="file in attachments" :key="file.id" class="upload-list__item">
                        <span class="upload-list__name">{{ file.name }}</span>
                        <span class="upload-list__size">{{ formatSize(file.size) }}</span>
                        <button class="upload-list__remove" @click="removeAttachment(file.id)"
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

        <!-- Pinned checkbox -->
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

        <div class="form-actions">
            <button class="btn btn--primary" @click="handleSubmit">{{ t.publish }}</button>
            <button class="btn btn--ghost" @click="emit('cancel')">{{ t.cancel }}</button>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.form-card {
    background: $color-surface;
    border: 1px solid $color-border-card;
    border-radius: $radius-lg;
    padding: $spacing-lg;
    margin-bottom: $spacing-lg;

    &__title {
        font-family: $font-display;
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
        color: $color-text-primary;
        margin-bottom: $spacing-lg;
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
    padding: $spacing-lg;
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
        margin-bottom: $spacing-sm;
    }

    &__text {
        font-size: $font-size-sm;
        color: $color-text-secondary;
        margin-bottom: 4px;
    }

    &__hint {
        font-size: $font-size-xs;
        color: $color-text-muted;
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
    margin-bottom: $spacing-lg;
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

        .pin-check__input:checked~& {
            background: $color-text-primary;
            border-color: $color-text-primary;
        }
    }
}

// Vue doesn't scope sibling selectors well — use :deep workaround
.pin-check:has(.pin-check__input:checked) .pin-check__box {
    background: $color-text-primary;
    border-color: $color-text-primary;
}

.form-actions {
    display: flex;
    gap: $spacing-sm;
}

.btn {
    padding: 9px 20px;
    border-radius: $radius-sm;
    font-family: $font-body;
    font-size: $font-size-sm;
    font-weight: $font-weight-semibold;
    border: 1px solid transparent;
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