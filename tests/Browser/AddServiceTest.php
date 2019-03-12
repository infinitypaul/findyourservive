<?php

namespace Tests\Browser;

use App\User;
use Tests\Browser\Pages\AddServicePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddServiceTest extends DuskTestCase
{

    use DatabaseMigrations;
    /**
     * An Admin Can Add a New Service.
     *
     * @return void
     * @throws \Throwable
     */
    public function testExample()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new AddServicePage)
                ->fillForm()
            ;
        });
    }
}
