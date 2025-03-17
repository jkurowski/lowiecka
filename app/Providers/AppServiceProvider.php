<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Board;
use App\Models\Boxes;
use App\Models\Building;
use App\Models\Client;
use App\Models\ClientFile;
use App\Models\ClientNote;
use App\Models\ClientRules;
use App\Models\ClientStatusHistory;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateSection;
use App\Models\Event;
use App\Models\File;
use App\Models\Floor;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Investment;
use App\Models\InvestmentArticles;
use App\Models\InvestmentPage;
use App\Models\IssueFile;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;
use App\Models\Settings;
use App\Models\Slider;
use App\Models\Stage;
use App\Models\Url;
use App\Models\User;

use App\Observers\ArticleObserver;
use App\Observers\BoardObserver;
use App\Observers\BoxObserver;
use App\Observers\BuildingObserver;
use App\Observers\ClientFileObserver;
use App\Observers\ClientObserver;
use App\Observers\ClientStatusObserver;
use App\Observers\EmailTemplateObserver;
use App\Observers\FileObserver;
use App\Observers\FloorObserver;
use App\Observers\GalleryObserver;
use App\Observers\ImageObserver;
use App\Observers\InvestmentArticleObserver;
use App\Observers\InvestmentObserver;
use App\Observers\InvestmentPageObserver;
use App\Observers\IssueFileObserver;
use App\Observers\MailTemplateObserver;
use App\Observers\PageObserver;
use App\Observers\PropertyObserver;
use App\Observers\SliderObserver;
use App\Observers\StageObserver;
use App\Observers\UrlObserver;
use App\Observers\UserObserver;
use App\Services\SMS\SMSService;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;
use Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL as FacadesURL;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Settings::class, function () {
            return Settings::make(storage_path('app/settings.json'));
        });
        $this->app->bind('App\Repositories\EloquentRepositoryInterface', 'App\Repositories\BaseRepository');
        $this->app->bind('App\Repositories\UserRepositoryInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\SliderRepositoryInterface', 'App\Repositories\SliderRepository');
        $this->app->bind('App\Repositories\BoxRepositoryInterface', 'App\Repositories\Client\ClientRepository');
        $this->app->bind('App\Repositories\ArticleRepositoryInterface', 'App\Repositories\ArticleRepository');
        $this->app->bind('App\Repositories\PageRepositoryInterface', 'App\Repositories\PageRepository');
        $this->app->bind('App\Repositories\UrlRepositoryInterface', 'App\Repositories\UrlRepository');
        $this->app->bind('App\Repositories\ImageRepositoryInterface', 'App\Repositories\ImageRepository');
        $this->app->bind('App\Repositories\InvestmentRepositoryInterface', 'App\Repositories\InvestmentRepository');
        $this->app->bind('App\Repositories\SectionRepositoryInterface', 'App\Repositories\SectionRepository');
        $this->app->singleton(SMSService::class, function ($app) {
            return new SMSService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Activity::saving(function (Activity $activity) {

            //dd($activity->toArray());

            $activity->properties = collect([
                "route"         => Request::getPathInfo(),
                "ipAddress"     => Request::ip(),
                "userAgent"     => Request::header('user-agent'),
                "locale"        => Request::header('accept-language'),
                "referer"       => Request::header('referer'),
                "methodType"    => Request::method()
            ]);
        });

        VerifyEmail::toMailUsing(function ($notifiable) {
            $verifyUrl = FacadesURL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return (new MailMessage)
                ->subject(settings()->get("page_title") . ' - ponowna weryfikacja konta')
                ->line('aby aktywować konto kliknij link poniżej:')
                ->action('Weryfikuj adres e-mail', $verifyUrl)
                ->salutation(new HtmlString('<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation"><tr><td>' . Markdown::parse('To jest wiadomość automatyczna, nie odpowiadaj na tego maila. Jeśli masz jakiekolwiek pytania, napisz do nas: ' . settings()->get("page_email")) . '</td></tr></table>'));
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo number_format($amount, 0, '', ' ') . ' zł'; ?>";
        });

        View::composer('admin.crm.client.client_shared.aside', function ($view) {
            $currentRoute = Route::current();
            $params = $currentRoute->parameters();
            if (isset($params['client'])) {
                $clientId = $params['client']['id'];

                $view->with('clientLastEvents', Event::where("client_id", $clientId)
                    ->whereUserId(auth()->id())
                    ->limit(3)
                    ->latest()
                    ->get(['created_at', 'name', 'active', 'type']));

                $view->with('clientLastNotes', ClientNote::where("client_id", $clientId)
                    ->whereUserId(auth()->id())
                    ->limit(3)
                    ->latest()
                    ->get(['created_at', 'text']));

                $view->with('clientLastFiles', ClientFile::where("client_id", $clientId)
                    ->whereUserId(auth()->id())
                    ->limit(3)
                    ->latest()
                    ->get(['created_at', 'name', 'size', 'file']));

                $clientRules = ClientRules::where('client_id', $clientId)
                    ->whereIn('rule_id', [1, 2, 3])
                    ->orderBy('id', 'desc')
                    ->limit(3)
                    ->get(['id', 'rule_id', 'status', 'created_at'])
                    ->sortBy(function ($rule) {
                        return array_search($rule->rule_id, [1, 2, 3]);
                    });

                $view->with('clientRules', $clientRules);
            }
        });

        View::share('current_locale', app()->getLocale());
        View::share('available_locales', config('app.available_locales'));
        View::share('current_investments', Investment::where('status', 1)->get(['id', 'name', 'slug', 'date_start']));
        View::share('required_rodo_rules', RodoRules::whereIn('id', [1, 2, 3])->get());
        View::share('last_news', Article::where('status', 1)->limit(3)->orderBy('posted_at', 'ASC')->get(['file', 'file_webp', 'title', 'posted_at', 'slug']));

        view()->composer(['admin.crm.offer.form', 'admin.crm.inbox.index'], function ($view) {
            $view->with('investments', Investment::all()->pluck('name', 'id'));
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo number_format((float) $amount, 0, '.', ' ') . ' zł'; ?>";
        });

        Image::observe(ImageObserver::class);
        Gallery::observe(GalleryObserver::class);
        Article::observe(ArticleObserver::class);

        Client::observe(ClientObserver::class);

        Slider::observe(SliderObserver::class);
        Boxes::observe(BoxObserver::class);
        Article::observe(ArticleObserver::class);
        Page::observe(PageObserver::class);
        Url::observe(UrlObserver::class);
        Image::observe(ImageObserver::class);
        File::observe(FileObserver::class);
        ClientFile::observe(ClientFileObserver::class);
        User::observe(UserObserver::class);

        // Board
        Stage::observe(StageObserver::class);
        Board::observe(BoardObserver::class);

        IssueFile::observe(IssueFileObserver::class);
        // EmailTemplateSection::observe(EmailTemplateObserver::class);
        // EmailTemplate::observe(MailTemplateObserver::class);

        /*
        |--------------------------------------------------------------------------
        | DeveloPro
        |--------------------------------------------------------------------------
        */
        Investment::observe(InvestmentObserver::class);
        Building::observe(BuildingObserver::class);
        Floor::observe(FloorObserver::class);
        Property::observe(PropertyObserver::class);
        InvestmentPage::observe(InvestmentPageObserver::class);
        InvestmentArticles::observe(InvestmentArticleObserver::class);
    }
}
