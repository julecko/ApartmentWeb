<script setup lang="ts">
import type { BadgeType } from '$types/news';
import { useI18n } from '$lib/i18n';

const props = defineProps<{ type: BadgeType }>();

const { t } = useI18n();

const ICONS: Record<BadgeType, string> = {
  investment: '↗',
  repairFund: '🏦',
  repair:     '🔧',
  general:    '📋',
};

const labelMap: Record<BadgeType, () => string> = {
  investment: () => t.value.badgeInvestment,
  repairFund: () => t.value.badgeRepairFund,
  repair:     () => t.value.badgeRepair,
  general:    () => t.value.badgeGeneral,
};
</script>

<template>
  <span :class="['badge', `badge--${props.type}`]">
    <span class="badge__icon" aria-hidden="true">{{ ICONS[props.type] }}</span>
    {{ labelMap[props.type]() }}
  </span>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.badge {
  display: inline-flex;
  align-items: center;
  gap: $spacing-xs;
  padding: 3px 10px;
  border-radius: $radius-sm;
  font-family: $font-body;
  font-size: $font-size-xs;
  font-weight: $font-weight-semibold;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  line-height: 1.6;

  &__icon {
    font-style: normal;
    font-size: 0.75rem;
  }

  &--investment {
    background: $color-badge-investice-bg;
    color: $color-badge-investice-text;
  }
  &--repairFund {
    background: $color-badge-fond-bg;
    color: $color-badge-fond-text;
  }
  &--repair {
    background: $color-badge-oprava-bg;
    color: $color-badge-oprava-text;
  }
  &--general {
    background: $color-badge-obecne-bg;
    color: $color-badge-obecne-text;
  }
}
</style>