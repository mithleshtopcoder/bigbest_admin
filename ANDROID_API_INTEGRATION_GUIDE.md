# Android API Integration Guide
## Organic Fresh E-Commerce & POS Billing System

This guide provides detailed instructions for integrating the Organic Fresh API into your Android application. The API supports location-based product display, cart management, order processing, coupons, offers, and combo deals.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [API Base Configuration](#api-base-configuration)
3. [Authentication Setup](#authentication-setup)
4. [Location Services](#location-services)
5. [API Integration by Feature](#api-integration-by-feature)
6. [Error Handling](#error-handling)
7. [Best Practices](#best-practices)
8. [Complete Code Examples](#complete-code-examples)

---

## Prerequisites

### Required Dependencies

Add these dependencies to your `build.gradle` (Module: app):

```gradle
dependencies {
    // Retrofit for API calls
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.11.0'
    
    // Coroutines for async operations
    implementation 'org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3'
    
    // ViewModel and LiveData
    implementation 'androidx.lifecycle:lifecycle-viewmodel-ktx:2.6.2'
    implementation 'androidx.lifecycle:lifecycle-livedata-ktx:2.6.2'
    
    // Location Services
    implementation 'com.google.android.gms:play-services-location:21.0.1'
    
    // Image Loading
    implementation 'com.github.bumptech.glide:glide:4.16.0'
}
```

### Permissions

Add these permissions to your `AndroidManifest.xml`:

```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
<uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
```

---

## API Base Configuration

### 1. Create API Constants

Create a file `ApiConstants.kt`:

```kotlin
object ApiConstants {
    const val BASE_URL = "http://your-api-domain.com/api/v1/"
    const val TIMEOUT_SECONDS = 30L
    
    // API Endpoints
    const val LOGIN = "login"
    const val REGISTER = "register"
    const val PRODUCTS = "products"
    const val PRODUCTS_FEATURED = "products/featured"
    const val PRODUCTS_TOP_SELLING = "products/top-selling"
    const val CATEGORIES = "categories"
    const val BRANDS = "brands"
    const val CART = "cart"
    const val CART_COMBO = "cart/combo"
    const val ORDERS = "orders"
    const val COUPONS = "coupons"
    const val COUPONS_VALIDATE = "coupons/validate"
    const val COUPONS_APPLY = "coupons/apply"
    const val OFFERS = "offers"
    const val OFFERS_FEATURED = "offers/featured"
    const val COMBO_OFFERS = "combo-offers"
    const val COMBO_OFFERS_FEATURED = "combo-offers/featured"
}
```

### 2. Create Retrofit Service

Create `ApiService.kt`:

```kotlin
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import java.util.concurrent.TimeUnit

object ApiService {
    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }
    
    private val okHttpClient = OkHttpClient.Builder()
        .addInterceptor(loggingInterceptor)
        .addInterceptor { chain ->
            val request = chain.request().newBuilder()
                .addHeader("Accept", "application/json")
                .addHeader("Content-Type", "application/json")
                .build()
            chain.proceed(request)
        }
        .connectTimeout(ApiConstants.TIMEOUT_SECONDS, TimeUnit.SECONDS)
        .readTimeout(ApiConstants.TIMEOUT_SECONDS, TimeUnit.SECONDS)
        .writeTimeout(ApiConstants.TIMEOUT_SECONDS, TimeUnit.SECONDS)
        .build()
    
    val retrofit: Retrofit = Retrofit.Builder()
        .baseUrl(ApiConstants.BASE_URL)
        .client(okHttpClient)
        .addConverterFactory(GsonConverterFactory.create())
        .build()
    
    val api: ApiInterface = retrofit.create(ApiInterface::class.java)
}
```

### 3. Create API Interface

Create `ApiInterface.kt`:

```kotlin
import retrofit2.Response
import retrofit2.http.*

interface ApiInterface {
    
    // Authentication
    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<ApiResponse<LoginResponse>>
    
    @POST("register")
    suspend fun register(@Body request: RegisterRequest): Response<ApiResponse<RegisterResponse>>
    
    // Products
    @GET("products")
    suspend fun getProducts(
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?,
        @Query("category_id") categoryId: Int? = null,
        @Query("brand_id") brandId: Int? = null,
        @Query("sort_by") sortBy: String? = null,
        @Query("per_page") perPage: Int? = null
    ): Response<ApiResponse<List<Product>>>
    
    @GET("products/featured")
    suspend fun getFeaturedProducts(
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?,
        @Query("limit") limit: Int? = null
    ): Response<ApiResponse<List<Product>>>
    
    @GET("products/{slug}")
    suspend fun getProductBySlug(
        @Path("slug") slug: String,
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?
    ): Response<ApiResponse<Product>>
    
    // Categories
    @GET("categories")
    suspend fun getCategories(): Response<ApiResponse<List<Category>>>
    
    // Brands
    @GET("brands")
    suspend fun getBrands(): Response<ApiResponse<List<Brand>>>
    
    // Cart
    @GET("cart")
    suspend fun getCart(
        @Header("Authorization") token: String,
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?
    ): Response<ApiResponse<CartResponse>>
    
    @POST("cart")
    suspend fun addToCart(
        @Header("Authorization") token: String,
        @Body request: AddToCartRequest
    ): Response<ApiResponse<CartItem>>
    
    @POST("cart/combo")
    suspend fun addComboToCart(
        @Header("Authorization") token: String,
        @Body request: AddComboRequest
    ): Response<ApiResponse<ComboCartResponse>>
    
    @PUT("cart/{id}")
    suspend fun updateCartItem(
        @Header("Authorization") token: String,
        @Path("id") cartItemId: Int,
        @Body request: UpdateCartRequest
    ): Response<ApiResponse<CartItem>>
    
    @DELETE("cart/{id}")
    suspend fun removeFromCart(
        @Header("Authorization") token: String,
        @Path("id") cartItemId: Int
    ): Response<ApiResponse<Unit>>
    
    // Coupons
    @GET("coupons")
    suspend fun getCoupons(): Response<ApiResponse<List<Coupon>>>
    
    @POST("coupons/validate")
    suspend fun validateCoupon(@Body request: ValidateCouponRequest): Response<ApiResponse<CouponValidationResponse>>
    
    @POST("coupons/apply")
    suspend fun applyCoupon(
        @Header("Authorization") token: String,
        @Body request: ApplyCouponRequest
    ): Response<ApiResponse<CouponApplicationResponse>>
    
    // Offers
    @GET("offers")
    suspend fun getOffers(
        @Query("type") type: String? = null,
        @Query("product_id") productId: Int? = null,
        @Query("category_id") categoryId: Int? = null
    ): Response<ApiResponse<List<Offer>>>
    
    @GET("offers/featured")
    suspend fun getFeaturedOffers(@Query("limit") limit: Int? = null): Response<ApiResponse<List<Offer>>>
    
    // Combo Offers
    @GET("combo-offers")
    suspend fun getComboOffers(
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?
    ): Response<ApiResponse<List<ComboOffer>>>
    
    @GET("combo-offers/featured")
    suspend fun getFeaturedComboOffers(
        @Query("latitude") latitude: Double?,
        @Query("longitude") longitude: Double?,
        @Query("limit") limit: Int? = null
    ): Response<ApiResponse<List<ComboOffer>>>
    
    // Orders
    @POST("orders")
    suspend fun createOrder(
        @Header("Authorization") token: String,
        @Body request: CreateOrderRequest
    ): Response<ApiResponse<Order>>
    
    @GET("orders")
    suspend fun getOrders(
        @Header("Authorization") token: String,
        @Query("status") status: String? = null
    ): Response<ApiResponse<List<Order>>>
}
```

---

## Authentication Setup

### 1. Create Auth Manager

Create `AuthManager.kt`:

```kotlin
import android.content.Context
import android.content.SharedPreferences

class AuthManager(context: Context) {
    private val prefs: SharedPreferences = 
        context.getSharedPreferences("auth_prefs", Context.MODE_PRIVATE)
    
    fun saveToken(token: String) {
        prefs.edit().putString("auth_token", token).apply()
    }
    
    fun getToken(): String? {
        return prefs.getString("auth_token", null)
    }
    
    fun isLoggedIn(): Boolean {
        return getToken() != null
    }
    
    fun logout() {
        prefs.edit().clear().apply()
    }
    
    fun getAuthHeader(): String {
        return "Bearer ${getToken()}"
    }
}
```

### 2. Update API Interface with Auth Interceptor

Update `ApiService.kt` to include auth token:

```kotlin
fun createApiService(context: Context): ApiInterface {
    val authManager = AuthManager(context)
    
    val authInterceptor = Interceptor { chain ->
        val original = chain.request()
        val token = authManager.getToken()
        
        val requestBuilder = original.newBuilder()
            .addHeader("Accept", "application/json")
            .addHeader("Content-Type", "application/json")
        
        token?.let {
            requestBuilder.addHeader("Authorization", "Bearer $it")
        }
        
        chain.proceed(requestBuilder.build())
    }
    
    val okHttpClient = OkHttpClient.Builder()
        .addInterceptor(authInterceptor)
        .addInterceptor(loggingInterceptor)
        .build()
    
    val retrofit = Retrofit.Builder()
        .baseUrl(ApiConstants.BASE_URL)
        .client(okHttpClient)
        .addConverterFactory(GsonConverterFactory.create())
        .build()
    
    return retrofit.create(ApiInterface::class.java)
}
```

---

## Location Services

### 1. Create Location Manager

Create `LocationManager.kt`:

```kotlin
import android.Manifest
import android.content.Context
import android.content.pm.PackageManager
import android.location.Location
import androidx.core.app.ActivityCompat
import com.google.android.gms.location.*
import kotlinx.coroutines.suspendCancellableCoroutine
import kotlin.coroutines.resume

class LocationManager(private val context: Context) {
    private val fusedLocationClient: FusedLocationProviderClient =
        LocationServices.getFusedLocationProviderClient(context)
    
    suspend fun getCurrentLocation(): Location? = suspendCancellableCoroutine { continuation ->
        if (ActivityCompat.checkSelfPermission(
                context,
                Manifest.permission.ACCESS_FINE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED &&
            ActivityCompat.checkSelfPermission(
                context,
                Manifest.permission.ACCESS_COARSE_LOCATION
            ) != PackageManager.PERMISSION_GRANTED
        ) {
            continuation.resume(null)
            return@suspendCancellableCoroutine
        }
        
        fusedLocationClient.lastLocation
            .addOnSuccessListener { location ->
                continuation.resume(location)
            }
            .addOnFailureListener {
                continuation.resume(null)
            }
    }
    
    fun requestLocationUpdates(callback: (Location) -> Unit) {
        val locationRequest = LocationRequest.create().apply {
            priority = LocationRequest.PRIORITY_HIGH_ACCURACY
            interval = 10000
            fastestInterval = 5000
        }
        
        if (ActivityCompat.checkSelfPermission(
                context,
                Manifest.permission.ACCESS_FINE_LOCATION
            ) == PackageManager.PERMISSION_GRANTED
        ) {
            fusedLocationClient.requestLocationUpdates(
                locationRequest,
                object : LocationCallback() {
                    override fun onLocationResult(locationResult: LocationResult) {
                        locationResult.lastLocation?.let(callback)
                    }
                },
                null
            )
        }
    }
}
```

### 2. Request Location Permission

In your Activity/Fragment:

```kotlin
private fun requestLocationPermission() {
    if (ContextCompat.checkSelfPermission(
            this,
            Manifest.permission.ACCESS_FINE_LOCATION
        ) != PackageManager.PERMISSION_GRANTED
    ) {
        ActivityCompat.requestPermissions(
            this,
            arrayOf(
                Manifest.permission.ACCESS_FINE_LOCATION,
                Manifest.permission.ACCESS_COARSE_LOCATION
            ),
            LOCATION_PERMISSION_REQUEST_CODE
        )
    }
}
```

---

## API Integration by Feature

### 1. Product Listing with Location

**ViewModel:**

```kotlin
class ProductViewModel(
    private val apiService: ApiInterface,
    private val locationManager: LocationManager
) : ViewModel() {
    
    private val _products = MutableLiveData<List<Product>>()
    val products: LiveData<List<Product>> = _products
    
    private val _nearestStore = MutableLiveData<Store?>()
    val nearestStore: LiveData<Store?> = _nearestStore
    
    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading
    
    private val _error = MutableLiveData<String?>()
    val error: LiveData<String?> = _error
    
    fun loadProducts(categoryId: Int? = null) {
        viewModelScope.launch {
            _isLoading.value = true
            _error.value = null
            
            try {
                val location = locationManager.getCurrentLocation()
                
                if (location == null) {
                    _error.value = "Location not available"
                    _isLoading.value = false
                    return@launch
                }
                
                val response = apiService.getProducts(
                    latitude = location.latitude,
                    longitude = location.longitude,
                    categoryId = categoryId
                )
                
                if (response.isSuccessful && response.body()?.success == true) {
                    _products.value = response.body()?.data ?: emptyList()
                    // Extract nearest store from response
                    response.body()?.nearestStore?.let {
                        _nearestStore.value = it
                    }
                } else {
                    _error.value = response.body()?.message ?: "Failed to load products"
                }
            } catch (e: Exception) {
                _error.value = e.message
            } finally {
                _isLoading.value = false
            }
        }
    }
}
```

**Activity/Fragment Usage:**

```kotlin
class ProductListActivity : AppCompatActivity() {
    private lateinit var viewModel: ProductViewModel
    private lateinit var adapter: ProductAdapter
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_product_list)
        
        val apiService = createApiService(this)
        val locationManager = LocationManager(this)
        viewModel = ProductViewModel(apiService, locationManager)
        
        setupRecyclerView()
        observeViewModel()
        
        // Request location permission first
        requestLocationPermission()
        
        // Load products
        viewModel.loadProducts()
    }
    
    private fun observeViewModel() {
        viewModel.products.observe(this) { products ->
            adapter.submitList(products)
        }
        
        viewModel.isLoading.observe(this) { isLoading ->
            // Show/hide loading indicator
        }
        
        viewModel.error.observe(this) { error ->
            error?.let {
                Toast.makeText(this, it, Toast.LENGTH_SHORT).show()
            }
        }
        
        viewModel.nearestStore.observe(this) { store ->
            store?.let {
                // Display nearest store info
                findViewById<TextView>(R.id.tvNearestStore).text = 
                    "Nearest Store: ${it.name} (${it.distance} km away)"
            }
        }
    }
}
```

### 2. Add to Cart with Location

**ViewModel:**

```kotlin
class CartViewModel(
    private val apiService: ApiInterface,
    private val locationManager: LocationManager,
    private val authManager: AuthManager
) : ViewModel() {
    
    private val _cartItems = MutableLiveData<List<CartItem>>()
    val cartItems: LiveData<List<CartItem>> = _cartItems
    
    fun addToCart(productVariantId: Int, quantity: Int) {
        viewModelScope.launch {
            try {
                val location = locationManager.getCurrentLocation()
                    ?: throw Exception("Location not available")
                
                val request = AddToCartRequest(
                    product_variant_id = productVariantId,
                    latitude = location.latitude,
                    longitude = location.longitude,
                    quantity = quantity
                )
                
                val response = apiService.addToCart(
                    token = authManager.getAuthHeader(),
                    request = request
                )
                
                if (response.isSuccessful && response.body()?.success == true) {
                    // Refresh cart
                    loadCart()
                } else {
                    throw Exception(response.body()?.message ?: "Failed to add to cart")
                }
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
    
    fun loadCart() {
        viewModelScope.launch {
            try {
                val location = locationManager.getCurrentLocation()
                    ?: throw Exception("Location not available")
                
                val response = apiService.getCart(
                    token = authManager.getAuthHeader(),
                    latitude = location.latitude,
                    longitude = location.longitude
                )
                
                if (response.isSuccessful && response.body()?.success == true) {
                    _cartItems.value = response.body()?.data?.items ?: emptyList()
                }
            } catch (e: Exception) {
                // Handle error
            }
        }
    }
}
```

### 3. Coupon Integration

**ViewModel:**

```kotlin
class CheckoutViewModel(
    private val apiService: ApiInterface,
    private val authManager: AuthManager
) : ViewModel() {
    
    private val _couponValidation = MutableLiveData<CouponValidationResponse?>()
    val couponValidation: LiveData<CouponValidationResponse?> = _couponValidation
    
    private val _appliedCoupon = MutableLiveData<CouponApplicationResponse?>()
    val appliedCoupon: LiveData<CouponApplicationResponse?> = _appliedCoupon
    
    fun validateCoupon(code: String, orderAmount: Double) {
        viewModelScope.launch {
            try {
                val request = ValidateCouponRequest(
                    code = code.uppercase(),
                    order_amount = orderAmount
                )
                
                val response = apiService.validateCoupon(request)
                
                if (response.isSuccessful && response.body()?.success == true) {
                    _couponValidation.value = response.body()?.data
                } else {
                    _error.value = response.body()?.message ?: "Invalid coupon"
                }
            } catch (e: Exception) {
                _error.value = e.message
            }
        }
    }
    
    fun applyCoupon(code: String, orderAmount: Double) {
        viewModelScope.launch {
            try {
                val request = ApplyCouponRequest(
                    code = code.uppercase(),
                    order_amount = orderAmount
                )
                
                val response = apiService.applyCoupon(
                    token = authManager.getAuthHeader(),
                    request = request
                )
                
                if (response.isSuccessful && response.body()?.success == true) {
                    _appliedCoupon.value = response.body()?.data
                } else {
                    _error.value = response.body()?.message ?: "Failed to apply coupon"
                }
            } catch (e: Exception) {
                _error.value = e.message
            }
        }
    }
}
```

**UI Implementation:**

```kotlin
class CheckoutActivity : AppCompatActivity() {
    private lateinit var viewModel: CheckoutViewModel
    
    private fun setupCouponSection() {
        findViewById<Button>(R.id.btnApplyCoupon).setOnClickListener {
            val couponCode = findViewById<EditText>(R.id.etCouponCode).text.toString()
            val orderAmount = calculateOrderTotal()
            
            if (couponCode.isNotEmpty()) {
                viewModel.validateCoupon(couponCode, orderAmount)
            }
        }
        
        viewModel.couponValidation.observe(this) { validation ->
            validation?.let {
                if (it.discount_amount > 0) {
                    // Show discount
                    findViewById<TextView>(R.id.tvDiscount).text = 
                        "Discount: ₹${it.discount_amount}"
                    findViewById<TextView>(R.id.tvFinalAmount).text = 
                        "Final Amount: ₹${it.final_amount}"
                    
                    // Show apply button
                    findViewById<Button>(R.id.btnConfirmCoupon).isVisible = true
                }
            }
        }
        
        findViewById<Button>(R.id.btnConfirmCoupon).setOnClickListener {
            val couponCode = findViewById<EditText>(R.id.etCouponCode).text.toString()
            val orderAmount = calculateOrderTotal()
            viewModel.applyCoupon(couponCode, orderAmount)
        }
        
        viewModel.appliedCoupon.observe(this) { applied ->
            applied?.let {
                // Coupon applied successfully
                // Save coupon_id for order creation
                selectedCouponId = it.coupon_id
                updateOrderSummary()
            }
        }
    }
}
```

### 4. Combo Offers Integration

**ViewModel:**

```kotlin
class ComboOfferViewModel(
    private val apiService: ApiInterface,
    private val locationManager: LocationManager
) : ViewModel() {
    
    private val _comboOffers = MutableLiveData<List<ComboOffer>>()
    val comboOffers: LiveData<List<ComboOffer>> = _comboOffers
    
    fun loadComboOffers() {
        viewModelScope.launch {
            try {
                val location = locationManager.getCurrentLocation()
                    ?: throw Exception("Location not available")
                
                val response = apiService.getComboOffers(
                    latitude = location.latitude,
                    longitude = location.longitude
                )
                
                if (response.isSuccessful && response.body()?.success == true) {
                    _comboOffers.value = response.body()?.data ?: emptyList()
                }
            } catch (e: Exception) {
                _error.value = e.message
            }
        }
    }
}
```

**Add Combo to Cart:**

```kotlin
fun addComboToCart(comboOfferId: Int, quantity: Int) {
    viewModelScope.launch {
        try {
            val location = locationManager.getCurrentLocation()
                ?: throw Exception("Location not available")
            
            val request = AddComboRequest(
                combo_offer_id = comboOfferId,
                latitude = location.latitude,
                longitude = location.longitude,
                quantity = quantity
            )
            
            val response = apiService.addComboToCart(
                token = authManager.getAuthHeader(),
                request = request
            )
            
            if (response.isSuccessful && response.body()?.success == true) {
                // Show success message
                // Refresh cart
                loadCart()
            }
        } catch (e: Exception) {
            // Handle error
        }
    }
}
```

### 5. Order Creation with Coupon

**Create Order Request:**

```kotlin
data class CreateOrderRequest(
    val delivery_address_id: Int,
    val payment_method: String,
    val delivery_date: String? = null,
    val delivery_time_slot: String? = null,
    val delivery_instructions: String? = null,
    val use_wallet: Boolean = false,
    val use_loyalty_points: Boolean = false,
    val coupon_code: String? = null  // Add coupon code here
)
```

**Order Creation:**

```kotlin
fun createOrder(request: CreateOrderRequest) {
    viewModelScope.launch {
        try {
            val response = apiService.createOrder(
                token = authManager.getAuthHeader(),
                request = request
            )
            
            if (response.isSuccessful && response.body()?.success == true) {
                val order = response.body()?.data
                // Navigate to order success screen
                navigateToOrderSuccess(order?.id)
            } else {
                _error.value = response.body()?.message ?: "Failed to create order"
            }
        } catch (e: Exception) {
            _error.value = e.message
        }
    }
}
```

---

## Data Models

Create these data classes based on your API responses:

```kotlin
// Base Response
data class ApiResponse<T>(
    val success: Boolean,
    val message: String? = null,
    val data: T? = null,
    val errors: Map<String, List<String>>? = null
)

// Product
data class Product(
    val id: Int,
    val name: String,
    val slug: String,
    val description: String?,
    val image: String?,
    val category: Category?,
    val brand: Brand?,
    val variants: List<ProductVariant>?,
    val is_featured: Boolean,
    val is_active: Boolean
)

// Cart
data class CartResponse(
    val items: List<CartItem>,
    val summary: CartSummary,
    val nearest_store: Store?
)

data class CartItem(
    val id: Int,
    val product_variant_id: Int,
    val quantity: Int,
    val unit_price: Double,
    val total_price: Double,
    val product_variant: ProductVariant?
)

// Coupon
data class Coupon(
    val id: Int,
    val code: String,
    val name: String,
    val discount_type: String,
    val discount_value: Double,
    val minimum_order_amount: Double,
    val valid_from: String,
    val valid_to: String
)

data class CouponValidationResponse(
    val coupon: Coupon,
    val discount_amount: Double,
    val order_amount: Double,
    val final_amount: Double
)

// Combo Offer
data class ComboOffer(
    val id: Int,
    val name: String,
    val slug: String,
    val description: String?,
    val image: String?,
    val original_price: Double,
    val discounted_price: Double,
    val discount_amount: Double,
    val discount_percentage: Double,
    val items: List<ComboOfferItem>?,
    val is_active: Boolean
)

// Order
data class Order(
    val id: Int,
    val order_number: String,
    val status: String,
    val payment_status: String,
    val subtotal: Double,
    val discount_amount: Double,
    val total_amount: Double,
    val items: List<OrderItem>?
)
```

---

## Error Handling

Create a centralized error handler:

```kotlin
object ErrorHandler {
    fun handleError(throwable: Throwable): String {
        return when (throwable) {
            is IOException -> "Network error. Please check your internet connection."
            is HttpException -> {
                when (throwable.code()) {
                    401 -> "Unauthorized. Please login again."
                    403 -> "Access denied."
                    404 -> "Resource not found."
                    500 -> "Server error. Please try again later."
                    else -> "An error occurred: ${throwable.message()}"
                }
            }
            else -> "An unexpected error occurred: ${throwable.message}"
        }
    }
}
```

---

## Best Practices

1. **Always Request Location First**: Before making product/cart API calls, ensure location permission is granted and location is available.

2. **Cache Location**: Store the last known location to avoid requesting location on every API call.

3. **Handle Token Expiry**: Implement token refresh logic or redirect to login when receiving 401 errors.

4. **Show Loading States**: Always show loading indicators during API calls.

5. **Validate Input**: Validate coupon codes, quantities, etc., before making API calls.

6. **Error Messages**: Display user-friendly error messages.

7. **Offline Handling**: Implement offline data caching where possible.

8. **Pagination**: Implement pagination for product lists.

---

## Complete Integration Checklist

- [ ] Add required dependencies
- [ ] Set up Retrofit and API service
- [ ] Implement authentication (login/register)
- [ ] Set up location services
- [ ] Implement product listing with location
- [ ] Implement cart functionality
- [ ] Integrate coupon validation and application
- [ ] Implement offers display
- [ ] Implement combo offers
- [ ] Add combo to cart functionality
- [ ] Implement order creation with coupon
- [ ] Add error handling
- [ ] Test all features
- [ ] Handle edge cases (no location, network errors, etc.)

---

## Testing

### Test Scenarios

1. **Location-Based Product Display**
   - Test with location permission granted
   - Test with location permission denied
   - Test with location unavailable

2. **Cart Operations**
   - Add single product
   - Add combo offer
   - Update quantity
   - Remove items
   - Clear cart

3. **Coupon Application**
   - Valid coupon
   - Invalid coupon
   - Expired coupon
   - Minimum order amount not met
   - Usage limit reached

4. **Order Creation**
   - With coupon
   - Without coupon
   - With wallet/loyalty points
   - Different payment methods

---

## Support

For API documentation and support, refer to:
- API Base URL: `http://your-api-domain.com/api/v1/`
- Postman Collection: `Organic_Fresh_API.postman_collection.json`

---

**Last Updated**: December 2025

