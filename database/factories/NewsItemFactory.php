<?php

namespace Database\Factories;

use App\Models\NewsItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsItemFactory extends Factory
{
    protected $model = NewsItem::class;

    public function definition(): array
    {
        return [
            'badge'      => $this->faker->randomElement(['investment', 'repairFund', 'repair', 'general']),
            'date'       => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'title_sk'   => $this->faker->sentence(5),
            'title_en'   => $this->faker->sentence(5),
            'summary_sk' => $this->faker->sentence(12),
            'summary_en' => $this->faker->sentence(12),
            'content_sk' => $this->faker->optional(0.7)->paragraph(3),
            'content_en' => $this->faker->optional(0.7)->paragraph(3),
            'pinned'     => $this->faker->boolean(15),
        ];
    }

    public function pinned(): static
    {
        return $this->state(['pinned' => true]);
    }

    public function investment(): static
    {
        return $this->state([
            'badge'      => 'investment',
            'title_sk'   => 'Výmena výťahu — schválené',
            'title_en'   => 'Elevator replacement — approved',
            'summary_sk' => 'Vlastníci odhlasovali financovanie nového výťahu za 1 200 000 Kč.',
            'summary_en' => 'Owners voted to finance a new elevator for 1,200,000 CZK.',
        ]);
    }

    public function repairFund(): static
    {
        return $this->state([
            'badge'      => 'repairFund',
            'title_sk'   => 'Navýšenie príspevku do fondu opráv od júla',
            'title_en'   => 'Repair fund contribution increase from July',
            'summary_sk' => 'Mesačný príspevok do fondu opráv sa od 1. júla zvyšuje o 15 %.',
            'summary_en' => 'The monthly repair fund contribution will increase by 15% from 1 July.',
        ]);
    }

    public function repair(): static
    {
        return $this->state([
            'badge'      => 'repair',
            'title_sk'   => 'Oprava strechy — 2. etapa dokončená',
            'title_en'   => 'Roof repair — 2nd phase completed',
            'summary_sk' => 'Druhá etapa rekonštrukcie strechy úspešne dokončená, tretia na jeseň.',
            'summary_en' => 'The second phase of roof reconstruction was completed successfully.',
        ]);
    }

    public function general(): static
    {
        return $this->state([
            'badge'      => 'general',
            'title_sk'   => 'Schôdza vlastníkov — 25. júna 2026',
            'title_en'   => 'Owners\' meeting — 25 June 2026',
            'summary_sk' => 'Schôdza sa koná 25. júna od 18:00 v prízemí domu.',
            'summary_en' => 'The meeting takes place on 25 June at 18:00 on the ground floor.',
        ]);
    }
}