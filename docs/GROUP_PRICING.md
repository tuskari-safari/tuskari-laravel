# Group Pricing System

## Overview
The group pricing system allows safari operators to offer discounted rates based on the number of travelers in a group. This encourages larger bookings and provides better value for customers traveling in groups.

## How It Works

### Database Structure
- **Table**: `safari_group_pricings`
- **Fields**:
  - `safari_id`: Links to the safari
  - `min`: Minimum number of travelers for this pricing band
  - `max`: Maximum number of travelers for this pricing band  
  - `pp`: Price per person for this range

### Example Pricing Bands
```
Safari ID: 123
Band 1: 2-4 travelers = $1800 per person
Band 2: 5-8 travelers = $1600 per person
Band 3: 9-12 travelers = $1400 per person
```

### Logic Flow
1. User selects number of adults on safari details page
2. Frontend calls `/check-price-special-discount` API endpoint
3. Backend finds matching pricing band where `min <= numberOfAdults <= max`
4. If match found, returns discounted price; otherwise returns base price
5. Frontend updates pricing display and shows discount notification

### API Endpoint
**GET** `/check-price-special-discount`

**Parameters**:
- `safari_id`: ID of the safari
- `noOfAdults`: Number of adult travelers
- `adultPrice`: Base adult price (for fallback)

**Response**:
```json
{
  "price": 1800,
  "has_group_pricing": true,
  "group_range": "2-4 travelers",
  "original_price": 2000,
  "savings": 200
}
```

### Frontend Integration
- Automatically checks for group pricing when number of adults changes
- Displays discount notification when applicable
- Shows savings amount to encourage bookings
- Uses discounted price in total calculations

### Testing
Run the group pricing tests:
```bash
php artisan test tests/Feature/GroupPricingTest.php
```

## Benefits
- **For Operators**: Encourages larger group bookings
- **For Travelers**: Better value for group travel
- **For Platform**: Higher booking values and customer satisfaction