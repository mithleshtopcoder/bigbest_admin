<?php

namespace App\Helpers;
use App\Models\Notification;
use App\Events\NotificationEvent;

use Illuminate\Support\Facades\File;

class MyHelper
{
    public static function uploadImage($image, $folder = 'common', $name = null)
    {
        if (isset($name)) {
            $imageName = $name . '.' . $image->extension();
        } else {
            // Generate unique name: folder-image-date-timestamp-uniqid-randomstring.extension
            $date = date('Ymd');
            $uniqueId = uniqid() . '-' . str()->random(4);
            $imageName = 'image-' . $date .'-' . $uniqueId . '.' . $image->extension();
        }
        $path = 'images/' . $folder;
        $sto = 'app//public/images/' . $folder;
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        // if($folder == 'customers' || $folder == 'users' || $folder == 'companies' || $folder == 'digital-signature' || $folder == 'company-logo')
        if ($folder == 'customers' || $folder == 'users')
            if (!File::isDirectory($sto)) {
                File::makeDirectory($sto, 0777, true, true);
                $image->move(storage_path($sto), $imageName);
            } else {
                $image->move(storage_path($sto), $imageName);
            }
        else
            $image->move(public_path('images/' . $folder), $imageName);

        // return ENV('IMAGE_URL').$path.'/'.$imageName;
        return $imageName;
    }
    //delete an image
    public static function removeImage($image, $folder = 'common')
    {
        $path = 'images/' . $folder . '/' . $image;
        if (\File::exists(public_path($path))) {
            $d = \File::delete(public_path($path));
            return 'Image deleted';
        } else if (\File::exists(storage_path($path))) {
            $d = \File::delete(storage_path($path));
            return 'Image deleted';
        } else {
            return 'File does not exists.';
        }
    }

    public static function getImage($image, $folder)
    {
        return ENV('FILE_PATH'). 'images/' . $folder . '/' . $image;
    }
    


    public static function uploadFile($image, $folder = 'common', $name = null)
    {
        if (isset($name)) {
            $imageName = $name . '.' . $image->getClientOriginalExtension();
        } else {
            // Generate unique name: folder-doc-date-timestamp-uniqid-randomstring.extension
            $date = date('Ymd');
            $uniqueId = uniqid() . '-' . str()->random(8);
            $imageName = $folder . '-doc-' . $date . '-' . time() . '-' . $uniqueId . '.' . $image->getClientOriginalExtension();
        }
        $path = 'documents/' . $folder;
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        if ($folder == 'customers' || $folder == 'users')
            $image->move(storage_path($path), $imageName);
        else
            $image->move(public_path('documents/' . $folder), $imageName);

        // return ENV('IMAGE_URL').$path.'/'.$imageName;
        return $imageName;
    }

    public static function removeFile($image, $folder = 'common')
    {
        $path = 'documents/' . $folder . '/' . $image;
        if (\File::exists(public_path($path))) {
            $d = \File::delete(public_path($path));
            return 'Document deleted';
        } else if (\File::exists(storage_path($path))) {
            $d = \File::delete(storage_path($path));
            return 'Document deleted';
        } else {
            return 'File does not exists.';
        }
    }
    


    public static function getFile($file, $folder)
    {
        return ENV('FILE_PATH') . 'documents/' . $folder . '/' . $file;
    }

    /**
     * Get option list by option master ID
     * 
     * @param int $optionMasterId The ID of the option master
     * @param bool $activeOnly Whether to return only active options (default: true)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getOptionList($optionMasterId, $activeOnly = true)
    {
        $query = \App\Models\Option::where('option_master_id', $optionMasterId);
        
        if ($activeOnly) {
            $query->where('status', true);
        }
        
        return $query->get();
    }

    /**
     * Format currency amount
     * 
     * @param float|int|string $amount The amount to format
     * @param string $currency The currency symbol (default: ₹)
     * @param int $decimals Number of decimal places (default: 2)
     * @return string Formatted currency string
     */
    public static function formatCurrency($amount, $currency = '₹', $decimals = 2)
    {
        if ($amount === null || $amount === '') {
            return $currency . '0.00';
        }
        
        $amount = (float) $amount;
        return $currency . number_format($amount, $decimals, '.', ',');
    }

    public static function numberToWords($number)
{
    $formatter = new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT);
    return ucwords($formatter->format($number));
}

/**
     * Create and optionally broadcast a notification
     *
     * @param int $customerId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string|null $actionUrl
     * @param bool $broadcast
     * @return Notification
     */
    public static function createNotification(
        int $customerId,
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $actionUrl = null,
        bool $broadcast = true
    ): Notification {
        $notification = Notification::create([
            'customer_id' => $customerId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'is_read' => false,
            'sent_at' => now(),
        ]);

        if ($broadcast) {
            event(new NotificationEvent($notification));
        }

        return $notification;
    }
}

/*
 * ============================================
 * HOW TO USE IN CONTROLLER
 * ============================================
 * 
 * First, add the use statement at the top of your controller:
 * 
 * use App\Helpers\MyHelper;
 * use Illuminate\Support\Facades\File;
 * 
 * 
 * ============================================
 * 1. UPLOAD IMAGE
 * ============================================
 * 
 * public function store(Request $request)
 * {
 *     $request->validate([
 *         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
 *     ]);
 * 
 *     // Upload image with default folder 'common'
 *     $imageName = MyHelper::uploadImage($request->file('image'));
 * 
 *     // Upload image to specific folder
 *     $imageName = MyHelper::uploadImage($request->file('image'), 'products');
 * 
 *     // Upload image with custom name
 *     $imageName = MyHelper::uploadImage($request->file('image'), 'products', 'product-123');
 * 
 *     // Save to database
 *     $product = Product::create([
 *         'name' => $request->name,
 *         'image' => $imageName,
 *         // ... other fields
 *     ]);
 * 
 *     return response()->json(['message' => 'Image uploaded successfully', 'image' => $imageName]);
 * }
 * 
 * 
 * ============================================
 * 2. REMOVE IMAGE
 * ============================================
 * 
 * public function destroy($id)
 * {
 *     $product = Product::findOrFail($id);
 * 
 *     // Remove image with default folder 'common'
 *     $result = MyHelper::removeImage($product->image);
 * 
 *     // Remove image from specific folder
 *     $result = MyHelper::removeImage($product->image, 'products');
 * 
 *     // Delete the record
 *     $product->delete();
 * 
 *     return response()->json(['message' => $result]);
 * }
 * 
 * 
 * ============================================
 * 3. GET IMAGE URL
 * ============================================
 * 
 * public function show($id)
 * {
 *     $product = Product::findOrFail($id);
 * 
 *     // Get full image URL
 *     $imageUrl = MyHelper::getImage($product->image, 'products');
 * 
 *     return response()->json([
 *         'product' => $product,
 *         'image_url' => $imageUrl
 *     ]);
 * }
 * 
 * 
 * ============================================
 * 4. UPLOAD FILE/DOCUMENT
 * ============================================
 * 
 * public function uploadDocument(Request $request)
 * {
 *     $request->validate([
 *         'document' => 'required|file|mimes:pdf,doc,docx|max:5120',
 *     ]);
 * 
 *     // Upload file with default folder 'common'
 *     $fileName = MyHelper::uploadFile($request->file('document'));
 * 
 *     // Upload file to specific folder
 *     $fileName = MyHelper::uploadFile($request->file('document'), 'invoices');
 * 
 *     // Upload file with custom name
 *     $fileName = MyHelper::uploadFile($request->file('document'), 'invoices', 'invoice-2024');
 * 
 *     return response()->json(['message' => 'File uploaded successfully', 'file' => $fileName]);
 * }
 * 
 * 
 * ============================================
 * 5. REMOVE FILE/DOCUMENT
 * ============================================
 * 
 * public function deleteDocument($id)
 * {
 *     $document = Document::findOrFail($id);
 * 
 *     // Remove file with default folder 'common'
 *     $result = MyHelper::removeFile($document->file_name);
 * 
 *     // Remove file from specific folder
 *     $result = MyHelper::removeFile($document->file_name, 'invoices');
 * 
 *     // Delete the record
 *     $document->delete();
 * 
 *     return response()->json(['message' => $result]);
 * }
 * 
 * 
 * ============================================
 * 6. GET FILE URL
 * ============================================
 * 
 * public function downloadDocument($id)
 * {
 *     $document = Document::findOrFail($id);
 * 
 *     // Get full file URL
 *     $fileUrl = MyHelper::getFile($document->file_name, 'invoices');
 * 
 *     return response()->json([
 *         'document' => $document,
 *         'file_url' => $fileUrl
 *     ]);
 * }
 * 
 * 
 * ============================================
 * COMPLETE EXAMPLE - CREATE WITH IMAGE UPLOAD
 * ============================================
 * 
 * public function store(Request $request)
 * {
 *     $request->validate([
 *         'name' => 'required|string',
 *         'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
 *     ]);
 * 
 *     try {
 *         // Upload image
 *         $imageName = MyHelper::uploadImage($request->file('image'), 'products');
 * 
 *         // Create product
 *         $product = Product::create([
 *             'name' => $request->name,
 *             'image' => $imageName,
 *             'status' => 1,
 *         ]);
 * 
 *         return response()->json([
 *             'success' => true,
 *             'message' => 'Product created successfully',
 *             'data' => $product,
 *             'image_url' => MyHelper::getImage($imageName, 'products')
 *         ], 201);
 * 
 *     } catch (\Exception $e) {
 *         return response()->json([
 *             'success' => false,
 *             'message' => 'Error: ' . $e->getMessage()
 *         ], 500);
 *     }
 * }
 * 
 * 
 * ============================================
 * COMPLETE EXAMPLE - UPDATE WITH IMAGE
 * ============================================
 * 
 * public function update(Request $request, $id)
 * {
 *     $product = Product::findOrFail($id);
 * 
 *     $request->validate([
 *         'name' => 'required|string',
 *         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
 *     ]);
 * 
 *     try {
 *         // If new image is uploaded, remove old one and upload new
 *         if ($request->hasFile('image')) {
 *             // Remove old image
 *             MyHelper::removeImage($product->image, 'products');
 *             
 *             // Upload new image
 *             $imageName = MyHelper::uploadImage($request->file('image'), 'products');
 *             $product->image = $imageName;
 *         }
 * 
 *         $product->name = $request->name;
 *         $product->save();
 * 
 *         return response()->json([
 *             'success' => true,
 *             'message' => 'Product updated successfully',
 *             'data' => $product,
 *             'image_url' => MyHelper::getImage($product->image, 'products')
 *         ]);
 * 
 *     } catch (\Exception $e) {
 *         return response()->json([
 *             'success' => false,
 *             'message' => 'Error: ' . $e->getMessage()
 *         ], 500);
 *     }
 * }
 * 
 */