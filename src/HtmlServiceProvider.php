<?php

namespace Collective\Html;

use Illuminate\Session\Store;
use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlBuilder();

        $this->registerFormBuilder();
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new HtmlBuilder($app['url']);
        });

        $this->app->alias('html', 'Collective\Html\HtmlBuilder');
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            /** @var Store $session */
            $session = $app['session.store'];

            $form = new FormBuilder($app['html'], $app['url'], $session->token());

            $form->setSessionStore($session);

            return $form;
        });

        $this->app->alias('form', 'Collective\Html\FormBuilder');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['html', 'form', 'Collective\Html\HtmlBuilder', 'Collective\Html\FormBuilder'];
    }
}
