<script setup lang="ts">
import { useI18n } from "$lib/i18n";

const { t, localeStore } = useI18n();
</script>

<template>
  <header class="page-header">
    <div class="page-header__text">
      <p class="page-header__eyebrow">{{ t.appEyebrow }}</p>
      <h1 class="page-header__title">{{ t.appTitle }}</h1>
      <p class="page-header__subtitle">{{ t.appSubtitle }}</p>
    </div>

    <div class="page-header__actions">
      <button class="lang-switch" @click="localeStore.toggleLocale" :title="t.switchLang">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <circle cx="9" cy="9" r="7.5" stroke="currentColor" stroke-width="1.2" />
          <ellipse
            cx="9"
            cy="9"
            rx="3.2"
            ry="7.5"
            stroke="currentColor"
            stroke-width="1.2"
          />
          <path
            d="M2 6.5h14M2 11.5h14"
            stroke="currentColor"
            stroke-width="1.2"
            stroke-linecap="round"
          />
        </svg>
        <span>{{ t.currentLang }}</span>
      </button>

      <div class="page-header__icon" aria-hidden="true">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
          <rect
            x="8"
            y="6"
            width="24"
            height="28"
            rx="2"
            stroke="currentColor"
            stroke-width="1.5"
          />
          <rect
            x="12"
            y="10"
            width="7"
            height="5"
            rx="1"
            stroke="currentColor"
            stroke-width="1.2"
          />
          <rect
            x="21"
            y="10"
            width="7"
            height="5"
            rx="1"
            stroke="currentColor"
            stroke-width="1.2"
          />
          <rect
            x="12"
            y="19"
            width="7"
            height="5"
            rx="1"
            stroke="currentColor"
            stroke-width="1.2"
          />
          <rect
            x="21"
            y="19"
            width="7"
            height="5"
            rx="1"
            stroke="currentColor"
            stroke-width="1.2"
          />
          <rect
            x="15"
            y="28"
            width="10"
            height="6"
            rx="1"
            stroke="currentColor"
            stroke-width="1.2"
          />
        </svg>
      </div>
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
    gap: $spacing-xs;
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
    font-family: $font-display;
    font-size: $font-size-2xl;
    font-weight: $font-weight-bold;
    line-height: $line-height-tight;
    color: $color-text-primary;
    margin-top: 2px;
  }

  &__subtitle {
    font-size: $font-size-sm;
    color: $color-text-secondary;
    margin-top: 2px;
  }

  &__actions {
    display: flex;
    align-items: center;
    gap: $spacing-md;
  }

  &__icon {
    color: $color-text-muted;
    opacity: 0.65;
    flex-shrink: 0;
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
</style>
