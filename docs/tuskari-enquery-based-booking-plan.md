# Enquiry-Based Booking Mode Implementation Plan

## Overview
Add an enquiry-based booking mode that creates a draft booking linked to a chat thread, allowing operators and travelers to negotiate before converting to a confirmed booking with payment.

**Key Concept**: Each Safari can be configured as either "booking" mode (instant booking) or "enquiry" mode (plan with operator first). This is controlled at the Safari level.

## Deliverables Mapping

| Client Deliverable | Implementation |
|--------------------|----------------|
| 1. Safari booking mode toggle | `booking_mode` field on Safari model ('booking' or 'enquiry') |
| 2. Enquiry submission flow (form → draft booking with is_enquiry flag) | `createEnquiry()` → SafariBooking with is_enquiry=true |
| 3. Auto-created message thread linking traveler ↔ operator | ChatRoom created/linked via chat_room_id field |
| 4. Operator pricing attachment (within messaging UI) | Quote panel in SendMessage.vue context |
| 5. Conversion trigger: confirm → checkout/deposit flow | `confirmEnquiry()` → routes to existing checkout |
| 6. Basic enquiry status tracking (pending/quoted/confirmed) | enquiry_status enum field + UI badges |

## MVP Scope Decisions
- **No automatic expiration** - Enquiries stay open until manually declined
- **Price-only quotes** - Operator provides final price (net + 13% platform fee) for traveler's original request
- **Messages-based management** - Operators manage enquiries within existing chat UI
- **Structured enquiry form** - Dates, guests, and initial message required (enables pricing context)
- **Safari-level mode control** - Operators toggle each safari between booking/enquiry mode

## Current State Analysis

### Existing Assets to Reuse
1. **Safari model** - Add booking_mode toggle
2. **SafariBooking model** - Already has status workflow (PENDING → ACTIVE → COMPLETED)
3. **Messaging system** - ChatRoom/Message models with real-time support
4. **Checkout flow** - Stripe integration with deposits and platform fees
5. **"Message Operator" pattern** - autoGenerateMessage already exists on safari details page

### Key Files to Modify
| File | Changes |
|------|---------|
| `app/Models/Safari.php` | Add booking_mode field |
| `app/Models/SafariBooking.php` | Add enquiry fields, relationship to ChatRoom |
| `app/Http/Controllers/Frontend/BookingController.php` | Add enquiry CRUD methods |
| `resources/js/Pages/Frontend/safari-details.vue` | Dynamic button based on booking_mode |
| `resources/js/components/Frontend/SendMessage.vue` | Add enquiry context panel |
| `resources/js/Pages/Frontend/Auth/my-trips.vue` | Show enquiries with status |
| `routes/frontend.php` | Add enquiry routes |

---

## Implementation Plan

### Phase 1: Database Schema Changes

**Migration A: Add booking_mode to safaris**
```
- booking_mode (enum: 'booking', 'enquiry', default 'booking')
```

**Migration B: Add enquiry fields to safari_bookings**
```
- is_enquiry (boolean, default false)
- chat_room_id (foreign key to chat_rooms, nullable)
- enquiry_status (enum: 'pending', 'quoted', 'confirmed', 'declined', nullable)
- quoted_total_price (decimal 10,2, nullable) - Operator's final quote (includes 13% platform fee)
- quoted_at (timestamp, nullable) - When operator provided quote
- traveler_notes (text, nullable) - Traveler's initial enquiry message
```

Status flow: `pending` → `quoted` → `confirmed` (or `declined` at any point)

### Phase 2: Backend Implementation

**A. Safari Model Updates**
- Add `booking_mode` field with enum cast
- Add helper method: `isEnquiryMode()` returns true if booking_mode === 'enquiry'

**B. BookingController - New Enquiry Methods**

1. `createEnquiry()` - POST /create-enquiry
   - Validates booking parameters (dates, guests, safari_id, traveler_notes)
   - Verifies safari is in enquiry mode
   - Creates SafariBooking with is_enquiry=true, status=PENDING
   - Creates or finds existing ChatRoom between traveler and operator
   - Links booking to chat_room_id
   - Creates auto-generated message with enquiry details (dates, guests, traveler notes)
   - Returns redirect to messages page

2. `quoteEnquiry()` - POST /quote-enquiry (operator only)
   - Validates enquiry_id and quoted_price
   - **Quoted price = net price + 13% platform fee** (operator enters full amount traveler pays)
   - Updates booking: quoted_total_price, enquiry_status='quoted', quoted_at
   - Creates system message in chat with quote details
   - Notifies traveler

3. `confirmEnquiry()` - POST /confirm-enquiry (traveler only)
   - Validates enquiry_id
   - Updates booking: total_price=quoted_total_price, enquiry_status='confirmed'
   - Stores booking data in session
   - Returns redirect to checkout page

4. `declineEnquiry()` - POST /decline-enquiry (either party)
   - Updates booking: enquiry_status='declined', status='CANCELLED'
   - Creates system message in chat

**C. ChatController Modifications**
- Pass enquiry context when loading chat (booking details, quote status)
- Add method to fetch linked enquiry for a chat room

### Phase 3: Frontend Implementation

**A. Safari Details Page (safari-details.vue)**

The booking form behavior changes based on safari's `booking_mode`:

| booking_mode | Button Text | Action |
|--------------|-------------|--------|
| 'booking' | "Book Now" | Proceed to checkout (existing flow) |
| 'enquiry' | "Plan with Operator" | Submit enquiry → redirect to messages |

Form structure for enquiry mode:
```
┌─────────────────────────────────────┐
│ Date Selection (existing)           │
│ Guest Selection (existing)          │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │ Message to Operator             │ │
│ │ (textarea - same UI style)      │ │
│ │                                 │ │
│ │ Tell us about your trip...      │ │
│ └─────────────────────────────────┘ │
│                                     │
│ [Plan with Operator]                │
│                                     │
│ Still got questions?                │
│ Message the operator                │
└─────────────────────────────────────┘
```

Implementation approach:
- Check `safari.booking_mode` to determine UI state
- When `booking_mode === 'enquiry'`:
  - Show "Plan with Operator" instead of "Book Now"
  - Show traveler_notes textarea below guest selection
  - On submit: POST to `/create-enquiry` → redirect to messages page
- When `booking_mode === 'booking'`:
  - Existing behavior unchanged ("Book Now" → checkout)

**B. Messages Page (messages.vue / SendMessage.vue)**

Current structure:
- `roomDetails` prop contains chatRoom, chatRoomUser, chatName
- Message display area with date separators
- Sticky footer for message input

Implementation approach:
- Add `enquiry` prop to SendMessage.vue (fetched via ChatController)
- Add collapsible enquiry context panel above message area:
  ```
  ┌─────────────────────────────────────┐
  │ Safari Name                         │
  │ Dates: Jan 15 - Jan 20  │  2 Adults │
  │ Status: Pending / Quoted            │
  │                                     │
  │ [Send Quote] (operator only)        │
  │ - or -                              │
  │ Quote: $2,500                       │
  │ [Confirm & Pay]  [Decline]          │
  └─────────────────────────────────────┘
  ```
- Quote form: simple modal with price input
  - Label: "Total price (includes 13% platform fee)"
  - Operator enters the full amount traveler will pay
- Action buttons call API endpoints and update UI reactively

**C. Traveler Dashboard (my-trips.vue)**
- Add "Enquiries" tab/filter to show enquiries separately from confirmed bookings
- Show enquiry-specific status badges (Pending, Quoted, Confirmed, Declined)
- Actions: View chat, Confirm quote (if quoted), Decline

**D. Operator Safari Management**
- Add booking_mode toggle in safari create/edit form
- Default value: 'booking'
- Label: "Booking Mode" with options:
  - "Instant Booking" (booking) - Travelers can book and pay immediately
  - "Enquiry First" (enquiry) - Travelers must discuss with you before booking

### Phase 4: Checkout Flow Integration

**Modify checkout page (check-out.vue)**
- Detect if coming from enquiry (via session data)
- Use quoted_total_price instead of calculated price
- **Pricing breakdown for enquiry bookings:**
  - Total: quoted_total_price (what operator quoted)
  - Platform fee: calculated as 13% of total for internal tracking
  - Operator receives: quoted_total_price - platform fee
- Show "Enquiry Booking" indicator
- Rest of checkout flow remains the same (deposit option, Stripe payment)

### Phase 5: Notifications

Use existing notification system (database + real-time via Reverb):
- New enquiry notification to operator
- Quote received notification to traveler
- Enquiry accepted notification to operator

---

## File Changes Summary

### New Files
- `database/migrations/XXXX_add_booking_mode_to_safaris.php`
- `database/migrations/XXXX_add_enquiry_fields_to_safari_bookings.php`

### Modified Files
- `app/Models/Safari.php` - Add booking_mode field and helper methods
- `app/Models/SafariBooking.php` - Add new fields, relationships, scopes
- `app/Http/Controllers/Frontend/BookingController.php` - Add enquiry methods
- `app/Http/Controllers/Frontend/ChatController.php` - Add enquiry context
- `resources/js/Pages/Frontend/safari-details.vue` - Dynamic button/form based on booking_mode
- `resources/js/Pages/Frontend/Auth/messages.vue` - Add enquiry panel
- `resources/js/components/Frontend/SendMessage.vue` - Add enquiry context UI
- `resources/js/Pages/Frontend/Auth/my-trips.vue` - Add enquiries tab
- `routes/frontend.php` - Add new routes
- Safari create/edit pages - Add booking_mode toggle

---

## Routes to Add

```php
// Enquiry routes (authenticated travelers)
Route::post('/create-enquiry', [BookingController::class, 'createEnquiry']);
Route::post('/confirm-enquiry', [BookingController::class, 'confirmEnquiry']);
Route::post('/decline-enquiry', [BookingController::class, 'declineEnquiry']);

// Operator routes
Route::post('/quote-enquiry', [BookingController::class, 'quoteEnquiry']);
```

---

## Verification Plan

1. **Unit Tests**
   - Test enquiry creation with valid/invalid data
   - Test safari booking_mode enforcement
   - Test quote submission by operator (with platform fee calculation)
   - Test enquiry confirmation and checkout flow
   - Test decline by both parties

2. **Manual Testing Flow**
   - Operator: Create safari with booking_mode='enquiry'
   - Traveler: Browse safari → See "Plan with Operator" button → Fill form with dates, guests, message → Submit → Redirected to messages
   - Operator: Receive enquiry notification → View in messages → Send quote (total including 13% fee)
   - Traveler: Receive quote → Confirm → Checkout → Payment
   - Edge cases: Decline by either party, multiple enquiries on same safari

3. **Integration Points**
   - Verify safari booking_mode affects safari details page correctly
   - Verify chat room creation links correctly
   - Verify notifications fire on each state change
   - Verify checkout uses quoted price correctly with proper fee breakdown

---

## Implementation Order

1. **Database migrations** - Add booking_mode to safaris, enquiry fields to safari_bookings
2. **Model updates** - Safari booking_mode, SafariBooking relationships and scopes
3. **Backend endpoints** - createEnquiry, quoteEnquiry, confirmEnquiry, declineEnquiry
4. **Routes** - Add new routes in frontend.php
5. **Safari management** - Add booking_mode toggle to operator's safari create/edit
6. **Safari details page** - Dynamic "Plan with Operator" / "Book Now" based on booking_mode
7. **Chat integration** - Add enquiry context to ChatController, pass to frontend
8. **SendMessage.vue** - Add enquiry context panel with actions
9. **Checkout modifications** - Handle enquiry bookings with quoted price
10. **My-trips page** - Add enquiries display
11. **Testing** - Manual end-to-end flow testing
