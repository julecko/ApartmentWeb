export type BadgeType = 'investment' | 'repairFund' | 'repair' | 'general';

export interface NewsAttachment {
    id: number;
    name: string;
    size: number;   // bytes
    url: string;
    mimeType: string;
}

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
    pinned: boolean;
    attachments?: NewsAttachment[];
}