<?php

namespace App\Providers\App\Listeners;


use App\Providers\App\Events\CategoryExistence;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCategoryExistence implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CategoryExistence  $event
     * @return void
     */
    public function handle(CategoryExistence $event)
    {
        //
    }
}
