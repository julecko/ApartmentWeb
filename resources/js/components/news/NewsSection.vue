<script setup lang="ts">
defineProps<{ label: string }>();
</script>

<template>
    <section class="section">
        <h3 class="section__label">{{ label }}</h3>
        <div class="section__list">
            <slot />
        </div>
    </section>
</template>

<style scoped lang="scss">
@use '$styles/colors' as *;

.section {
    &__label {
        font-family: $font-body;
        font-size: $font-size-xs;
        font-weight: $font-weight-semibold;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: $color-text-muted;
        margin-bottom: $spacing-md;
    }

    &__list {
        display: flex;
        flex-direction: column;
        gap: $spacing-md;
    }
}
</style>