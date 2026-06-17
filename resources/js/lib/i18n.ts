import { computed } from 'vue';
import { useLocaleStore } from '$stores/locale';

const translations = {
    sk: {
        // Header
        appEyebrow: 'Správa domu',
        appTitle: 'Aktuality bytu',
        appSubtitle: 'Informácie o správe, investíciách a fonde opráv',
        currentLang: 'SK',

        // Admin header
        adminEyebrow: 'Admin',
        adminTitle: 'Správa príspevkov',

        // Sections
        pinned: 'Pripnuté',
        others: 'Ostatné',
        allPosts: 'Všetky príspevky',

        // Card actions
        readMore: 'Čítať viac',
        readLess: 'Čítať menej',
        pin: 'Pripnúť',
        unpin: 'Odopnúť',
        delete: 'Vymazať',
        logout: 'Odhlásiť',

        // Badges
        badgeInvestment: 'Investícia',
        badgeRepairFund: 'Fond opráv',
        badgeRepair: 'Oprava',
        badgeGeneral: 'Obecné',

        // Form
        formTitle: 'Nový príspevok',
        fieldTitleSk: 'Názov (SK)',
        fieldTitleSkPlaceholder: 'Názov príspevku po slovensky',
        fieldTitleEn: 'Názov (EN)',
        fieldTitleEnPlaceholder: 'Post title in English',
        fieldCategory: 'Kategória',
        fieldDate: 'Dátum',
        fieldSummarySk: 'Krátky popis (SK, voliteľný)',
        fieldSummarySkPlaceholder: 'Stručné zhrnutie — zobrazí sa na prehľade',
        fieldSummaryEn: 'Krátky popis (EN, voliteľný)',
        fieldSummaryEnPlaceholder: 'Short summary — displayed in the overview',
        fieldContentSk: 'Dlhý popis (SK, voliteľný)',
        fieldContentSkPlaceholder: 'Podrobný text príspevku — zobrazí sa po rozbalení…',
        fieldContentEn: 'Dlhý popis (EN, voliteľný)',
        fieldContentEnPlaceholder: 'Detailed post content — shown after expanding…',
        fieldAttachments: 'Prílohy',
        dropzoneText: 'Pretiahnite súbory alebo kliknite pre výber',
        dropzoneHint: 'PDF, obrázky, dokumenty',
        pinPost: 'Pripnúť príspevok',
        publish: 'Publikovať',
        cancel: 'Zrušiť',
        save: 'Uložiť',
        editPost: 'Upraviť príspevok',
        addPost: 'Pridať príspevok',

        // Attachments
        attachmentsShow: 'Zobraziť prílohy',
        attachmentsHide: 'Skryť prílohy',
        attachmentSingular: 'príloha',
        attachmentPlural: 'prílohy',
        download: 'Stiahnuť',
    },
    en: {
        // Header
        appEyebrow: 'Building management',
        appTitle: 'Building News',
        appSubtitle: 'Updates on management, investments and repair fund',
        currentLang: 'EN',

        // Admin header
        adminEyebrow: 'Admin',
        adminTitle: 'Manage posts',

        // Sections
        pinned: 'Pinned',
        others: 'Other',
        allPosts: 'All posts',

        // Card actions
        readMore: 'Read more',
        readLess: 'Read less',
        pin: 'Pin',
        unpin: 'Unpin',
        delete: 'Delete',
        logout: 'Log out',

        // Badges
        badgeInvestment: 'Investment',
        badgeRepairFund: 'Repair fund',
        badgeRepair: 'Repair',
        badgeGeneral: 'General',

        // Form
        formTitle: 'New post',
        fieldTitleSk: 'Title (SK)',
        fieldTitleSkPlaceholder: 'Post title in Slovak',
        fieldTitleEn: 'Title (EN)',
        fieldTitleEnPlaceholder: 'Post title in English',
        fieldCategory: 'Category',
        fieldDate: 'Date',
        fieldSummarySk: 'Short description (SK)',
        fieldSummarySkPlaceholder: 'Brief summary — displayed in the overview',
        fieldSummaryEn: 'Short description (EN)',
        fieldSummaryEnPlaceholder: 'Brief summary — displayed in the overview',
        fieldContentSk: 'Long description (SK, optional)',
        fieldContentSkPlaceholder: 'Detailed post content — shown after expanding…',
        fieldContentEn: 'Long description (EN, optional)',
        fieldContentEnPlaceholder: 'Detailed post content — shown after expanding…',
        fieldAttachments: 'Attachments',
        dropzoneText: 'Drag files here or click to browse',
        dropzoneHint: 'PDF, images, documents',
        pinPost: 'Pin post',
        publish: 'Publish',
        cancel: 'Cancel',
        save: 'Save',
        editPost: 'Edit post',
        addPost: 'Add post',

        // Attachments
        attachmentsShow: 'Show attachments',
        attachmentsHide: 'Hide attachments',
        attachmentSingular: 'attachment',
        attachmentPlural: 'attachments',
        download: 'Download',
    },
} as const;

export function useI18n() {
    const localeStore = useLocaleStore();
    const t = computed(() => translations[localeStore.locale]);
    return { t, localeStore };
}