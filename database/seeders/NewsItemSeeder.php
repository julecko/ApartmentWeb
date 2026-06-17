<?php

namespace Database\Seeders;

use App\Models\NewsAttachment;
use App\Models\NewsItem;
use Illuminate\Database\Seeder;

class NewsItemSeeder extends Seeder
{
    public function run(): void
    {
        $pinned = NewsItem::factory()->investment()->pinned()->create([
            'date'       => '2026-06-10',
            'content_sk' => 'Na schôdzi vlastníkov dňa 10. 6. 2026 bolo nadpolovičnou väčšinou schválené financovanie nového výťahu. Práce začnú v septembri 2026. Dodávateľ bude vybraný výberovým konaním do konca júla.',
            'content_en' => 'At the owners\' meeting on 10 June 2026, financing for a new elevator was approved by majority vote. Work will begin in September 2026. A contractor will be selected through a tender by the end of July.',
        ]);

        NewsAttachment::factory()->pdf()->for($pinned)->create(['name' => 'zapisnica-schoda.pdf']);
        NewsAttachment::factory()->pdf()->for($pinned)->create(['name' => 'cenova-ponuka-vytah.pdf']);

        NewsItem::factory()->repairFund()->create([
            'date'       => '2026-06-05',
            'content_sk' => 'Z dôvodu rastúcich nákladov na údržbu a plánovaných investícií sa mesačný príspevok do fondu opráv zvyšuje o 15 %. Nová výška príspevku bude uvedená na faktúrach od júla 2026.',
            'content_en' => 'Due to rising maintenance costs and planned investments, the monthly repair fund contribution will increase by 15%. The new contribution amount will appear on invoices from July 2026.',
        ]);

        $roof = NewsItem::factory()->repair()->create([
            'date'       => '2026-05-28',
            'content_sk' => 'Zhotoviteľ dokončil druhú etapu opravy strechy v plánovanom termíne a bez viacnákladov. Tretia, záverečná etapa je naplánovaná na október 2026.',
            'content_en' => 'The contractor completed the second phase of roof repairs on schedule and within budget. The third and final phase is scheduled for October 2026.',
        ]);

        NewsAttachment::factory()->for($roof)->create([
            'name'      => 'fotodokumentacia-strecha.jpg',
            'path'      => 'news-attachments/fotodokumentacia-strecha.jpg',
            'mime_type' => 'image/jpeg',
            'size'      => 1_048_576,
        ]);

        NewsItem::factory()->general()->create([
            'date'       => '2026-05-20',
            'content_sk' => 'Po výberovom konaní bola ako nový správca domu zvolená spoločnosť Správa SK s.r.o. Prechod prebehne bezproblémovo — platby IBAN ostávajú rovnaké do 31. 7. 2026.',
            'content_en' => 'Following a competitive tender, Správa SK s.r.o. was selected as the new building manager. The transition will be seamless — your IBAN payments remain unchanged until 31 July 2026.',
        ]);

        // Additional random posts with random attachments
        NewsItem::factory(8)
            ->create()
            ->each(function (NewsItem $item) {
                if (rand(0, 1)) {
                    NewsAttachment::factory(rand(1, 3))->for($item)->create();
                }
            });
    }
}
