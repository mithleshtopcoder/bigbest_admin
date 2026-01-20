<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\MyHelper;

class ProductImageController extends Controller
{
    public $folder;
    function __construct()
    {
        $this->folder = 'product_variant';
    }
    /**
     * Display images for a variant.
     */
    public function index($product_id, $variant_id)
    {
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($variant_id);

        $images = ProductImage::where('product_variant_id', $variant_id)
            ->orderBy('is_primary', 'desc')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    /**
     * Store new images for a variant.
     */
    public function store(Request $request, $product_id, $variant_id)
    {
        $variant = ProductVariant::where('product_id', $product_id)
            ->findOrFail($variant_id);

        $validator = Validator::make($request->all(), [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $uploadedImages = [];
            $files = $request->file('images');

            foreach ($files as $index => $file) {
                // Upload image using helper
                $imageName = MyHelper::uploadImage($file, $this->folder);

                // Check if this is the first image and no primary exists
                $isPrimary = ($index === 0 && !ProductImage::where('product_variant_id', $variant_id)->where('is_primary', true)->exists());

                $image = ProductImage::create([
                    'product_id' => $product_id,
                    'product_variant_id' => $variant_id,
                    'image_path' => $imageName,
                    'alt_text' => $variant->name . ' - Image ' . ($index + 1),
                    'sort_order' => ProductImage::where('product_variant_id', $variant_id)->max('sort_order') + 1,
                    'is_primary' => $isPrimary,
                ]);

                $uploadedImages[] = $image;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' image(s) uploaded successfully',
                'data' => $uploadedImages
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set an image as primary.
     */
    public function setPrimary($product_id, $variant_id, $id)
    {
        try {
            $image = ProductImage::where('product_variant_id', $variant_id)
                ->findOrFail($id);

            DB::beginTransaction();

            // Unset other primary images for this variant
            ProductImage::where('product_variant_id', $variant_id)
                ->where('id', '!=', $id)
                ->update(['is_primary' => false]);

            // Set this image as primary
            $image->update(['is_primary' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Primary image updated successfully',
                'data' => $image->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update primary image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image.
     */
    public function delete($product_id, $variant_id, $id)
    {
        try {
            $image = ProductImage::where('product_variant_id', $variant_id)->findOrFail($id);

            // Delete physical file
            MyHelper::removeImage($image->image_path, $this->folder);

            // Delete database record
            $image->delete();

            // If this was primary, set another image as primary if available
            if ($image->is_primary) {
                $nextImage = ProductImage::where('product_variant_id', $variant_id)
                    ->orderBy('sort_order')
                    ->first();
                
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
