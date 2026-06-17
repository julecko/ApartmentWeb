<script setup lang="ts">
import { useI18n } from '$lib/i18n';

defineProps<{ variant: 'public' | 'admin' }>();
const emit = defineEmits<{ logout: [] }>();

const { t, localeStore } = useI18n();
</script>

<template>
    <header class="page-header">
        <div class="page-header__text">
            <p class="page-header__eyebrow">
                {{ variant === 'admin' ? t.adminEyebrow : t.appEyebrow }}
            </p>
            <h1 class="page-header__title">
                <svg width="26" height="26" viewBox="0 0 22 22" fill="none" aria-hidden="true"
                    class="page-header__icon">
                    <rect x="3" y="2" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.4" />
                    <rect x="6" y="6" width="4" height="3" rx="0.8" stroke="currentColor" stroke-width="1.2" />
                    <rect x="12" y="6" width="4" height="3" rx="0.8" stroke="currentColor" stroke-width="1.2" />
                    <rect x="6" y="12" width="4" height="3" rx="0.8" stroke="currentColor" stroke-width="1.2" />
                    <rect x="12" y="12" width="4" height="3" rx="0.8" stroke="currentColor" stroke-width="1.2" />
                </svg>
                {{ variant === 'admin' ? t.adminTitle : t.appTitle }}
            </h1>
            <p v-if="variant === 'public'" class="page-header__subtitle">
                {{ t.appSubtitle }}
            </p>
        </div>

        <div class="page-header__actions">
            <button class="lang-switch" @click="localeStore.toggleLocale" :title="t.currentLang">
                <svg width="15" height="15" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                    <circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.2" />
                    <ellipse cx="9" cy="9" rx="3.2" ry="7.5" stroke="currentColor" stroke-width="1.2" />
                    <path d="M2 6.5h14M2 11.5h14" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                </svg>
                {{ t.currentLang }}
            </button>

            <button v-if="variant === 'admin'" class="logout-btn" @click="emit('logout')">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M5 2H2.5A1.5 1.5 0 0 0 1 3.5v7A1.5 1.5 0 0 0 2.5 12H5" stroke="currentColor"
                        stroke-width="1.3" stroke-linecap="round" />
                    <path d="M9.5 4.5L13 7l-3.5 2.5M13 7H5" stroke="currentColor" stroke-width="1.3"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ t.logout }}
            </button>
        </div>
    </header>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: $spacing-xl $spacing-xl $spacing-lg;

    &__text {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    &__eyebrow {
        font-family: $font-body;
        font-size: $font-size-xs;
        font-weight: $font-weight-semibold;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: $color-text-muted;
    }

    &__title {
        display: flex;
        align-items: center;
        gap: $spacing-sm;
        font-family: $font-display;
        font-size: $font-size-2xl;
        font-weight: $font-weight-bold;
        line-height: $line-height-tight;
        color: $color-text-primary;
        margin-top: 2px;
    }

    &__icon {
        color: $color-text-muted;
        opacity: 0.7;
        flex-shrink: 0;
    }

    &__subtitle {
        font-size: $font-size-sm;
        color: $color-text-secondary;
        margin-top: 2px;
    }

    &__actions {
        display: flex;
        align-items: center;
        gap: $spacing-sm;
    }
}

.lang-switch {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: 1px solid $color-border-card;
    border-radius: $radius-sm;
    background: $color-surface;
    font-family: $font-body;
    font-size: $font-size-xs;
    font-weight: $font-weight-semibold;
    letter-spacing: 0.06em;
    color: $color-text-secondary;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;

    &:hover {
        background: $color-text-primary;
        color: $color-surface;
        border-color: $color-text-primary;
    }

    &:focus-visible {
        outline: 2px solid $color-text-link;
        outline-offset: 2px;
    }
}

.logout-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: none;
    background: transparent;
    font-family: $font-body;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    color: $color-text-secondary;
    cursor: pointer;
    border-radius: $radius-sm;
    transition: color 0.15s, background 0.15s;

    &:hover {
        color: #DC2626;
        background: #FEE2E2;
    }
}
</style>