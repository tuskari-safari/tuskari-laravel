# Enquiry-Based Booking Mode - Implementation Summary

## Overview

This document describes the implementation of the enquiry-based booking mode feature for the Tuskari safari booking platform. This feature allows safari operators to configure their safaris to require traveler enquiries before booking, enabling price negotiation and trip customization through the messaging system.

**Branch**: `enquiry-based-booking-mode`
**Commits**: 4 commits from `925fee5` to `c7cebb1`

---

## Feature Concept

Each Safari can be configured in one of two modes:

| Mode | Button Text | Behavior |
|------|-------------|----------|
| `booking` (default) | "Book Now" | Instant booking with calculated prices |
| `enquiry` | "Plan with Operator" | Creates draft booking + chat thread for negotiation |

### Enquiry Status Flow

```
pending → quoted → confirmed → (checkout & payment)
    ↓         ↓
declined  declined
```

---

## Database Schema Changes

### Migration 1: `add_booking_mode_to_safaris_table`

**File**: `database/migrations/2026_01_17_040230_add_booking_mode_to_safaris_table.php`

```php
Schema::table('safaris', function (Blueprint $table) {
    $table->enum('booking_mode', ['booking', 'enquiry'])->default('booking')->after('status');
});
```

### Migration 2: `add_enquiry_fields_to_safari_bookings_table`

**File**: `database/migrations/2026_01_17_040230_add_enquiry_fields_to_safari_bookings_table.php`

```php
Schema::table('safari_bookings', function (Blueprint $table) {
    $table->boolean('is_enquiry')->default(false)->after('safari_id');
    $table->foreignId('chat_room_id')->nullable()->after('is_enquiry')
        ->constrained('chat_rooms')->nullOnDelete();
    $table->enum('enquiry_status', ['pending', 'quoted', 'confirmed', 'declined'])
        ->nullable()->after('chat_room_id');
    $table->decimal('quoted_total_price', 10, 2)->nullable()->after('enquiry_status');
    $table->timestamp('quoted_at')->nullable()->after('quoted_total_price');
    $table->text('traveler_notes')->nullable()->after('quoted_at');
});
```

---

## Backend Implementation

### Model Updates

#### Safari Model (`app/Models/Safari.php`)

**Changes**:
- Added `booking_mode` to `$fillable` array
- Added helper method:

```php
public function isEnquiryMode(): bool
{
    return $this->booking_mode === 'enquiry';
}
```

#### SafariBooking Model (`app/Models/SafariBooking.php`)

**New Fillable Fields**:
- `is_enquiry`, `chat_room_id`, `enquiry_status`, `quoted_total_price`, `quoted_at`, `traveler_notes`

**New Casts**:
```php
protected $casts = [
    'is_enquiry' => 'boolean',
    'quoted_at' => 'datetime',
    'quoted_total_price' => 'decimal:2',
];
```

**New Relationship**:
```php
public function chatRoom(): BelongsTo
{
    return $this->belongsTo(ChatRoom::class, 'chat_room_id');
}
```

**New Scopes**:
- `scopeEnquiries($query)` - Filter to enquiries only
- `scopeRegularBookings($query)` - Filter to regular bookings
- `scopePendingEnquiries($query)` - Filter to pending enquiries
- `scopeQuotedEnquiries($query)` - Filter to quoted enquiries

---

### Controller Methods

#### BookingController (`app/Http/Controllers/Frontend/BookingController.php`)

##### 1. `createEnquiry()` - POST `/create-enquiry`

**Purpose**: Traveler submits an enquiry for an enquiry-mode safari

**Flow**:
1. Validates booking parameters + `traveler_notes`
2. Verifies safari is in enquiry mode
3. Creates or finds existing ChatRoom between traveler and operator
4. Creates SafariBooking with `is_enquiry=true`, `enquiry_status='pending'`
5. Creates auto-generated message with enquiry details
6. Sends notification to operator
7. Returns redirect to messages page

**Request Parameters**:
```json
{
    "safari_id": "required|exists:safaris,id",
    "check_in_date": "required|date",
    "check_out_date": "required|date|after:check_in_date",
    "number_of_adults": "required|integer|min:1",
    "number_of_children": "nullable|integer|min:0",
    "duration": "required|string",
    "traveler_notes": "nullable|string|max:2000"
}
```

##### 2. `quoteEnquiry()` - POST `/quote-enquiry`

**Purpose**: Operator submits a price quote for a pending enquiry

**Authorization**: Operator only (must own the safari)

**Flow**:
1. Validates enquiry_id and quoted_price
2. Updates booking with quoted price and status
3. Creates system message in chat with quote details
4. Sends notification to traveler

**Request Parameters**:
```json
{
    "enquiry_id": "required|exists:safari_bookings,id",
    "quoted_total_price": "required|numeric|min:1"
}
```

**Note**: The quoted price includes the 13% platform fee (total amount traveler pays)

##### 3. `confirmEnquiry()` - POST `/confirm-enquiry`

**Purpose**: Traveler accepts the quote and proceeds to checkout

**Authorization**: Traveler only (must own the enquiry)

**Flow**:
1. Validates enquiry has status 'quoted'
2. Updates enquiry status to 'confirmed'
3. Creates system message in chat
4. Stores booking data in session (for checkout)
5. Sends notification to operator
6. Returns redirect to checkout page

##### 4. `declineEnquiry()` - POST `/decline-enquiry`

**Purpose**: Either party declines the enquiry

**Authorization**: Traveler or Operator

**Flow**:
1. Updates enquiry status to 'declined' and booking status to 'CANCELLED'
2. Creates system message in chat with optional reason
3. Sends notification to the other party

**Request Parameters**:
```json
{
    "enquiry_id": "required|exists:safari_bookings,id",
    "reason": "nullable|string|max:500"
}
```

##### 5. `getEnquiry()` - GET `/get-enquiry`

**Purpose**: Fetch active enquiry for a chat room (used by chat UI)

**Returns**:
```json
{
    "enquiry": { /* SafariBooking with safari, traveler, operator */ },
    "is_operator": true/false
}
```

##### 6. `safariPayment()` - Updated

**Changes**:
- Added `is_enquiry_booking` and `enquiry_id` to validation
- For enquiry bookings: updates existing booking instead of creating new
- Calculates platform fee from quoted price for enquiry bookings
- Skips duplicate booking check for enquiry conversions

---

### Routes

**File**: `routes/frontend.php`

#### Traveler Routes (authenticated)
```php
Route::post('create-enquiry', [BookingController::class, 'createEnquiry'])->name('create-enquiry');
Route::post('confirm-enquiry', [BookingController::class, 'confirmEnquiry'])->name('confirm-enquiry');
```

#### Operator Routes (authenticated)
```php
Route::post('quote-enquiry', [BookingController::class, 'quoteEnquiry'])->name('quote-enquiry');
```

#### Both Traveler & Operator Routes
```php
Route::post('decline-enquiry', [BookingController::class, 'declineEnquiry'])->name('decline-enquiry');
Route::get('get-enquiry', [BookingController::class, 'getEnquiry'])->name('get-enquiry');
```

---

## Frontend Implementation

### 1. Safari Details Page

**File**: `resources/js/Pages/Frontend/safari-details.vue`

#### Changes:
- Added `isEnquiryMode` computed property
- Added `travelerNotes` ref and `isSubmittingEnquiry` state
- Added `handleEnquiry()` function for enquiry submission
- Added `handleFormSubmit()` to route between booking/enquiry

#### UI Changes:
- Form header: "Book Your Safari" → "Plan with Operator" (enquiry mode)
- Description text changes based on mode
- Added traveler notes textarea (enquiry mode only)
- Button: "Book now" → "Plan with Operator" (enquiry mode)

### 2. Operator Safari Create/Edit Page

**File**: `resources/js/Pages/Frontend/Auth/create-new-listing.vue`

#### Changes:
- Added `booking_mode` to form with default `'booking'`
- Added radio button group for booking mode selection:
  - **Instant Booking** - Travelers can book and pay immediately
  - **Enquiry First** - Travelers must discuss with you before booking

**File**: `app/Http/Controllers/Frontend/SafariOperator/ListingController.php`
- Added `booking_mode` to both create and update methods

### 3. Chat / Messages Page

**File**: `resources/js/components/Frontend/SendMessage.vue`

#### New State Variables:
```javascript
const enquiry = ref(null);
const isOperator = ref(false);
const quoteAmount = ref('');
const isSubmittingQuote = ref(false);
const isProcessing = ref(false);
const showDeclineModal = ref(false);
const declineReason = ref('');
const isDeclining = ref(false);
```

#### New Methods:
- `fetchEnquiry()` - Loads enquiry data for the chat room
- `formatDate()`, `formatPrice()`, `capitalizeFirst()` - Helper functions
- `submitQuote()` - Operator submits quote
- `confirmEnquiry()` - Traveler confirms quote
- `declineEnquiry()` - Either party declines

#### UI: Enquiry Context Panel

Displayed above messages when an active enquiry exists:

```
┌─────────────────────────────────────────────────────┐
│ Safari Name                                         │
│ Jan 15, 2026 - Jan 20, 2026 | 2 adult(s)           │
│                                        [Pending]    │
├─────────────────────────────────────────────────────┤
│ Operator View (pending):                            │
│ Total Price (includes 13% platform fee)             │
│ $ [___________] [Send Quote]                        │
├─────────────────────────────────────────────────────┤
│ Traveler View (quoted):                             │
│ Quoted Price: $2,500.00                             │
│ [Confirm & Pay]  [Decline]                          │
└─────────────────────────────────────────────────────┘
```

#### Status Badge Colors:
- **Pending**: Yellow (`#fff3cd`)
- **Quoted**: Blue (`#cce5ff`)
- **Confirmed**: Green (`#d4edda`)
- **Declined**: Red (`#f8d7da`)

### 4. My Trips Page

**File**: `resources/js/Pages/Frontend/Auth/my-trips.vue`

#### Changes:
- Added `booking_type` filter (All / Confirmed Bookings / Enquiries)
- Updated table status column to show enquiry status badges
- Added action icons for enquiries:
  - Chat icon (pending) - Navigate to messages
  - Dollar icon (quoted) - Navigate to messages to view quote
- Added helper functions: `capitalizeFirst()`, `goToChat()`, `handleEnquiryAction()`

**File**: `app/Http/Controllers/Frontend/UserController.php`
- Added `booking_type` filter to `myTrips()` method

### 5. Checkout Page

**File**: `resources/js/Pages/Frontend/Auth/check-out.vue`

#### Changes:
- Added "Enquiry Booking" badge indicator for enquiry bookings
- Hidden group discount badge for enquiry bookings (price is pre-negotiated)
- Added CSS styles for enquiry booking indicator

---

## Notifications

All enquiry state changes trigger notifications using the existing `SendNotification` system:

| Event | Recipient | Notification Title |
|-------|-----------|-------------------|
| New Enquiry | Operator | "[Name] sent an enquiry for [Safari]" |
| Quote Sent | Traveler | "You received a quote for [Safari]" |
| Enquiry Confirmed | Operator | "[Name] confirmed your quote for [Safari]" |
| Enquiry Declined | Other Party | "Enquiry for [Safari] has been declined" |

---

## Testing Checklist

### Manual Testing Flow

1. **Operator Setup**:
   - Login as operator (`operator@yopmail.com`)
   - Create or edit a safari
   - Set booking mode to "Enquiry First"
   - Save the safari

2. **Traveler Enquiry**:
   - Login as traveler
   - Browse to the enquiry-mode safari
   - Should see "Plan with Operator" button
   - Fill in dates, guests, optional message
   - Submit enquiry
   - Should redirect to messages page

3. **Operator Quote**:
   - Login as operator
   - Check notifications
   - Go to messages
   - Should see enquiry context panel
   - Enter quote price and submit
   - Verify system message appears

4. **Traveler Confirmation**:
   - Login as traveler
   - Check notifications
   - Go to messages
   - Should see quoted price with Confirm/Decline buttons
   - Click "Confirm & Pay"
   - Should redirect to checkout with "Enquiry Booking" badge
   - Complete payment

5. **Decline Flow**:
   - Test decline from both traveler and operator sides
   - Verify status updates and notifications

### Edge Cases to Test

- Multiple enquiries for same safari by same traveler
- Decline after quote sent
- Safari mode change with pending enquiries
- Chat with multiple enquiries (only latest active shown)

---

## Files Changed Summary

### New Files
- `database/migrations/2026_01_17_040230_add_booking_mode_to_safaris_table.php`
- `database/migrations/2026_01_17_040230_add_enquiry_fields_to_safari_bookings_table.php`

### Modified Files

| File | Changes |
|------|---------|
| `app/Models/Safari.php` | Added `booking_mode` field, `isEnquiryMode()` method |
| `app/Models/SafariBooking.php` | Added enquiry fields, casts, relationships, scopes |
| `app/Http/Controllers/Frontend/BookingController.php` | Added 5 enquiry methods, updated `safariPayment()` |
| `app/Http/Controllers/Frontend/UserController.php` | Added booking_type filter |
| `app/Http/Controllers/Frontend/SafariOperator/ListingController.php` | Added booking_mode to create/update |
| `routes/frontend.php` | Added enquiry routes |
| `resources/js/Pages/Frontend/safari-details.vue` | Enquiry mode UI |
| `resources/js/Pages/Frontend/Auth/create-new-listing.vue` | Booking mode toggle |
| `resources/js/Pages/Frontend/Auth/my-trips.vue` | Enquiry filter and display |
| `resources/js/Pages/Frontend/Auth/check-out.vue` | Enquiry booking indicator |
| `resources/js/components/Frontend/SendMessage.vue` | Enquiry context panel |

---

## Commit History

```
c7cebb1 Integrate enquiry bookings with checkout flow
5babb23 Add enquiry context panel to chat and enquiry filter to my-trips
f99ebdb Add enquiry mode to safari details and operator listing pages
925fee5 Add enquiry-based booking mode - Backend implementation
```

---

## Future Enhancements (Out of Scope for MVP)

- Automatic enquiry expiration after X days
- Counter-offer functionality (traveler can request different price)
- Enquiry templates for operators
- Bulk enquiry management for operators
- Enquiry analytics/reporting
