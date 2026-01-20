# Support Tickets API - Android Integration Guide

## Overview

This document provides comprehensive documentation for integrating Support Tickets functionality in the Android mobile application. The API allows customers to:

1. **Get Tickets List** - View all support tickets with pagination and replies
2. **Create New Ticket** - Submit a new support ticket

**Base URL:** `https://your-domain.com/api`

**Authentication:** Bearer Token (Sanctum)

**Important Notes:**
- All tickets include their public replies in the response
- Replies contain both customer and admin information (check `replied_by_type` to determine sender)
- Attachments are always returned as an array (empty array `[]` if no attachments)

---

## Table of Contents

1. [Authentication](#authentication)
2. [API Endpoints](#api-endpoints)
   - [Get Customer's Support Tickets](#1-get-customers-support-tickets)
   - [Create New Support Ticket](#2-create-new-support-ticket)
3. [Data Models](#data-models)
4. [Request/Response Examples](#requestresponse-examples)
5. [Error Handling](#error-handling)
6. [Android Implementation](#android-implementation)
7. [Code Examples](#code-examples)
8. [Best Practices](#best-practices)

## Quick Start

This documentation covers **2 main APIs**:

1. **GET `/api/support-tickets`** - Retrieve tickets list with pagination and replies
2. **POST `/api/support-tickets`** - Create a new support ticket

**Key Features:**
- ✅ Pagination support (default 15 items per page)
- ✅ Status filtering (open, in_progress, resolved, closed, cancelled)
- ✅ Replies included in ticket response
- ✅ Priority levels (low, medium, high, urgent)

---

## Authentication

All API endpoints require authentication using Bearer Token (Sanctum). Include the token in the `Authorization` header of every request.

```
Authorization: Bearer {your_access_token}
```

**How to get the token:**
- Customer must login first using the login API
- The login API returns an access token
- Store this token securely (SharedPreferences, EncryptedSharedPreferences, or Secure Storage)
- Include this token in all subsequent API requests

---

## API Endpoints

### 1. Get Customer's Support Tickets

Retrieve a list of support tickets for the authenticated customer. Each ticket includes all public replies in the response.

**Endpoint:** `GET /api/support-tickets`

**Authentication:** Required

**Note:** The response includes `public_replies` array for each ticket, containing all replies with both customer and admin information. Use `replied_by_type` field to determine who sent each reply.

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `status` | string | No | Filter tickets by status. Values: `open`, `in_progress`, `resolved`, `closed`, `cancelled` |
| `page` | integer | No | Page number for pagination (default: 1) |
| `per_page` | integer | No | Number of items per page (default: 15) |

**Response Status Codes:**
- `200 OK` - Success
- `401 Unauthorized` - Invalid or missing authentication token

**Success Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "ticket_number": "TKT-YQYGGQJT",
            "customer_id": 8,
            "subject": "Order delivery issue",
            "description": "My order has not been delivered yet. Order number: ORD-12345",
            "priority": "high",
            "status": "in_progress",
            "assigned_to": 2,
            "assigned_by": 1,
            "assigned_at": "2026-01-12T11:48:00.000000Z",
            "resolved_at": "2026-01-12T12:57:44.000000Z",
            "closed_at": "2026-01-12T12:57:26.000000Z",
            "resolution_notes": null,
            "created_at": "2026-01-12T11:46:44.000000Z",
            "updated_at": "2026-01-12T15:48:22.000000Z",
            "deleted_at": null,
            "assigned_user": {
                "id": 2,
                "name": "Lance Murray",
                "email": "powu@mailinator.com"
            },
            "public_replies": [
                {
                    "id": 1,
                    "ticket_id": 1,
                    "replied_by_type": "admin",
                    "replied_by_id": 1,
                    "message": "hello",
                    "attachments": [],
                    "is_internal": false,
                    "created_at": "2026-01-12T11:48:17.000000Z",
                    "updated_at": "2026-01-12T11:48:17.000000Z",
                    "customer": {
                        "id": 1,
                        "first_name": "Info kjhkjhjk",
                        "last_name": "RSTC"
                    },
                    "admin": {
                        "id": 1,
                        "name": "Super Admin",
                        "email": "superadmin@email.com"
                    }
                },
                {
                    "id": 2,
                    "ticket_id": 1,
                    "replied_by_type": "admin",
                    "replied_by_id": 1,
                    "message": "hello",
                    "attachments": [],
                    "is_internal": false,
                    "created_at": "2026-01-12T11:48:20.000000Z",
                    "updated_at": "2026-01-12T11:48:20.000000Z",
                    "customer": {
                        "id": 1,
                        "first_name": "Info kjhkjhjk",
                        "last_name": "RSTC"
                    },
                    "admin": {
                        "id": 1,
                        "name": "Super Admin",
                        "email": "superadmin@email.com"
                    }
                }
            ]
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```

---

### 2. Create New Support Ticket

Create a new support ticket for the authenticated customer.

**Endpoint:** `POST /api/support-tickets`

**Authentication:** Required

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `subject` | string | Yes | Ticket subject (max 255 characters) |
| `description` | string | Yes | Detailed description of the issue |
| `priority` | string | No | Ticket priority. Values: `low`, `medium`, `high`, `urgent` (default: `medium`) |

**Response Status Codes:**
- `201 Created` - Ticket created successfully
- `401 Unauthorized` - Invalid or missing authentication token
- `422 Unprocessable Entity` - Validation error

**Success Response:**

```json
{
    "success": true,
    "message": "Ticket created successfully",
    "data": {
        "id": 1,
        "ticket_number": "TKT-YQYGGQJT",
        "customer_id": 8,
        "subject": "Order delivery issue",
        "description": "My order has not been delivered yet. Order number: ORD-12345",
        "priority": "high",
        "status": "open",
        "assigned_to": null,
        "assigned_by": null,
        "assigned_at": null,
        "resolved_at": null,
        "closed_at": null,
        "resolution_notes": null,
        "created_at": "2026-01-12T11:46:44.000000Z",
        "updated_at": "2026-01-12T11:46:44.000000Z",
        "deleted_at": null,
        "customer": {
            "id": 8,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "phone": "+1234567890"
        },
        "assigned_user": null
    }
}
```

**Validation Error Response:**

```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "subject": [
            "The subject field is required."
        ],
        "description": [
            "The description field is required."
        ],
        "priority": [
            "The selected priority is invalid."
        ]
    }
}
```

---

## Data Models

### SupportTicket

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Ticket ID |
| `ticket_number` | string | Unique ticket number (format: TKT-XXXXXXXX) |
| `customer_id` | integer | ID of the customer who created the ticket |
| `subject` | string | Ticket subject |
| `description` | string | Detailed description |
| `priority` | string | Priority level: `low`, `medium`, `high`, `urgent` |
| `status` | string | Current status: `open`, `in_progress`, `resolved`, `closed`, `cancelled` |
| `assigned_to` | integer\|null | ID of admin user assigned to the ticket |
| `assigned_by` | integer\|null | ID of admin user who assigned the ticket |
| `assigned_at` | datetime\|null | When the ticket was assigned |
| `resolved_at` | datetime\|null | When the ticket was resolved |
| `closed_at` | datetime\|null | When the ticket was closed |
| `resolution_notes` | string\|null | Resolution notes from admin |
| `created_at` | datetime | Ticket creation timestamp |
| `updated_at` | datetime | Last update timestamp |
| `deleted_at` | datetime\|null | Soft delete timestamp (null if not deleted) |
| `assigned_user` | object\|null | Assigned admin user details (if assigned) |
| `public_replies` | array | List of public replies to the ticket (includes all replies with customer and admin info) |

### SupportTicketReply

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Reply ID |
| `ticket_id` | integer | ID of the ticket |
| `replied_by_type` | string | Type of replier: `customer` or `admin` |
| `replied_by_id` | integer | ID of the replier (customer or admin) |
| `message` | string | Reply message |
| `attachments` | array | Array of attachment file paths (empty array if no attachments) |
| `is_internal` | boolean | Whether the reply is internal (not visible to customer) |
| `created_at` | datetime | Reply creation timestamp |
| `updated_at` | datetime | Last update timestamp |
| `customer` | object | Customer details (always present, may contain customer info even if replied by admin) |
| `admin` | object | Admin details (always present, may contain admin info even if replied by customer) |

---

## Error Handling

### Common Error Responses

**401 Unauthorized:**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

**422 Validation Error:**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "field_name": [
            "Error message 1",
            "Error message 2"
        ]
    }
}
```

**500 Server Error:**
```json
{
    "success": false,
    "message": "Internal server error"
}
```

### Error Handling Best Practices

1. Always check the `success` field in the response
2. Handle network errors (timeout, no internet)
3. Show user-friendly error messages
4. Log errors for debugging
5. Implement retry logic for network failures

---

## Android Implementation

### 1. Dependencies

Add the following dependencies to your `build.gradle` (Module: app):

```gradle
dependencies {
    // Retrofit for API calls
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.11.0'
    
    // Coroutines for async operations
    implementation 'org.jetbrains.kotlinx:kotlinx-coroutines-android:1.7.3'
    implementation 'org.jetbrains.kotlinx:kotlinx-coroutines-core:1.7.3'
    
    // Lifecycle components
    implementation 'androidx.lifecycle:lifecycle-viewmodel-ktx:2.6.2'
    implementation 'androidx.lifecycle:lifecycle-livedata-ktx:2.6.2'
}
```

### 2. Network Configuration

**ApiClient.kt:**
```kotlin
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.concurrent.TimeUnit

object ApiClient {
    private const val BASE_URL = "https://your-domain.com/api/"
    private const val TIMEOUT = 30L
    
    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = if (BuildConfig.DEBUG) {
            HttpLoggingInterceptor.Level.BODY
        } else {
            HttpLoggingInterceptor.Level.NONE
        }
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
        .addInterceptor { chain ->
            val token = TokenManager.getToken() // Get token from your storage
            val request = if (token != null) {
                chain.request().newBuilder()
                    .addHeader("Authorization", "Bearer $token")
                    .build()
            } else {
                chain.request()
            }
            chain.proceed(request)
        }
        .connectTimeout(TIMEOUT, TimeUnit.SECONDS)
        .readTimeout(TIMEOUT, TimeUnit.SECONDS)
        .writeTimeout(TIMEOUT, TimeUnit.SECONDS)
        .build()
    
    val retrofit: Retrofit = Retrofit.Builder()
        .baseUrl(BASE_URL)
        .client(okHttpClient)
        .addConverterFactory(GsonConverterFactory.create())
        .build()
}
```

### 3. Data Models

**SupportTicket.kt:**
```kotlin
import com.google.gson.annotations.SerializedName
import java.util.Date

data class SupportTicket(
    @SerializedName("id") val id: Int,
    @SerializedName("ticket_number") val ticketNumber: String,
    @SerializedName("customer_id") val customerId: Int,
    @SerializedName("subject") val subject: String,
    @SerializedName("description") val description: String,
    @SerializedName("priority") val priority: String,
    @SerializedName("status") val status: String,
    @SerializedName("assigned_to") val assignedTo: Int?,
    @SerializedName("assigned_by") val assignedBy: Int?,
    @SerializedName("assigned_at") val assignedAt: String?,
    @SerializedName("resolved_at") val resolvedAt: String?,
    @SerializedName("closed_at") val closedAt: String?,
    @SerializedName("resolution_notes") val resolutionNotes: String?,
    @SerializedName("created_at") val createdAt: String,
    @SerializedName("updated_at") val updatedAt: String,
    @SerializedName("deleted_at") val deletedAt: String?,
    @SerializedName("assigned_user") val assignedUser: AssignedUser?,
    @SerializedName("public_replies") val publicReplies: List<SupportTicketReply>?
)

data class AssignedUser(
    @SerializedName("id") val id: Int,
    @SerializedName("name") val name: String,
    @SerializedName("email") val email: String
)

data class SupportTicketReply(
    @SerializedName("id") val id: Int,
    @SerializedName("ticket_id") val ticketId: Int,
    @SerializedName("replied_by_type") val repliedByType: String,
    @SerializedName("replied_by_id") val repliedById: Int,
    @SerializedName("message") val message: String,
    @SerializedName("attachments") val attachments: List<String>,
    @SerializedName("is_internal") val isInternal: Boolean,
    @SerializedName("created_at") val createdAt: String,
    @SerializedName("updated_at") val updatedAt: String,
    @SerializedName("customer") val customer: CustomerInfo,
    @SerializedName("admin") val admin: AdminInfo
)

data class CustomerInfo(
    @SerializedName("id") val id: Int,
    @SerializedName("first_name") val firstName: String,
    @SerializedName("last_name") val lastName: String
) {
    val fullName: String
        get() = "$firstName $lastName".trim()
}

data class AdminInfo(
    @SerializedName("id") val id: Int,
    @SerializedName("name") val name: String,
    @SerializedName("email") val email: String
)
```

**CreateTicketRequest.kt:**
```kotlin
data class CreateTicketRequest(
    val subject: String,
    val description: String,
    val priority: String? = "medium"
)
```

**ApiResponse.kt:**
```kotlin
data class ApiResponse<T>(
    @SerializedName("success") val success: Boolean,
    @SerializedName("message") val message: String?,
    @SerializedName("data") val data: T?,
    @SerializedName("errors") val errors: Map<String, List<String>>?,
    @SerializedName("pagination") val pagination: Pagination?
)

data class Pagination(
    @SerializedName("current_page") val currentPage: Int,
    @SerializedName("last_page") val lastPage: Int,
    @SerializedName("per_page") val perPage: Int,
    @SerializedName("total") val total: Int
)
```

### 4. API Interface

**SupportTicketApi.kt:**
```kotlin
import retrofit2.Response
import retrofit2.http.*

interface SupportTicketApi {
    
    @GET("support-tickets")
    suspend fun getTickets(
        @Query("status") status: String? = null,
        @Query("page") page: Int = 1,
        @Query("per_page") perPage: Int = 15
    ): Response<ApiResponse<List<SupportTicket>>>
    
    @POST("support-tickets")
    suspend fun createTicket(
        @Body request: CreateTicketRequest
    ): Response<ApiResponse<SupportTicket>>
}
```

### 5. Repository

**SupportTicketRepository.kt:**
```kotlin
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext

class SupportTicketRepository {
    private val api = ApiClient.retrofit.create(SupportTicketApi::class.java)
    
    suspend fun getTickets(
        status: String? = null,
        page: Int = 1,
        perPage: Int = 15
    ): Result<Pair<List<SupportTicket>, Pagination?>> = withContext(Dispatchers.IO) {
        try {
            val response = api.getTickets(status, page, perPage)
            if (response.isSuccessful && response.body()?.success == true) {
                val data = response.body()?.data ?: emptyList()
                val pagination = response.body()?.pagination
                Result.success(Pair(data, pagination))
            } else {
                val errorMessage = response.body()?.message ?: "Unknown error"
                Result.failure(Exception(errorMessage))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }
    
    suspend fun createTicket(
        subject: String,
        description: String,
        priority: String = "medium"
    ): Result<SupportTicket> = withContext(Dispatchers.IO) {
        try {
            val request = CreateTicketRequest(subject, description, priority)
            val response = api.createTicket(request)
            if (response.isSuccessful && response.body()?.success == true) {
                val ticket = response.body()?.data
                if (ticket != null) {
                    Result.success(ticket)
                } else {
                    Result.failure(Exception("Ticket data is null"))
                }
            } else {
                val errorMessage = response.body()?.message ?: "Unknown error"
                val errors = response.body()?.errors
                Result.failure(Exception("$errorMessage ${errors?.toString() ?: ""}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }
}
```

### 6. ViewModel

**SupportTicketViewModel.kt:**
```kotlin
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import kotlinx.coroutines.launch

class SupportTicketViewModel : ViewModel() {
    private val repository = SupportTicketRepository()
    
    private val _tickets = MutableLiveData<List<SupportTicket>>()
    val tickets: LiveData<List<SupportTicket>> = _tickets
    
    private val _pagination = MutableLiveData<Pagination?>()
    val pagination: LiveData<Pagination?> = _pagination
    
    private val _isLoading = MutableLiveData<Boolean>()
    val isLoading: LiveData<Boolean> = _isLoading
    
    private val _error = MutableLiveData<String?>()
    val error: LiveData<String?> = _error
    
    private val _ticketCreated = MutableLiveData<SupportTicket?>()
    val ticketCreated: LiveData<SupportTicket?> = _ticketCreated
    
    fun loadTickets(status: String? = null, page: Int = 1) {
        viewModelScope.launch {
            _isLoading.value = true
            _error.value = null
            
            repository.getTickets(status, page).fold(
                onSuccess = { (ticketsList, pagination) ->
                    _tickets.value = ticketsList
                    _pagination.value = pagination
                    _isLoading.value = false
                },
                onFailure = { exception ->
                    _error.value = exception.message
                    _isLoading.value = false
                }
            )
        }
    }
    
    fun createTicket(subject: String, description: String, priority: String = "medium") {
        viewModelScope.launch {
            _isLoading.value = true
            _error.value = null
            
            repository.createTicket(subject, description, priority).fold(
                onSuccess = { ticket ->
                    _ticketCreated.value = ticket
                    _isLoading.value = false
                    // Reload tickets list
                    loadTickets()
                },
                onFailure = { exception ->
                    _error.value = exception.message
                    _isLoading.value = false
                }
            )
        }
    }
}
```

### 7. Activity/Fragment Example

**SupportTicketsActivity.kt:**
```kotlin
import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.activity.viewModels
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.Observer
import androidx.recyclerview.widget.LinearLayoutManager
import kotlinx.android.synthetic.main.activity_support_tickets.*

class SupportTicketsActivity : AppCompatActivity() {
    private val viewModel: SupportTicketViewModel by viewModels()
    private lateinit var adapter: SupportTicketAdapter
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_support_tickets)
        
        setupRecyclerView()
        setupObservers()
        setupClickListeners()
        
        // Load tickets
        viewModel.loadTickets()
    }
    
    private fun setupRecyclerView() {
        adapter = SupportTicketAdapter(
            onTicketClick = { ticket ->
                // Handle ticket click - navigate to detail
                // Intent(this, TicketDetailActivity::class.java).apply {
                //     putExtra("ticket_id", ticket.id)
                //     startActivity(this)
                // }
            },
            onReplyClick = { reply ->
                // Handle reply click if needed
            }
        )
        recyclerViewTickets.layoutManager = LinearLayoutManager(this)
        recyclerViewTickets.adapter = adapter
    }
    
    private fun setupObservers() {
        viewModel.tickets.observe(this, Observer { tickets ->
            adapter.submitList(tickets)
            if (tickets.isEmpty()) {
                textViewEmpty.visibility = View.VISIBLE
                recyclerViewTickets.visibility = View.GONE
            } else {
                textViewEmpty.visibility = View.GONE
                recyclerViewTickets.visibility = View.VISIBLE
            }
        })
        
        viewModel.isLoading.observe(this, Observer { isLoading ->
            progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        })
        
        viewModel.error.observe(this, Observer { error ->
            error?.let {
                Toast.makeText(this, it, Toast.LENGTH_LONG).show()
            }
        })
        
        viewModel.ticketCreated.observe(this, Observer { ticket ->
            ticket?.let {
                Toast.makeText(this, "Ticket created successfully: ${it.ticketNumber}", Toast.LENGTH_LONG).show()
                // Navigate to ticket detail or refresh list
            }
        })
    }
    
    private fun setupClickListeners() {
        buttonCreateTicket.setOnClickListener {
            // Open create ticket dialog or activity
            // showCreateTicketDialog()
        }
        
        // Filter buttons
        buttonFilterAll.setOnClickListener {
            viewModel.loadTickets(null)
        }
        buttonFilterOpen.setOnClickListener {
            viewModel.loadTickets("open")
        }
        buttonFilterResolved.setOnClickListener {
            viewModel.loadTickets("resolved")
        }
    }
}
```

**CreateTicketDialog.kt:**
```kotlin
import android.app.Dialog
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.DialogFragment
import androidx.lifecycle.ViewModelProvider
import kotlinx.android.synthetic.main.dialog_create_ticket.*

class CreateTicketDialog : DialogFragment() {
    private lateinit var viewModel: SupportTicketViewModel
    
    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        return inflater.inflate(R.layout.dialog_create_ticket, container, false)
    }
    
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        
        viewModel = ViewModelProvider(requireActivity())[SupportTicketViewModel::class.java]
        
        buttonSubmit.setOnClickListener {
            val subject = editTextSubject.text.toString().trim()
            val description = editTextDescription.text.toString().trim()
            val priority = spinnerPriority.selectedItem.toString().lowercase()
            
            if (validateInput(subject, description)) {
                viewModel.createTicket(subject, description, priority)
                dismiss()
            }
        }
        
        buttonCancel.setOnClickListener {
            dismiss()
        }
        
        // Observe ticket creation
        viewModel.ticketCreated.observe(viewLifecycleOwner) { ticket ->
            ticket?.let {
                Toast.makeText(context, "Ticket created: ${it.ticketNumber}", Toast.LENGTH_LONG).show()
            }
        }
    }
    
    private fun validateInput(subject: String, description: String): Boolean {
        if (subject.isEmpty()) {
            editTextSubject.error = "Subject is required"
            return false
        }
        if (description.isEmpty()) {
            editTextDescription.error = "Description is required"
            return false
        }
        return true
    }
}
```

---

## Code Examples

### Example 1: Get All Tickets

```kotlin
viewModel.loadTickets()
```

### Example 2: Get Tickets by Status

```kotlin
// Get only open tickets
viewModel.loadTickets(status = "open")

// Get resolved tickets
viewModel.loadTickets(status = "resolved")
```

### Example 3: Create Ticket

```kotlin
viewModel.createTicket(
    subject = "Order delivery issue",
    description = "My order #12345 has not been delivered yet. It was supposed to arrive yesterday.",
    priority = "high"
)
```

### Example 4: Pagination

```kotlin
// Load page 2
viewModel.loadTickets(page = 2)

// Load with custom page size
viewModel.loadTickets(page = 1, perPage = 20)
```

### Example 5: Displaying Replies

```kotlin
// In your RecyclerView adapter or UI
ticket.publicReplies?.forEach { reply ->
    val senderName = when (reply.repliedByType) {
        "admin" -> reply.admin.name
        "customer" -> reply.customer.fullName
        else -> "Unknown"
    }
    
    val senderEmail = when (reply.repliedByType) {
        "admin" -> reply.admin.email
        "customer" -> null // Customer email not in response
        else -> null
    }
    
    // Display reply message, sender, and timestamp
    // reply.message
    // senderName
    // reply.createdAt
}
```

### Example 6: Handling Attachments

```kotlin
// Attachments are always an array (empty if none)
ticket.publicReplies?.forEach { reply ->
    if (reply.hasAttachments()) {
        reply.attachments.forEach { attachmentUrl ->
            // Display or download attachment
            // attachmentUrl contains the full file path/URL
            // Format: "path/to/file.pdf" or full URL
        }
    }
}
```

### Example 7: Displaying Ticket with Replies in RecyclerView

```kotlin
// In your RecyclerView Adapter
class SupportTicketAdapter(
    private val onTicketClick: (SupportTicket) -> Unit,
    private val onReplyClick: (SupportTicketReply) -> Unit
) : RecyclerView.Adapter<RecyclerView.ViewHolder>() {
    
    override fun onBindViewHolder(holder: RecyclerView.ViewHolder, position: Int) {
        val ticket = tickets[position]
        
        when (holder) {
            is TicketViewHolder -> {
                holder.bind(ticket)
                holder.itemView.setOnClickListener { onTicketClick(ticket) }
            }
            is ReplyViewHolder -> {
                val reply = ticket.publicReplies?.get(position - 1) // Adjust index
                reply?.let { holder.bind(it) }
            }
        }
    }
    
    // Get total item count including replies
    override fun getItemCount(): Int {
        return tickets.sumOf { 1 + (it.publicReplies?.size ?: 0) }
    }
}

// In TicketViewHolder
fun bind(ticket: SupportTicket) {
    textViewTicketNumber.text = ticket.ticketNumber
    textViewSubject.text = ticket.subject
    textViewStatus.text = ticket.status.replace("_", " ").capitalize()
    textViewPriority.text = ticket.priority.capitalize()
    
    // Show reply count
    val replyCount = ticket.publicReplies?.size ?: 0
    textViewReplyCount.text = "$replyCount ${if (replyCount == 1) "reply" else "replies"}"
    
    // Show last reply if exists
    ticket.publicReplies?.lastOrNull()?.let { lastReply ->
        textViewLastReply.text = "Last reply: ${lastReply.getSenderName()}"
        textViewLastReplyTime.text = formatDate(lastReply.createdAt)
    }
}

// In ReplyViewHolder
fun bind(reply: SupportTicketReply) {
    textViewMessage.text = reply.message
    textViewSender.text = reply.getSenderName()
    textViewSenderType.text = when (reply.repliedByType) {
        "admin" -> "Support Team"
        "customer" -> "You"
        else -> "Unknown"
    }
    textViewTime.text = formatDate(reply.createdAt)
    
    // Show attachments if any
    if (reply.hasAttachments()) {
        recyclerViewAttachments.visibility = View.VISIBLE
        // Display attachments
    } else {
        recyclerViewAttachments.visibility = View.GONE
    }
}
```

---

## Best Practices

### 1. Token Management
- Store tokens securely using `EncryptedSharedPreferences` or Android Keystore
- Implement token refresh logic
- Clear tokens on logout

### 2. Error Handling
- Show user-friendly error messages
- Implement retry logic for network failures
- Log errors for debugging

### 3. Loading States
- Show loading indicators during API calls
- Disable UI interactions while loading
- Handle empty states gracefully

### 4. Caching
- Cache ticket list locally
- Refresh cache on pull-to-refresh
- Show cached data while fetching new data

### 5. Network Optimization
- Implement pagination for large lists
- Use appropriate page sizes (15-20 items)
- Load more data on scroll (infinite scroll)

### 6. User Experience
- Show ticket status with color coding
- Display priority badges
- Show last reply timestamp
- Implement pull-to-refresh
- Display replies in chronological order (oldest first)
- Show sender name and type (Admin/Customer) for each reply
- Handle attachments display/download

### 7. Security
- Never log sensitive data
- Validate input on client side
- Use HTTPS only
- Implement certificate pinning in production

---

## Status and Priority Values

### Ticket Status
- `open` - New ticket, not yet assigned
- `in_progress` - Ticket is being worked on
- `resolved` - Issue has been resolved
- `closed` - Ticket is closed
- `cancelled` - Ticket was cancelled

### Priority Levels
- `low` - Low priority issue
- `medium` - Normal priority (default)
- `high` - High priority issue
- `urgent` - Urgent issue requiring immediate attention

---

## Testing

### Test Cases

1. **Get Tickets**
   - Test with valid token
   - Test with invalid token (should return 401)
   - Test with status filter
   - Test pagination

2. **Create Ticket**
   - Test with valid data
   - Test with missing required fields
   - Test with invalid priority value
   - Test with empty subject/description

3. **Error Scenarios**
   - Test with no internet connection
   - Test with server timeout
   - Test with invalid response format

---

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Check if token is being sent in headers
   - Verify token is valid and not expired
   - Ensure token format: `Bearer {token}`

2. **422 Validation Error**
   - Check all required fields are provided
   - Verify field formats (subject max 255 chars)
   - Ensure priority value is valid

3. **Network Errors**
   - Check internet connectivity
   - Verify base URL is correct
   - Check SSL certificate validity

4. **Empty Response**
   - Verify customer has tickets
   - Check status filter is correct
   - Verify pagination parameters

---

## Support

For API support or issues, contact:
- Email: support@your-domain.com
- Documentation: https://your-domain.com/api-docs

---

## Important Implementation Notes

### Response Structure (Based on Actual API Response)

#### Get Tickets Response
- **Structure:** `{ success: true, data: [tickets], pagination: {...} }`
- Each ticket object includes:
  - All ticket fields (id, ticket_number, subject, description, etc.)
  - `assigned_user` object (null if not assigned)
  - `public_replies` array with all replies
  - `deleted_at` field (null if not deleted)

#### Replies Structure
- **Always included:** Each ticket has `public_replies` array in the response
- **Both objects present:** Each reply contains both `customer` and `admin` objects
- **Determine sender:** Use `replied_by_type` field:
  - `"admin"` → Reply sent by admin/support team (use `admin` object)
  - `"customer"` → Reply sent by customer (use `customer` object)
- **Order:** Replies are ordered chronologically (oldest first)
- **Attachments:** Always an array `[]` - empty array if no attachments
- **Example:**
  ```json
  {
    "replied_by_type": "admin",
    "customer": { "id": 1, "first_name": "...", "last_name": "..." },
    "admin": { "id": 1, "name": "Super Admin", "email": "..." }
  }
  ```
  In this case, use `admin.name` as sender since `replied_by_type` is "admin"

### Pagination
- **Default:** 15 tickets per page
- **Pagination object contains:**
  - `current_page` - Current page number
  - `last_page` - Total number of pages
  - `per_page` - Items per page
  - `total` - Total number of tickets
- **Implementation:** Use `current_page < last_page` to show "Load More" button

### Ticket Status Flow
- **Status progression:** `open` → `in_progress` → `resolved` → `closed`
- **Alternative:** Tickets can be `cancelled` at any stage
- **Timestamps:** Check `resolved_at` and `closed_at` for status history
- **Assigned info:** `assigned_to` and `assigned_by` fields track assignment

### Field Notes
- `assigned_by` - ID of admin who assigned the ticket (may be null)
- `deleted_at` - Soft delete timestamp (null if active)
- `attachments` - Always an array, never null (empty `[]` if no attachments)

---

## Version History

- **v1.0** (2026-01-12) - Initial release
  - Get tickets list with replies included
  - Create new ticket
  - Status filtering
  - Pagination support
  - Replies with customer and admin information

---

## License

This API documentation is proprietary and confidential.
