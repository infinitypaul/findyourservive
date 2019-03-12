<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class AddServicePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/services';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@new_record' => 'a[id="new_record"]',
            '@title' => 'input[id="title"]',
            '@description' => 'input[id="description"]',
            '@map' => 'input[id="map"]',
            '@create' => 'button[id="create"]',

        ];
    }

    public function fillForm(Browser $browser){
        $browser
            ->waitForText('New Record')
            ->click('@new_record')->pause(1000)
            ->type('@title', 'Fatmot')->pause(500)
            ->type('@description', 'The Best Food Around Town That You Can Eat')->pause(500)
            ->type('@map', '31')->pause(2000)->keys('@map', ['{ARROW_DOWN}'])->pause(1000)->keys('@map', ['{ARROW_DOWN}'])->keys('@map', ['{TAB}'])->pause(5000)
            ->press('Create')
            ->with('.table', function ($service) {
                $service->assertSee('Fatmot');
            })
        ;
    }
}
