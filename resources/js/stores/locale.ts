import { defineStore } from 'pinia';
import { computed } from 'vue';
import { ref } from 'vue';

export type Locale = 'sk' | 'en';

export const useLocaleStore = defineStore('locale', () => {
    const locale = ref<Locale>('sk');

    function toggleLocale() {
        locale.value = locale.value === 'sk' ? 'en' : 'sk';
    }

    function setLocale(value: Locale) {
        locale.value = value;
    }

    const isSk = computed(() => locale.value === 'sk');

    return { locale, isSk, toggleLocale, setLocale };
});