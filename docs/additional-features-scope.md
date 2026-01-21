# Additional Features Scope & Complexity Analysis

**Document Purpose:** Technical breakdown of features outside the original $500 scope for client pricing decisions.

**Original Scope (Completed - $500):**
- Quote pricing logic (net vs gross)
- Pricing consistency across chat, checkout, and booking records

---

## Feature 1: Enquiry Submission with Login Flow & Data Preservation

### Current State
- Non-logged-in users see the enquiry form but submission fails with an error
- **Just implemented:** Login prompt message with links (simple UX improvement)

### Full Requested Behavior
1. User fills out enquiry form (dates, guests, message)
2. User clicks "Plan with Operator" while logged out
3. System preserves all form data
4. Redirects to login/register page
5. After successful login, automatically submits the preserved enquiry
6. Redirects user to messages page with the new conversation

### Technical Scope

| Component | Changes Required |
|-----------|------------------|
| **Frontend (safari-details.vue)** | Store form data in localStorage/sessionStorage before redirect |
| **Login page (login.vue)** | Check for pending enquiry data after successful auth |
| **Backend (BookingController)** | New endpoint or modify `createEnquiry` to handle deferred submissions |
| **Session/Storage handling** | Implement secure data preservation across auth flow |

### Complexity Assessment
- **Files affected:** 3-4 files
- **New logic:** Session-based enquiry preservation, post-login callback handling
- **Edge cases:**
  - Data expiration (what if user logs in days later?)
  - Multiple safari tabs with different enquiries
  - User registers instead of logs in (different flow)
- **Testing surface:** Auth flow + enquiry flow integration

### Complexity Rating: **Medium**

---

## Feature 2: Quote Lifecycle Flow (Multi-Quote Negotiation)

### Current State
- Operators can send only ONE quote per enquiry
- If traveler declines, enquiry status becomes "declined" and conversation is dead
- No way to revise pricing or continue negotiation

### Requested Behavior
1. Traveler declines quote → enquiry returns to "pending" status (not "declined")
2. Operator can send a revised quote
3. Back-and-forth continues until agreement or explicit closure
4. Quote history preserved in conversation

### Technical Scope

| Component | Changes Required |
|-----------|------------------|
| **Database** | New `enquiry_quotes` table to track quote history (or JSON column) |
| **SafariBooking model** | Relationship to quote history, new status logic |
| **BookingController** | Modify `declineEnquiry()` to reset status, allow re-quoting |
| **SendMessage.vue** | Show quote history, enable re-quote UI for operators after decline |
| **Message display** | Differentiate between quote versions (Quote #1, Quote #2, etc.) |

### Database Schema Addition
```
enquiry_quotes (new table)
- id
- safari_booking_id (FK)
- quoted_net_price
- quoted_total_price
- status (pending, accepted, declined)
- quoted_at
- responded_at
- created_at
```

### Complexity Assessment
- **Files affected:** 5-7 files
- **New logic:** Quote versioning, status state machine changes, UI for history
- **Edge cases:**
  - What happens to old quote messages in chat?
  - Maximum quote attempts?
  - Notifications for each revised quote
  - Preventing operator spam
- **Testing surface:** Full quote lifecycle with multiple iterations

### Complexity Rating: **Medium-High**

---

## Feature 3: Checkout Resumability

### Current State
- Traveler accepts quote → redirected to checkout
- If they navigate away, they cannot return to complete payment
- The accepted quote state is lost

### Requested Behavior
1. Accepted quotes maintain persistent "ready for checkout" state
2. "Continue to Checkout" button appears in:
   - Messages page (in the conversation)
   - My Trips / Dashboard
   - Booking detail view
3. Checkout page loads with all data pre-filled

### Technical Scope

| Component | Changes Required |
|-----------|------------------|
| **SafariBooking model** | Track checkout readiness state (new status or flag) |
| **BookingController** | Endpoint to resume checkout with booking ID |
| **Messages page (SendMessage.vue)** | Show "Continue to Checkout" for confirmed enquiries |
| **My Trips page** | Display pending checkouts with resume action |
| **Checkout page** | Accept booking ID param, load from DB instead of session |

### State Machine Update
```
Current: pending → quoted → confirmed → [checkout] → paid
New:     pending → quoted → confirmed (persistent) → paid
                              ↓
                    [Can resume checkout anytime]
```

### Complexity Assessment
- **Files affected:** 5-6 files
- **New logic:** Persistent checkout state, multiple entry points to checkout
- **Edge cases:**
  - Quote expiration (should confirmed quotes expire?)
  - Price changes after confirmation
  - Multiple devices/browsers
  - Partial payment states
- **Testing surface:** Multiple navigation paths to checkout

### Complexity Rating: **Medium**

---

## Feature 4: Early Balance Payment for Enquiry Bookings

### Current State
- Instant bookings support early balance payment after deposit
- Enquiry-based bookings with deposits have no "Pay Remaining Balance" option

### Requested Behavior
1. After deposit payment on enquiry booking, show remaining balance
2. "Pay Remaining Balance" button in My Trips section
3. Links to checkout with remaining amount pre-filled
4. Uses existing checkout flow

### Technical Scope

| Component | Changes Required |
|-----------|------------------|
| **My Trips page** | Add balance payment button for enquiry bookings with deposits |
| **BookingController** | Endpoint to initiate balance payment for enquiry bookings |
| **Checkout page** | Handle partial payment for enquiry bookings |
| **Payment processing** | Ensure Stripe handles partial payment correctly |

### Complexity Assessment
- **Files affected:** 3-4 files
- **New logic:** Minimal - mostly extending existing instant booking logic to enquiries
- **Edge cases:**
  - Balance calculation accuracy
  - Currency/fee consistency with original quote
  - Payment failure handling
- **Testing surface:** Payment flow for enquiry deposits

### Complexity Rating: **Low-Medium** (if instant booking early payment already works well)

---

## Summary Table

| Feature | Complexity | Files Affected | New DB Changes | Dependencies |
|---------|------------|----------------|----------------|--------------|
| 1. Login Flow & Data Preservation | Medium | 3-4 | None | Auth system |
| 2. Multi-Quote Negotiation | Medium-High | 5-7 | New table | Messaging, notifications |
| 3. Checkout Resumability | Medium | 5-6 | Status field | Checkout flow |
| 4. Early Balance Payment | Low-Medium | 3-4 | None | Existing payment logic |

---

## Implementation Order Recommendation

Based on dependencies and complexity:

1. **Feature 4 (Early Balance Payment)** - Lowest complexity, builds on existing patterns
2. **Feature 3 (Checkout Resumability)** - Medium complexity, improves UX significantly
3. **Feature 1 (Login Flow)** - Medium complexity, important for conversion
4. **Feature 2 (Multi-Quote)** - Highest complexity, requires careful state management

---

## Notes for Pricing

- Features 1, 3, and 4 are relatively self-contained
- Feature 2 (Multi-Quote) is the most complex due to:
  - Database schema changes
  - State machine modifications
  - UI changes across multiple components
  - Potential notification system updates

- All features share some common patterns (status management, UI updates in messages/trips)
- Implementing them together may offer some efficiency vs. one at a time

---

*Document generated: January 21, 2026*
*Branch: revisions-1*
