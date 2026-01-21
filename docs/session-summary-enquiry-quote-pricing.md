# Session Summary: Enquiry Quote Pricing Revision

**Date:** January 21, 2026
**Branch:** `revisions-1`
**Commit:** `616f3a0`

## Objective

Implement a revised pricing logic for enquiry-based bookings where:
1. Operators enter their **net price** (what they receive) instead of the gross price
2. The platform automatically calculates the traveler's total by adding the 13% platform fee on top
3. Consistent pricing display across chat, checkout, and booking records

## Formula Used

```
traveler_total = net_price / 0.87
```

**Example:**
- Operator enters: $870 (net - what they receive)
- Traveler pays: $1,000 ($870 / 0.87)
- Platform fee: $130 (13% of $1,000)

This maintains mathematical consistency with regular bookings where the platform takes 13% of the gross amount.

---

## Changes Made

### 1. Database Migration

**File:** `database/migrations/2026_01_21_025035_add_quoted_net_price_to_safari_bookings.php`

Added `quoted_net_price` column to `safari_bookings` table:
```php
$table->decimal('quoted_net_price', 10, 2)->nullable()->after('quoted_total_price');
```

### 2. Backfill Migration

**File:** `database/migrations/2026_01_21_025600_backfill_quoted_net_price_for_existing_enquiries.php`

Backfills existing quoted enquiries with calculated net price:
```php
DB::table('safari_bookings')
    ->where('is_enquiry', true)
    ->whereNotNull('quoted_total_price')
    ->whereNull('quoted_net_price')
    ->update([
        'quoted_net_price' => DB::raw('ROUND(quoted_total_price * 0.87, 2)')
    ]);
```

### 3. Model Update

**File:** `app/Models/SafariBooking.php`

Added `quoted_net_price` to:
- `$fillable` array
- `$casts` array with `'decimal:2'` type

### 4. Backend Controller Changes

**File:** `app/Http/Controllers/Frontend/BookingController.php`

#### `quoteEnquiry()` method (lines 815-889)
- Changed validation from `quoted_total_price` to `quoted_net_price`
- Calculates traveler total: `$travelerTotal = round($netPrice / (1 - ($platformFeePercentage / 100)), 2)`
- Stores both `quoted_net_price` and `quoted_total_price`
- Updated message content to include: "(Includes conservation and local community contribution)"
- Updated notification text

#### `confirmEnquiry()` method (lines 891-981)
- Uses stored `quoted_net_price` directly for `pay_to_operator`
- Added legacy fallback for quotes without `quoted_net_price`:
  ```php
  if (!$payToOperator) {
      $payToOperator = round($enquiry->quoted_total_price * (1 - ($platformFeePercentage / 100)), 2);
  }
  ```

### 5. Frontend - SendMessage.vue

**File:** `resources/js/components/Frontend/SendMessage.vue`

#### Operator Quote Form (pending status)
- Label changed to: "Your price (amount you'll receive)"
- Input placeholder: "Enter your price"
- Added real-time preview: "Traveler will pay: $X"

#### Operator Quote Sent (quoted status)
- Shows both prices:
  - "Your price: $X"
  - "Traveler pays: $X"

#### Traveler Quote View (quoted status)
- Shows: "Total: $X"
- Added: "Includes conservation and local community contribution"

#### Script Changes
- Renamed `quoteAmount` ref to `quoteNetAmount`
- Added `calculateTravelerTotal()` function: `(parseFloat(netPrice) / 0.87).toFixed(2)`
- Updated `submitQuote()` to send `quoted_net_price` parameter

#### CSS Additions
```css
.traveler-total-preview {
    margin-top: 8px;
    padding: 8px 12px;
    background: #e8f5e9;
    border-radius: 6px;
    color: #2e7d32;
}

.contribution-note {
    font-size: 12px;
    color: #666;
    margin-top: 0;
    margin-bottom: 12px;
    font-style: italic;
}
```

### 6. Frontend - Checkout Page

**File:** `resources/js/Pages/Frontend/Auth/check-out.vue`

Added contribution message to enquiry booking badge:
```vue
<small class="d-block text-muted mt-1">Includes conservation and local community contribution</small>
```

---

## Files Modified Summary

| File | Type | Description |
|------|------|-------------|
| `database/migrations/2026_01_21_025035_add_quoted_net_price_to_safari_bookings.php` | New | Add column migration |
| `database/migrations/2026_01_21_025600_backfill_quoted_net_price_for_existing_enquiries.php` | New | Backfill migration |
| `app/Models/SafariBooking.php` | Modified | Added field to fillable/casts |
| `app/Http/Controllers/Frontend/BookingController.php` | Modified | Updated quoteEnquiry & confirmEnquiry |
| `resources/js/components/Frontend/SendMessage.vue` | Modified | New quote UI with net price |
| `resources/js/Pages/Frontend/Auth/check-out.vue` | Modified | Added contribution message |

---

## Testing Checklist

- [ ] Operator enters $1,000 net → verify traveler sees $1,149.43
- [ ] Verify database has both `quoted_net_price` and `quoted_total_price`
- [ ] Price in chat matches checkout page
- [ ] Booking record shows correct `pay_to_operator` value
- [ ] Existing quotes without `quoted_net_price` still work (legacy fallback)
- [ ] Small amounts ($1 net → $1.15 traveler)
- [ ] Large amounts ($10,000 net → $11,494.25 traveler)
- [ ] Decimal precision maintained

---

## Related Documents

- `docs/revisions-1-plan.md` - Original implementation plan
- `docs/tuskari-revisions.pdf` - Requirements document
- `docs/enquiry-based-booking-implementation.md` - Previous enquiry booking implementation
