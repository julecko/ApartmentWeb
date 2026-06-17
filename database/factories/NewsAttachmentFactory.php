<?php

namespace Database\Factories;

use App\Models\NewsAttachment;
use App\Models\NewsItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsAttachmentFactory extends Factory
{
    protected $model = NewsAttachment::class;

    private const MIME_TYPES = [
        'application/pdf'                                                         => 'pdf',
        'image/jpeg'                                                              => 'jpg',
        'image/png'                                                               => 'png',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       => 'xlsx',
    ];

    public function definition(): array
    {
        $mime      = $this->faker->randomElement(array_keys(self::MIME_TYPES));
        $extension = self::MIME_TYPES[$mime];
        $name      = $this->faker->slug(3) . '.' . $extension;

        return [
            'news_item_id' => NewsItem::factory(),
            'name'         => $name,
            'path'         => 'news-attachments/' . $name,
            'mime_type'    => $mime,
            'size'         => $this->faker->numberBetween(10_000, 5_000_000),
        ];
    }

    public function pdf(): static
    {
        return $this->state(function () {
            $name = $this->faker->slug(3) . '.pdf';
            return [
                'name'      => $name,
                'path'      => 'news-attachments/' . $name,
                'mime_type' => 'application/pdf',
                'size'      => $this->faker->numberBetween(50_000, 2_000_000),
            ];
        });
    }
}
