<?php

use App\Http\Controllers\Front\MenuController;
use App\Http\Controllers\Admin\MassMail\CustomTemplateController;
use Illuminate\Support\Facades\Route;

//GET - admin/crm/module
//POST - admin/crm/module - store
//PUT - admin/crm/module/{calendar} - update
//GET - admin/crm/module/{calendar} - show
//DELETE - admin/crm/module/{calendar} - destroy
//GET - admin/crm/module/{calendar}/edit - edit

Route::group([
    'namespace' => 'Admin',
    'prefix' => '/admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'verified']
], function () {

    Route::redirect('/', '/admin/settings/seo');

    Route::post('slider/set', 'Slider\IndexController@sort')->name('slider.sort');
    Route::post('gallery/set', 'Gallery\IndexController@sort')->name('gallery.sort');
    Route::post('image/set', 'Gallery\ImageController@sort')->name('image.sort');
    Route::post('box/set', 'Box\IndexController@sort')->name('box.sort');
    Route::post('invest-page/set', 'Developro\Page\IndexController@sort')->name('investment_page.sort');

    Route::post('ux/properties', 'UX\IndexController@properties')->name('ux.properties');

    Route::get('contract/show/{contract}', 'Contract\IndexController@show')->name('contract.show');
    Route::get('contract/settings/{contract}', 'Contract\IndexController@settings')->name('contract.settings');
    Route::post('contract/settings/{contract}', 'Contract\IndexController@saveSettings')->name('contract.save-settings');
    Route::post('contract/generate/{contract}', 'Contract\IndexController@generate')->name('contract.generate');

    Route::post('contract/template', 'Contract\TemplateController@index')->name('contract.template');
    Route::get('contract/template/form', 'Contract\TemplateController@create')->name('contract.template.create');
    Route::post('contract/template/form', 'Contract\TemplateController@store')->name('contract.template.store');
    Route::get('contract/template/{template}', 'Contract\TemplateController@edit')->name('contract.template.edit');
    Route::get('contract/template/show/{template}', 'Contract\TemplateController@show')->name('contract.template.show');
    Route::put('contract/template/{template}', 'Contract\TemplateController@update')->name('contract.template.update');
    Route::get('contract/template/settings/{template}', 'Contract\TemplateController@settings')->name('contract.template.settings');
    Route::post('contract/template/settings/{template}', 'Contract\TemplateController@saveSettings')->name('contract.template.save-settings');
    Route::post('contract/template/generate/{template}', 'Contract\TemplateController@generate')->name('contract.template.generate');


    Route::get('user/datatable', 'User\IndexController@datatable')->name('user.datatable');
    Route::get('user/get-list', 'User\IndexController@getList')->name('user.getList');
    Route::get('user/send-notification', 'User\IndexController@sendNotification')->name('user.sendNotification');

    // Gallery
    Route::get('ajaxGetGalleries', 'Gallery\IndexController@ajaxGetGalleries')->name('ajaxGetGalleries');

    Route::resources([
        'page' => 'Page\IndexController',
        'url' => 'Url\IndexController',
        'file' => 'File\IndexController',
        'file-catalog' => 'File\CatalogController',
        'gallery' => 'Gallery\IndexController',
        'image' => 'Gallery\ImageController',
        'map' => 'Map\IndexController',
        'slider' => 'Slider\IndexController',
        'user' => 'User\IndexController',
        'role' => 'Role\IndexController',
        'greylist' => 'Greylist\IndexController',
        'article' => 'Article\IndexController',
        'contract' => 'Contract\IndexController',
        'ux' => 'UX\IndexController',
        'box' => 'Box\IndexController'
    ]);

    // Settings
    Route::group(['prefix' => '/settings', 'as' => 'settings.'], function () {

        Route::resources([
            '/' => 'Dashboard\IndexController',
            'seo' => 'Dashboard\SeoController',
            'social' => 'Dashboard\SocialController',
            'popup' => 'Dashboard\PopupController',
            'facebook' => 'Dashboard\FacebookController'
        ]);
    });

    Route::post('/get-template', 'Email\GeneratorController@getTemplate')->name('email.generator.get-template');
    Route::post('/get-settings', 'Email\GeneratorController@getSettings')->name('email.generator.get-settings');
    Route::post('/update-settings', 'Email\GeneratorController@updateSettings')->name('email.generator.update-settings');
    Route::post('/update-order', 'Email\GeneratorController@updateOrder')->name('email.generator.update-order');
    Route::post('/remove-block', 'Email\GeneratorController@destroyBlock')->name('email.generator.remove-block');
    Route::post('/upload-image', 'Email\GeneratorController@uploadImage')->name('email.generator.upload-image');
    Route::get('/copy/{email}', 'Email\GeneratorController@copy')->name('email.generator.copy');



    // Email`s
    Route::group(['namespace' => 'Email', 'prefix' => '/email', 'as' => 'email.'], function () {
        Route::get('generator/{investment_id}', 'GeneratorController@index')->name('generator.index');
        Route::get('generator/{investment_id}/create', 'GeneratorController@create')->name('generator.create');
        Route::get('generator/{investment_id}/{id}', 'GeneratorController@show')->name('generator.show');
        Route::get('generator/{investment_id}/{id}/edit', 'GeneratorController@edit')->name('generator.edit');
        Route::resource('generator', 'GeneratorController')->except(['index', 'create', 'show', 'edit']);
    });
    Route::post('email/template/update', 'Email\GeneratorController@updateTemplate')->name('email.generator.update-template');
    Route::post('email/template/assign-attachment', 'Email\GeneratorController@assignAttachment')->name('email.generator.assignAttachment');
    Route::post('email/template/unlink-attachment', 'Email\GeneratorController@unlinkAttachment')->name('email.generator.unlinkAttachment');

    Route::get('logs', 'Log\IndexController@index')->name('log.index');
    Route::get('logs/datatable', 'Log\IndexController@datatable')->name('log.datatable');
    Route::get('logs/{causer}', 'Log\IndexController@show')->name('log.show');
    Route::get('logs/datatable/{causer}', 'Log\IndexController@datatableUser')->name('log.datatable-user');

    Route::group(['namespace' => 'File', 'middleware' => 'file_owner', 'as' => 'file-catalog.'], function () {
        Route::get('file-catalog/{file_catalog}', 'CatalogController@show')->name('show');
        Route::get('file-catalog/{file_catalog}/edit', 'CatalogController@edit')->name('edit');
    });

    Route::group(['namespace' => 'File', 'prefix' => '/file', 'as' => 'file.'], function () {
        Route::get('{file}/download', 'IndexController@download')->name('download');
        Route::get('file-catalog/{file}/create', 'CatalogController@create')->name('create.catalog');
        Route::get('file-catalog/{file}/create-file', 'IndexController@create')->name('create.file-file');
    });

    Route::group(['namespace' => 'Rodo', 'prefix' => '/rodo', 'as' => 'rodo.'], function () {
        Route::post('clients/modal', 'ClientController@clientModal')->name('clients.modal');

        Route::resources([
            'clients' => 'ClientController',
            'rules' => 'RulesController',
            'settings' => 'SettingsController'
        ]);

        Route::post('check-rule', 'RulesController@check')->name('check-rule');
    });

    // Leady z zewnątrz
    Route::get('external-leads', 'ExternalLeads\IndexController@index')->name('externalLeads.index');
    Route::post('external-leads/assign', 'ExternalLeads\IndexController@assign')->name('externalLeads.assign');
    Route::post('external-leads/assign/get-selected', 'ExternalLeads\IndexController@getSelectedOptions')->name('externalLeads.assign.getSelected');
    Route::get('external-leads/datatable', 'ExternalLeads\IndexController@datatable')->name('externalLeads.datatable');


    // Nip
    Route::post('nip', 'NipController@index')->name('nip.index');

    // STMP test
    Route::post('smtp-test', 'SMTP\IndexController@checkSMTP')->name('smtp-test.checkSMTP');

    // CRM
    // admin.crm
    Route::group(['namespace' => 'Crm', 'prefix' => '/crm', 'as' => 'crm.'], function () {

        // Ustawienia CRM
        Route::group(['namespace' => 'Settings', 'prefix' => '/settings', 'as' => 'settings.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
        });

        Route::get('contact/datatable', 'Contact\IndexController@datatable')->name('contact.datatable');
        Route::get('issue/datatable', 'Issue\IndexController@datatable')->name('issue.datatable');
        Route::post('issue/{issue}/file', 'Issue\FileController@upload')->name('issue.file.upload');
        Route::delete('issue/{issue}/file/{issueFile}', 'Issue\FileController@destroy')->name('issue.file.destroy');
        Route::post('issue/{issue}/update-status', 'Issue\IndexController@updateStatus')->name('issue.update-status');
        Route::get('offer/datatable', 'Offer\IndexController@datatable')->name('offer.datatable');
        Route::get('offer/{offer}/AjaxSearch', 'Offer\IndexController@offerAjaxSearch')->name('offer.ajax.search');

        Route::post('offers/search-by-name', 'Offer\IndexController@searchByName')->name('offer.search-by-name');

        Route::get('offer/create/{id?}', 'Offer\IndexController@create')->name('offer.create');
        Route::get('offer', 'Offer\IndexController@index')->name('offer.index');
        Route::get('offer/drafts', 'Offer\IndexController@drafts')->name('offer.drafts');

        // Szablony ofert
        Route::get('offer/templates', 'Offer\TemplateController@index')->name('offer.templates');
        Route::get('offer/templates/create', 'Offer\TemplateController@create')->name('offer.templates.create');
        Route::get('offer/templates/{id}/edit', 'Offer\TemplateController@edit')->name('offer.templates.edit');
        Route::get('offer/templates/{id}', 'Offer\TemplateController@show')->name('offer.templates.show');
        Route::delete('offer/templates/{id}', 'Offer\TemplateController@destroy')->name('offer.templates.destroy');
        Route::post('offer/templates', 'Offer\TemplateController@store')->name('offer.templates.store');
        Route::put('offer/templates/{id}', 'Offer\TemplateController@update')->name('offer.templates.update');

        // Szablony tresci
        Route::get('offer/content-templates', 'Offer\ContentTemplatesController@index')->name('offer.content-templates');
        Route::get('offer/content-templates/create', 'Offer\ContentTemplatesController@create')->name('offer.content-templates.create');
        Route::get('offer/content-templates/{id}/edit', 'Offer\ContentTemplatesController@edit')->name('offer.content-templates.edit');
        Route::delete('offer/content-templates/{id}', 'Offer\ContentTemplatesController@destroy')->name('offer.content-templates.destroy');
        Route::post('offer/content-templates', 'Offer\ContentTemplatesController@store')->name('offer.content-templates.store');
        Route::put('offer/content-templates/{template}', 'Offer\ContentTemplatesController@update')->name('offer.content-templates.update');
        Route::get('offer/content-templates/show', 'Offer\ContentTemplatesController@show')->name('offer.content-templates.show');

        Route::put('offer/{offer}', 'Offer\IndexController@update')->name('offer.update');
        Route::post('offer/{issue}/file', 'Offer\FileController@upload')->name('offer.file.upload');
        Route::delete('offer/{offer}/file/{id}', 'Offer\FileController@destroy')->name('offer.file.destroy');
        Route::post('offer/{offer}/property/{id}', 'Offer\IndexController@property')->name('offer.property');
        Route::delete('offer/{id}', 'Offer\IndexController@destroy')->name('offer.destroy');

        Route::resources([
            'custom-fields' => 'CustomField\IndexController',
            'contact' => 'Contact\IndexController',
            'issue' => 'Issue\IndexController',
            //'offer' => 'Offer\IndexController'
        ]);

        Route::get('handover/modal/{taskId?}', 'Property\HandoverController@modal')->name('handover.modal');
        Route::get('handover/{property}', 'Property\HandoverController@index')->name('handover');
        Route::post('handover/{property}', 'Property\HandoverController@store')->name('handover.store');
        Route::delete('handover/{task}', 'Property\HandoverController@destroy')->name('handover.destroy');

        Route::group(['namespace' => 'Jobs', 'prefix' => '/jobs', 'as' => 'jobs.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/jobs/{id}', 'IndexController@show')->name('show');
            Route::post('/jobs/{id}/retry', 'IndexController@retry')->name('retry');
            Route::delete('/jobs/{id}/forget', 'IndexController@forget')->name('forget');
            Route::post('/jobs/{job}/run-now', 'IndexController@run-now')->name('run-now');

            Route::post('/jobs/{job}/remove', 'IndexController@removeJob')->name('removeJob');
            Route::post('/failed-jobs/{job}/remove', 'IndexController@removeFailedJob')->name('removeFailedJob');
        });

        Route::get('inbox', 'Inbox\IndexController@index')->name('inbox.index');
        Route::get('inbox/datatable', 'Inbox\IndexController@datatable')->name('inbox.datatable');
        Route::delete('inbox/{id}', 'Inbox\IndexController@destroy')->name('inbox.destroy');

        // Settings
        Route::group(['namespace' => 'Statistics', 'prefix' => '/statistics', 'as' => 'statistics.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/rooms', 'IndexController@rooms')->name('rooms');
        });

        // Funnel
        Route::group(['namespace' => 'Funnel', 'prefix' => '/funnel', 'as' => 'funnel.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
        });

        // Assign leads
        Route::group(['namespace' => 'AssignLeads', 'prefix' => '/assign-leads', 'as' => 'assign-leads.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
            Route::post('/', 'IndexController@store')->name('store');
        });

        // admin.crm.clients.create
        Route::group(['namespace' => 'Client', 'prefix' => '/clients', 'as' => 'clients.'], function () {

            Route::post('/payments/calc-percent', 'PaymentController@calcPercent')->name('calc-percent');
            Route::post('/payments/calc-amount', 'PaymentController@calcAmount')->name('calc-amount');

            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/datatable', 'IndexController@datatable')->name('datatable');
            Route::get('/create', 'IndexController@create')->name('create');
            Route::get('/create-company', 'IndexController@createNewCompany')->name('create-company');
            Route::get('/{client}', 'IndexController@show')->name('show');
            Route::put('/{client}', 'IndexController@update')->name('update');
            Route::post('/', 'IndexController@store')->name('store');

            Route::post('/modal', 'IndexController@storeModal')->name('store-modal');

            Route::get('{client}/calendar', 'CalendarController@index')->name('calendar');
            Route::get('{client}/rodo', 'RodoController@show')->name('rodo');

            // Client files
            Route::get('{client}/issues', 'IssueController@show')->name('issues');

            // Client files
            Route::get('{client}/files', 'FileController@show')->name('files');
            Route::post('{client}/files', 'FileController@store')->name('files.store');
            Route::post('{client}/files/create', 'FileController@create')->name('files.create');
            Route::delete('{client}/files/{clientFile}', 'FileController@destroy')->name('file.destroy');

            // Client file description
            Route::post('file-desc/{clientFile}/form', 'FileController@form')->name('file.desc.form');
            Route::post('file-desc/{clientFile}', 'FileController@storeDesc')->name('file.desc.store');
            Route::delete('file-desc/{clientFile}', 'FileController@destroyDesc')->name('file.desc.destroy');
            Route::post('{client}/files/{clientFile}', 'FileController@changeStatus')->name('file.change-status');

            // Client notes
            Route::get('{client}/notes', 'NoteController@show')->name('notes');
            Route::post('{client}/notes', 'NoteController@store')->name('notes.store');
            Route::put('{client}/notes/{note}', 'NoteController@update')->name('notes.update');
            Route::delete('{client}/notes/{note}', 'NoteController@destroy')->name('notes.destroy');

            // Client calendar
            Route::get('{client}/events', 'CalendarController@show')->name('events.show');
            Route::get('{client}/events/{event}', 'CalendarController@edit')->name('events.edit');
            Route::post('{client}/events/form', 'CalendarController@create')->name('events.create');

            Route::get('{client}/payments/create', 'PropertyController@create')->name('property.create');
            Route::post('{client}/payments/store', 'PropertyController@store')->name('property.store');

            Route::get('{client}/payments', 'PaymentController@index')->name('payments');
            Route::get('{client}/payments/{property}', 'PaymentController@show')->name('payments.show');
            Route::post('{client}/payments/{property}/generate-payments', 'PaymentController@generatePayments')->name('payments.generate');
            Route::get('{client}/payments/{property}/generate-table', 'PaymentController@generateTable')->name('payments.generate-table');

            Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
                Route::delete('{payment}', 'PaymentController@destroy')->name('destroy');
                Route::get('edit/{payment}', 'PaymentController@edit')->name('edit');
                Route::put('edit/{payment}', 'PaymentController@update')->name('update');
                Route::get('create/{property}', 'PaymentController@create')->name('create');
                Route::post('/{property}', 'PaymentController@store')->name('store');
            });

            Route::group(['prefix' => '{client}/preferences', 'as' => 'preferences.'], function () {
                Route::get('/', 'PreferencesController@index')->name('index');
                Route::delete('/{preference}', 'PreferencesController@destroy')->name('destroy');
                Route::get('/edit/{preference}', 'PreferencesController@edit')->name('edit');
                Route::put('/edit/{preference}', 'PreferencesController@update')->name('update');
                Route::get('/create', 'PreferencesController@create')->name('create');
                Route::post('/store', 'PreferencesController@store')->name('store');
            });

            // Client chat
            Route::group(['prefix' => '{client}/chat', 'as' => 'chat.'], function () {
                Route::get('/', 'ChatController@show')->name('show');
                Route::post('/form', 'ChatController@form')->name('form');
                Route::post('/mark', 'ChatController@mark')->name('mark');
                Route::post('/', 'ChatController@create')->name('create');
            });

            Route::get('messages/{clientId}', 'ChatController@fetchMessages')->name('chat.fetchMessages');
            Route::post('messages', 'ChatController@sendMessage')->name('chat.sendMessage');

            Route::post('/get-investment-properties', [\App\Http\Controllers\Admin\Crm\Client\IndexController::class, 'getInvestmentProperties'])->name('getInvestmentProperties');
        });

        Route::group(['namespace' => 'Board', 'prefix' => '/board', 'as' => 'board.'], function () {

            // Main board
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/create', 'IndexController@create')->name('create');
            Route::post('/store', 'IndexController@store')->name('store');
            Route::get('/{board}/edit', 'IndexController@edit')->name('edit');
            Route::get('/{board}/show', 'IndexController@show')->name('show');
            Route::put('/{board}', 'IndexController@update')->name('update');
            Route::delete('/{board}', 'IndexController@destroy')->name('destroy');

            // Stages
            Route::post('/stage/form', 'StageController@form')->name('stage.form');
            Route::post('/stage', 'StageController@store')->name('stage.store');
            Route::delete('/stage/{stage}', 'StageController@destroy')->name('stage.destroy');
            Route::post('/stage/sort', 'StageController@sort')->name('stage.sort');

            // Tasks
            Route::post('/task/form', 'TaskController@form')->name('task.form');
            Route::post('/task', 'TaskController@store')->name('task.store');
            Route::delete('/task/{task}', 'TaskController@destroy')->name('task.destroy');
            Route::post('/task/assign', 'TaskController@assign')->name('task.assign');
            Route::post('/task/save-assign', 'TaskController@saveAssign')->name('task.assign.save');
            Route::post('/task/sort', 'TaskController@sort')->name('task.sort');
        });

        Route::group(['namespace' => 'Calendar', 'prefix' => '/calendar', 'as' => 'calendar.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/table', 'IndexController@table')->name('table');
            Route::post('/', 'IndexController@store')->name('store');
            Route::get('/events', 'IndexController@show')->name('show');
            Route::get('/eventsTable', 'IndexController@datatable')->name('datatable');

            Route::put('{event}/move', 'IndexController@move')->name('event.move');
            Route::delete('{event}/delete', 'IndexController@destroy')->name('event.destroy');
            Route::post('{event}/edit', 'IndexController@edit')->name('event.edit');
            Route::put('{event}/update', 'IndexController@update')->name('event.update');

            Route::post('{event}/changeEventStatus', 'IndexController@changeEventStatus')->name('event.changeStatus');

            Route::post('form', 'IndexController@create')->name('create');
        });
    });

    // DeveloPro
    Route::group(['namespace' => 'Developro', 'prefix' => '/developro', 'as' => 'developro.'], function () {

        // Ustawienia CMS
        Route::group(['namespace' => 'Settings', 'prefix' => '/settings', 'as' => 'settings.'], function () {
            Route::get('/', 'IndexController@index')->name('index');
        });

        Route::get('investment/fix-position', 'Investment\IndexController@fixPosition')->name('fix-position');

        Route::resources([
            'investment' => 'Investment\IndexController',
            'property-price-components' => 'PropertyPrice\IndexController',
            'investment-company' => 'Company\IndexController',
            'investment-sale-point' => 'Investment\SalePointController'
        ]);

        Route::get('property-details/{property}', 'Investment\IndexController@propertyDetails')->name('property-details');


        Route::post('investment/get-templates', 'Investment\IndexController@getTemplates')->name('investment.getTemplates');
        Route::post('investment/update-templates', 'Investment\IndexController@updateTemplates')->name('investment.updateTemplates');
        Route::get('investment/{investment}/templates', 'Investment\IndexController@templates')->name('investment.templates');

        Route::group(['middleware' => 'check.investment.permission', 'prefix' => '/investment', 'as' => 'investment.'], function () {

            Route::resources([
                //'{investment}/import' => 'Import\IndexController',
                '{investment}/page' => 'Page\IndexController',
                '{investment}/article' => 'Article\IndexController',
                '{investment}/plan' => 'Plan\IndexController',
                '{investment}/search' => 'Search\IndexController',
                '{investment}/houses' => 'House\HouseController',
                '{investment}/floors' => 'Floor\FloorController',
                '{investment}/floor/{floor}/properties' => 'Property\PropertyController',
                '{investment}/buildings' => 'Building\BuildingController',
                '{investment}/building.floors' => 'Building\BuildingFloorController',
                '{investment}/building.floor.properties' => 'Building\BuildingPropertyController',
                '{investment}/property/{property}/message' => 'Property\InboxController',
                '{investment}/floor/{floor}/others' => 'Property\OthersController',
                '{investment}/building.floor.others' => 'Building\BuildingOthersController',
            ]);

            Route::get('{investment}/popup', 'Popup\IndexController@index')->name('popup.index');
            Route::post('{investment}/popup', 'Popup\IndexController@update')->name('popup.update');

            // Iframes
            Route::get('{investment}/iframe', 'Iframe\IndexController@index')->name('iframe.index');
            Route::post('{investment}/iframe', 'Iframe\IndexController@store')->name('iframe.store');

            Route::get('{investment}/log', 'Investment\IndexController@log')->name('log');
            Route::get('{investment}/datatable', 'Investment\IndexController@datatable')->name('log.datatable');
            Route::get('{investment}/events', 'Investment\IndexController@events')->name('events');
            Route::get('{investment}/eventTable', 'Investment\IndexController@eventtable')->name('eventtable');

            Route::get('{investment}/properties', 'Property\PropertyController@fetchProperties');
            Route::get('{investment}/available-properties', 'Property\PropertyController@fetchAvailableProperties');

            Route::prefix('{investment}/payments')->group(function () {
                Route::get('/', 'Payments\IndexController@index')->name('payments');
                Route::get('/create', 'Payments\IndexController@create')->name('payments.create');
                Route::get('/{payment}/edit', 'Payments\IndexController@edit')->name('payments.edit');

                Route::post('/', 'Payments\IndexController@store')->name('payments.store');
                Route::post('/calculate', 'Payments\IndexController@calculate')->name('payments.calculate');

                Route::put('/{payment}', 'Payments\IndexController@update')->name('payments.update');
                Route::delete('/{payment}', 'Payments\IndexController@destroy')->name('payments.destroy');
            });


            Route::post('{investment}/{floor}/{property}/related', 'Property\PropertyController@storerelated')->name('related.store');
            Route::post('{investment}/{floor}/{property}/remove-related', 'Property\PropertyController@removerelated')->name('related.remove');


            //admin.developro.investment.property.history
            Route::get('{investment}/property/{property}/history', 'Property\HistoryController@show')->name('property.history');


            Route::get('{investment}/floors/{floor}/copy', 'Floor\FloorController@copy')->name('floors.copy');
            Route::get('{investment}/building/{building}/floors/{floor}/copy', 'Building\BuildingFloorController@copy')->name('building.floors.copy');
        });
        Route::post('ajax/investment', 'Investment\IndexController@ajax')->name('ajax.investment');

    });

    //Notes v2
    Route::group(['namespace' => 'Note'], function () {
        Route::post('notes', 'IndexController@store')->name('notes.store');
        Route::delete('notes/{note}/delete', 'IndexController@destroy')->name('notes.destroy');
        Route::put('notes/{note}/update', 'IndexController@update')->name('notes.update');
    });


    // Mass mail
    Route::group(['namespace' => 'MassMail', 'prefix' => '/mass-mail', 'as' => 'mass-mail.'], function () {
        Route::get('/', 'IndexController@index')->name('index');
        Route::get('/schedule', 'IndexController@schedule')->name('schedule');
        Route::delete('/schedule/{id}', 'IndexController@destroySchedule')->name('schedule.destroy');

        Route::get('/newsletter-templates', 'IndexController@newsletterTemplates')->name('newsletter-templates');

        Route::post('/send', 'IndexController@send')->name('send');
        Route::post('/send-test', 'IndexController@sendTest')->name('send-test');
        Route::post('/upload-custom-template', [CustomTemplateController::class, 'upload'])->name('custom-template.upload');
    })->middleware(['check.smtp.config', 'set.user.smtp.config']);

    // Urlopy
    Route::group(['namespace' => 'Absence', 'prefix' => '/absences', 'as' => 'absences.'], function () {
        Route::get('/', 'IndexController@index')->name('index');
        Route::post('/', 'IndexController@store')->name('store');
        Route::get('/create', 'IndexController@create')->name('create');
        Route::get('/{absence}/edit', 'IndexController@edit')->name('edit');
        Route::put('/{absence}', 'IndexController@update')->name('update');
        Route::delete('/{absence}', 'IndexController@destroy')->name('destroy');
    });
});

Route::group(['namespace' => 'Facebook', 'prefix' => 'auth/facebook', 'middleware' => 'auth'], function () {
    Route::get('/', 'IndexController@redirectToProvider')->name('redirectToProvider');
    Route::get('/callback', 'IndexController@handleProviderCallback')->name('handleProviderCallback');
    Route::get('/post', 'IndexController@post')->name('post');
    Route::get('/delete/{access_token}', 'IndexController@delete')->name('facebook.page.delete');
});

//Route::get('{uri}', [MenuController::class, 'index'])->where('uri', '([A-Za-z0-9\-\/]+)');

Route::get('/{page}', 'Front\Static\IndexController@pages')->name('static.page');
