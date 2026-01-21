# Enquiry Quote Pricing Revision Plan

## Summary
Implement 2 in-scope revision items:
1. **Quote pricing logic**: Change from operator entering gross price to entering net price (what they receive), with platform adding 13% on top
2. **Pricing consistency**: Ensure consistent pricing display across chat, checkout, and booking records

## Formula Decision
Use `traveler_total = net_price / 0.87` to maintain mathematical consistency with regular bookings (13% of gross).

Example:
- Operator enters: $870 (net - what they receive)
- Traveler pays: $1,000 ($870 / 0.87)
- Platform fee: $130 (13% of $1,000)

---

## Phase 1: Database Migration

### New Migration: `add_quoted_net_price_to_safari_bookings`
```php
Schema::table('safari_bookings', function (Blueprint $table) {
    $table->decimal('quoted_net_price', 10, 2)->nullable()->after('quoted_total_price');
});
```

**File**: `database/migrations/XXXX_add_quoted_net_price_to_safari_bookings.php`

---

## Phase 2: Model Update

**File**: `app/Models/SafariBooking.php`

Add to `$fillable`:
```php
'quoted_net_price',
```

Add to `$casts`:
```php
'quoted_net_price' => 'decimal:2',
```

---

## Phase 3: Backend Changes

### 3.1 `quoteEnquiry()` method (lines 814-882)

**File**: `app/Http/Controllers/Frontend/BookingController.php`

Changes:
1. Update validation: `quoted_total_price` → `quoted_net_price`
2. Calculate traveler total from net price
3. Store both `quoted_net_price` and `quoted_total_price`
4. Update message content with contribution text

```php
$validatedData = $request->validate([
    'enquiry_id' => 'required|exists:safari_bookings,id',
    'quoted_net_price' => 'required|numeric|min:1',  // Changed
]);

// Calculate traveler total
$setting = Setting::first();
$platformFeePercentage = $setting->platform_fee ?? 13;
$netPrice = $validatedData['quoted_net_price'];
$travelerTotal = round($netPrice / (1 - ($platformFeePercentage / 100)), 2);

$enquiry->update([
    'quoted_net_price' => $netPrice,           // NEW
    'quoted_total_price' => $travelerTotal,    // Calculated
    'enquiry_status' => 'quoted',
    'quoted_at' => now(),
]);

// Update message content
$messageContent = "💰 Quote Provided\n\n";
$messageContent .= "Safari: {$enquiry->safari->title}\n";
$messageContent .= "Dates: {$checkIn} - {$checkOut}\n";
$messageContent .= "Guests: ...";
$messageContent .= "\n\nTotal: $" . number_format($travelerTotal, 2);
$messageContent .= "\n(Includes conservation and local community contribution)";
```

### 3.2 `confirmEnquiry()` method (lines 884-971)

Simplify fee calculation by using stored net price:

```php
// Replace lines 925-928
$payToOperator = $enquiry->quoted_net_price;

// Fallback for legacy quotes without quoted_net_price
if (!$payToOperator) {
    $setting = Setting::first();
    $platformFeePercentage = $setting->platform_fee ?? 13;
    $payToOperator = round($enquiry->quoted_total_price * (1 - ($platformFeePercentage / 100)), 2);
}
```

### 3.3 Notification text update (line 864)

```php
'body' => 'The operator has quoted a total of $' . number_format($travelerTotal, 2) . ' for your enquiry.',
```

---

## Phase 4: Frontend Changes

### 4.1 SendMessage.vue - Operator Quote Form (lines 19-31)

**File**: `resources/js/components/Frontend/SendMessage.vue`

Replace quote form:
```vue
<div v-if="isOperator && enquiry.enquiry_status === 'pending'" class="enquiry-actions">
    <div class="quote-form">
        <label>Your price (amount you'll receive)</label>
        <div class="quote-input-row">
            <span class="currency-symbol">$</span>
            <input type="number" v-model="quoteNetAmount" min="1" step="0.01"
                   placeholder="Enter your price" class="quote-input" />
            <button @click="submitQuote" :disabled="isSubmittingQuote || !quoteNetAmount" class="cmn-butn quote-btn">
                {{ isSubmittingQuote ? 'Sending...' : 'Send Quote' }}
            </button>
        </div>
        <div v-if="quoteNetAmount > 0" class="traveler-total-preview">
            <small>Traveler will pay: <strong>${{ calculateTravelerTotal(quoteNetAmount) }}</strong></small>
        </div>
    </div>
</div>
```

### 4.2 SendMessage.vue - Operator Quote Sent (lines 34-39)

```vue
<div class="quoted-info">
    <p>Your price: <strong>${{ formatPrice(enquiry.quoted_net_price) }}</strong></p>
    <p>Traveler pays: <strong>${{ formatPrice(enquiry.quoted_total_price) }}</strong></p>
    <p class="text-muted">Waiting for traveler to confirm...</p>
</div>
```

### 4.3 SendMessage.vue - Traveler Quote View (lines 42-54)

```vue
<div class="quote-received">
    <p class="quote-price">Total: <strong>${{ formatPrice(enquiry.quoted_total_price) }}</strong></p>
    <p class="contribution-note">Includes conservation and local community contribution</p>
    <div class="action-buttons">
        <!-- buttons unchanged -->
    </div>
</div>
```

### 4.4 SendMessage.vue - Script Changes

```javascript
// Change ref name
const quoteNetAmount = ref('');

// Add calculation helper
const calculateTravelerTotal = (netPrice) => {
    return (parseFloat(netPrice) / 0.87).toFixed(2);
};

// Update submitQuote to use new parameter
const submitQuote = () => {
    if (!quoteNetAmount.value || isSubmittingQuote.value) return;
    isSubmittingQuote.value = true;
    axios.post(route('frontend.quote-enquiry'), {
        enquiry_id: enquiry.value.id,
        quoted_net_price: quoteNetAmount.value  // Changed
    })
    // ...
};
```

### 4.5 SendMessage.vue - CSS Addition

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
    margin-top: 4px;
    font-style: italic;
}
```

### 4.6 check-out.vue - Contribution Message

**File**: `resources/js/Pages/Frontend/Auth/check-out.vue`

Add under the enquiry booking badge (around line 86):
```vue
<div v-if="bookingData?.is_enquiry_booking" class="enquiry-booking-badge mb-2">
    <span class="badge">Enquiry Booking</span>
    <small>Price agreed with operator</small>
    <small class="d-block text-muted mt-1">Includes conservation and local community contribution</small>
</div>
```

---

## Phase 5: Backfill Migration (Optional)

For existing quoted enquiries, calculate `quoted_net_price` from `quoted_total_price`:

```php
// Migration: backfill_quoted_net_price.php
DB::table('safari_bookings')
    ->where('is_enquiry', true)
    ->whereNotNull('quoted_total_price')
    ->whereNull('quoted_net_price')
    ->update([
        'quoted_net_price' => DB::raw('ROUND(quoted_total_price * 0.87, 2)')
    ]);
```

---

## Files to Modify

| File | Changes |
|------|---------|
| `database/migrations/XXXX_add_quoted_net_price_to_safari_bookings.php` | NEW - Add column |
| `app/Models/SafariBooking.php` | Add `quoted_net_price` to fillable/casts |
| `app/Http/Controllers/Frontend/BookingController.php` | Update `quoteEnquiry()` and `confirmEnquiry()` |
| `resources/js/components/Frontend/SendMessage.vue` | Quote form UI, calculation preview, contribution text |
| `resources/js/Pages/Frontend/Auth/check-out.vue` | Add contribution message |

---

## Verification Plan

1. **Test new quote flow**:
   - Operator enters $1,000 net → verify traveler sees $1,149.43
   - Verify database has both `quoted_net_price` and `quoted_total_price`

2. **Test pricing consistency**:
   - Price in chat matches checkout page
   - Booking record shows correct `pay_to_operator` value

3. **Test legacy fallback**:
   - Existing quotes without `quoted_net_price` still work

4. **Edge cases**:
   - Small amounts ($1 net → $1.15 traveler)
   - Large amounts ($10,000 net → $11,494.25 traveler)
   - Decimal precision
