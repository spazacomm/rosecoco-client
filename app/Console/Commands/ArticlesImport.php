<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticlesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:import';
    


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import articles from JSON into the articles collection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
         $this->info('Starting article import...');

          info('Starting article import...');

        // Load JSON file
        $jsonPath = storage_path('articles.json');
        if (!file_exists($jsonPath)) {
            $this->error('JSON file not found.');
            return;
        }

        info('Json path: '.$jsonPath);

        $articles = json_decode(file_get_contents($jsonPath), true);
        if (!$articles) {
            $this->error('Invalid JSON format.');
            return;
        }

        info('aertickles: '.json_encode($articles));


        $collection = Collection::find('articles');
        if (!$collection) {
            $this->error('Collection "articles" not found.');
            return;
        }

        info('collection: '.$collection);

        foreach ($articles as $article) {

            info('article: '.json_encode($article));

            if (!isset($article['title'])) continue;

            $slug = Str::slug($article['title']); // Generate a slug from the title
            $entry = Entry::query()->where('slug', $slug)->where('collection', 'articles')->first();

            info('entry: '.json_encode($entry));

            if (isset($entry)) {
                $this->info("Skipping existing article: {$article['title']}");
                continue;
            }

            Entry::make()
                ->collection('articles')
                ->slug($slug)
                ->data([
                    'title' => $article['title'],
                    'published' => false,
                ])
                ->save();

            $this->info("Imported: {$article['title']}");
        }

        $this->info('Article import completed successfully.');

    }
}
