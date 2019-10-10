<?php

namespace App\Providers\App\Events;

use App\Category as Category;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryExistence implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   // public $broadcastQueue = 'category-queue';
    //public $categoryId;
    public $categoryTitle;
    
    public function __construct($title)
    {
      //  $this->categoryId = $id;
        $this->categoryTitle = $title;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['category-event'];
    }
    public function broadcastAs() {
        return 'categoryfired';
    }
    public function broadcastWith() {
        $category = Category::where('category_name', $this->categoryTitle)->get()->toArray()[0];
        return [
            'category_name' => $category['category_name'],
            'status' => $category['category_status'] == 1 ? 'Active' : 'Inactive',
            'created_at' => Date('H:i:s', strtotime($category['created_at'])),
            'updated_at' => Date('H:i:s', strtotime($category['updated_at']))
        ];
    }
}
