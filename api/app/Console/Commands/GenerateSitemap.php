<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Generating sitemap...');

        Sitemap::create()
            ->add(Url::create(config('app.frontend_url'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            )
            ->add(Url::create(config('app.frontend_url') . 'about-kyrian')
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            )
            ->add(Post::all())
            ->add(Tag::whereHas('posts')->get())
            ->add(Category::whereHas('posts')->get())
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
