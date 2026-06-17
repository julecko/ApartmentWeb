<script setup lang="ts">
    import { useForm } from '@inertiajs/vue3'
    import { route } from 'ziggy-js'

    const form = useForm({
        username: '',
        password: '',
    })

    function login() {
        form.post(route('admin.login'), {
            preserveScroll: true,
        })
    }
</script>

<template>
    <div class="login-page">
        <div class="login-container">

            <div class="icon-wrapper">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="4" width="10" height="10" rx="1.5" stroke="currentColor" stroke-width="1.5" />

                    <rect x="10" y="10" width="10" height="10" rx="1.5" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </div>

            <h1>Admin prístup</h1>

            <p class="subtitle">
                Správa aktualít domu
            </p>

            <form class="login-card" @submit.prevent="login">
                <label>USERNAME</label>

                <div class="input-wrapper">
                    <input v-model="form.username" type="text" placeholder="Zadajte username" autocomplete="username">
                </div>

                <p v-if="form.errors.username" class="error">
                    {{ form.errors.username }}
                </p>

                <label>HESLO</label>

                <div class="input-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5" />

                        <path d="M8 10V7a4 4 0 118 0v3" stroke="currentColor" stroke-width="1.5" />
                    </svg>

                    <input v-model="form.password" type="password" placeholder="Zadajte heslo"
                        autocomplete="current-password">
                </div>

                <p v-if="form.errors.password" class="error">
                    {{ form.errors.password }}
                </p>

                <button type="submit" :disabled="form.processing">
                    {{ form.processing
                        ? 'Prihlasujem...'
                        : 'Prihlásiť sa'
                    }}
                </button>
            </form>

        </div>
    </div>
</template>

<style scoped lang="scss">
    @use '$styles/colors' as *;

    .login-page {
        min-height: 100vh;

        display: flex;
        justify-content: center;
        align-items: center;

        padding: $spacing-lg;

        background: $color-bg;

        font-family: $font-body;
    }

    .login-container {
        width: 100%;
        max-width: 420px;

        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;

        display: flex;
        justify-content: center;
        align-items: center;

        margin-bottom: $spacing-md;

        border-radius: $radius-sm;

        background: $color-text-primary;
        color: $color-surface;
    }

    h1 {
        margin: 0;

        font-family: $font-display;
        font-size: $font-size-2xl;
        font-weight: $font-weight-bold;
        line-height: $line-height-tight;

        color: $color-text-primary;
    }

    .subtitle {
        margin-top: $spacing-xs;
        margin-bottom: $spacing-xl;

        font-size: $font-size-sm;

        color: $color-text-secondary;
    }

    .login-card {
        width: 100%;

        padding: $spacing-lg;

        border: 1px solid $color-border-card;
        border-radius: $radius-sm;

        background: $color-surface;
    }

    label {
        display: block;

        margin-top: $spacing-sm;
        margin-bottom: $spacing-sm;

        font-size: $font-size-xs;
        font-weight: $font-weight-medium;

        letter-spacing: 0.15em;

        color: $color-text-secondary;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        gap: $spacing-sm;

        height: 46px;

        padding: 0 14px;

        border: 1px solid $color-border;
        border-radius: $radius-sm;

        color: $color-text-muted;

        transition: border-color .2s ease;
    }

    .input-wrapper:focus-within {
        border-color: $color-text-primary;
    }

    input {
        flex: 1;

        border: none;
        outline: none;

        background: transparent;

        font-family: inherit;
        font-size: $font-size-base;

        color: $color-text-primary;
    }

    button {
        width: 100%;
        height: 46px;

        margin-top: $spacing-md;

        border: none;
        border-radius: $radius-sm;

        cursor: pointer;

        font-family: inherit;
        font-size: $font-size-sm;
        font-weight: $font-weight-semibold;

        background: $color-text-primary;
        color: $color-surface;

        transition: opacity .2s ease;
    }

    button:hover:not(:disabled) {
        opacity: .9;
    }

    button:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    .error {
        margin-top: $spacing-sm;

        font-size: $font-size-sm;

        color: #c0392b;
    }
</style>