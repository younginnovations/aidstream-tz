<?php namespace App\Providers;

use App\Services\Settings\Segmentation\SegmentationInterface;
use App\Services\Settings\Segmentation\SegmentationService;
use App\Tz\Aidstream\Repositories\DocumentLink\DocumentLinkRepository;
use App\Tz\Aidstream\Repositories\DocumentLink\DocumentLinkRepositoryInterface;
use App\Tz\Aidstream\Repositories\Project\ProjectRepository;
use App\Tz\Aidstream\Repositories\Project\ProjectRepositoryInterface;
use App\Tz\Aidstream\Repositories\Setting\SettingRepository;
use App\Tz\Aidstream\Repositories\Setting\SettingRepositoryInterface;
use App\Tz\Aidstream\Repositories\Transaction\TransactionRepository;
use App\Tz\Aidstream\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin',
            'App\SuperAdmin\Repositories\SuperAdmin'
        );

        $this->app->bind(
            'App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup',
            'App\SuperAdmin\Repositories\OrganizationGroup'
        );

        $this->app->bind(
            SegmentationInterface::class,
            SegmentationService::class
        );

        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(DocumentLinkRepositoryInterface::class, DocumentLinkRepository::class);
    }
}
