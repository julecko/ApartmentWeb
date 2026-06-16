export type BadgeType = 'investment' | 'repairFund' | 'repair' | 'general';

export interface NewsItem {
    id: number;
    badge: BadgeType;
    date: string;
    titleSk: string;
    titleEn: string;
    summarySk: string;
    summaryEn: string;
    contentSk: string;
    contentEn: string;
    pinned?: boolean;
}