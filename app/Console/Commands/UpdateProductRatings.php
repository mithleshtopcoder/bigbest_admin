<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Support\Facades\DB;

class UpdateProductRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-ratings {--product-id= : Update rating for a specific product ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product ratings and total reviews based on approved reviews';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->option('product-id');

        if ($productId) {
            // Update specific product
            $this->updateProductRating($productId);
            $this->info("Updated rating for product ID: {$productId}");
        } else {
            // Update all products
            $this->info('Updating ratings for all products...');
            
            $products = Product::all();
            $bar = $this->output->createProgressBar($products->count());
            $bar->start();

            $updated = 0;
            foreach ($products as $product) {
                $this->updateProductRating($product->id);
                $updated++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Successfully updated ratings for {$updated} products.");
        }

        return Command::SUCCESS;
    }

    /**
     * Update product rating and total reviews
     */
    private function updateProductRating($productId)
    {
        $approvedReviews = ProductReview::where('product_id', $productId)
            ->where('status', 'approved')
            ->get();

        $totalReviews = $approvedReviews->count();
        $averageRating = $totalReviews > 0 
            ? round($approvedReviews->avg('rating'), 2) 
            : 0.00;

        Product::where('id', $productId)->update([
            'rating' => $averageRating,
            'total_reviews' => $totalReviews,
        ]);
    }
}
