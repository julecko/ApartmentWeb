import { computed } from 'vue';
import { useLocaleStore } from '$stores/locale';

const translations = {
    sk: {
        appEyebrow: 'Správa domu',
        appTitle: 'Aktuality bytu',
        appSubtitle: 'Informácie o správe, investíciách a fonde opráv',
        pinned: 'Pripnuté',
        others: 'Ostatné',
        readMore: 'Čítať viac',
        readLess: 'Čítať menej',
        badgeInvestment: 'Investícia',
        badgeRepairFund: 'Fond opráv',
        badgeRepair: 'Oprava',
        badgeGeneral: 'Obecné',
        currentLang: 'SK',
    },
    en: {
        appEyebrow: 'Building management',
        appTitle: 'Building News',
        appSubtitle: 'Updates on management, investments and repair fund',
        pinned: 'Pinned',
        others: 'Other',
        readMore: 'Read more',
        readLess: 'Read less',
        badgeInvestment: 'Investment',
        badgeRepairFund: 'Repair fund',
        badgeRepair: 'Repair',
        badgeGeneral: 'General',
        currentLang: 'EN',
    },
} as const;

export function useI18n() {
    const localeStore = useLocaleStore();

    const t = computed(() => translations[localeStore.locale]);

    return { t, localeStore };
}