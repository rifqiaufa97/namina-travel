<?php

namespace App\Console\Commands;

use App\Models\Destination;
use App\Models\Wishlist;
use Illuminate\Console\Command;

class SyncWishlistCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishlist:sync-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize the wishlist counts for all destinations';
    
    /**
     * The Wishlist model instance.
     *
     * @var \App\Models\Wishlist
     */
    protected $wishlist;
    
    /**
     * The Destination model instance.
     *
     * @var \App\Models\Destination
     */
    protected $destination;

    /**
     * Create a new command instance.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @param  \App\Models\Destination  $destination
     * @return void
     */
    public function __construct(Wishlist $wishlist, Destination $destination)
    {
        parent::__construct();
        
        $this->wishlist = $wishlist;
        $this->destination = $destination;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting wishlist count synchronization...');
        
        $bar = $this->output->createProgressBar($this->destination->count());
        $bar->start();
        
        // Get all destinations and update their wishlist counts
        $this->destination->chunk(100, function ($destinations) use ($bar) {
            foreach ($destinations as $destination) {
                $count = $this->wishlist->where('destination_id', $destination->id)->count();
                $destination->update(['wishlist_count' => $count]);
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine(2);
        $this->info('Wishlist count synchronization completed successfully!');
        
        return 0;
    }
}
