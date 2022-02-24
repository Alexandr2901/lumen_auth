<?php

namespace App\Providers;

use App\Contracts\Repositories\NewsRepositoryContract;
use App\Contracts\Repositories\TagRepositoryContract;
use App\Contracts\Repositories\UserRepositoryContract;
use App\Contracts\Services\FiltersServiceContract;
use App\Contracts\Services\NewsServiceContract;
use App\Repositories\NewsRepository;
use App\Repositories\TagsRepository;
use App\Repositories\UserRepository;
use App\Services\FiltersService;
use App\Services\NewsService;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->repositories();
        $this->services();
    }

    private function repositories()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(TagRepositoryContract::class, TagsRepository::class);
        $this->app->bind(NewsRepositoryContract::class, NewsRepository::class);
    }

    private function services()
    {
        $this->app->bind(NewsServiceContract::class, NewsService::class);
        $this->app->bind(FiltersServiceContract::class, FiltersService::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//                $this->app->bind(BaseRepositoryContract::class, BaseRepository::class);

    }
}
