# Car Listing Laravel 12 — Full Project Plan

## Tech Stack

| Layer | Choice |
|---|---|
| Framework | Laravel 12 |
| Auth | Laravel Breeze (Blade with Alpine) |
| Frontend | Blade + Alpine.js + Tailwind CSS v4 |
| Build Tool | Vite |
| Image Storage | Laravel Storage (local/public disk) |
| Email | Laravel Mail (Mailable + Notifications) |
| Payment | Stripe (stripe-php) |
| Social Share | Web Share API + WhatsApp/Facebook URL schemes |

---

## Phase 0: Project Setup

- Create Laravel 12 project
- Install Laravel Breeze (Blade + Alpine + Tailwind)
- Install dependencies: intervention/image, stripe/stripe-php
- Configure .env, .gitignore
- Init git, connect to GitHub repo

---

## Phase 1: Database Migrations & Models

### Migrations (in order)

**users** — extend default (add role, phone, avatar, is_banned)

**cars**
- id, user_id (FK), make, model, year, price (decimal 12,2)
- mileage (integer), fuel_type (enum: petrol/diesel/electric/hybrid)
- transmission (enum: manual/automatic), city, description (text)
- status (enum: draft/pending/approved/rejected)
- rejection_reason (text nullable), is_featured (boolean)
- featured_at (timestamp nullable), featured_until (timestamp nullable)
- views_count (integer), slug (string unique)
- timestamps

**car_images**
- id, car_id (FK), path, is_primary (boolean), sort_order, timestamps

**favorites**
- id, user_id (FK), car_id (FK), timestamps
- unique constraint: (user_id, car_id)

**conversations**
- id, car_id (FK), sender_id (FK users), receiver_id (FK users), timestamps
- unique constraint: (car_id, sender_id, receiver_id)

**messages**
- id, conversation_id (FK), user_id (FK), body (text), read_at, timestamps

**payments**
- id, user_id (FK), car_id (FK), stripe_payment_id, amount (decimal 10,2)
- type (enum: featured/boost), status (enum: pending/completed/failed)
- paid_at, timestamps

**settings**
- id, key (string unique), value (text), timestamps

### Models & Relationships

```
User hasMany Car, Favorite, Message, Payment
Car belongsTo User, hasMany CarImage, hasMany Favorite, hasMany Conversation
CarImage belongsTo Car
Favorite belongsTo User, belongsTo Car
Conversation belongsTo Car, belongsTo User (sender/receiver), hasMany Message
Message belongsTo Conversation, belongsTo User
Payment belongsTo User, belongsTo Car
```

---

## Phase 2: Auth & User Roles

- Breeze provides: Register, Login, Logout, Password Reset, Profile
- AdminMiddleware + ClientMiddleware
- Default role = client on registration
- Admin seeded via DatabaseSeeder
- Client dashboard: stats, tabs (My Listings, Favorites, Messages)

---

## Phase 3: Admin Panel

### Routes (prefix: /admin)
- Dashboard (stats + Chart.js charts)
- Cars: list all, filter by status, approve, reject (with reason)
- Users: list, ban, unban
- Payments: list all
- Settings: view, update

### Approval Workflow
- Admin sees pending cars queue
- Approve → status=approved, email sent
- Reject → modal for reason, status=rejected, email sent

---

## Phase 4: Car CRUD (Client Side)

### Routes
- GET/POST /my-cars, GET /my-cars/create, PUT/DELETE /my-cars/{car}

### Multi-Step Form (Alpine.js Wizard)
- Step 1: Make, Model, Year, Fuel, Transmission, Mileage, City
- Step 2: Price, Condition, Description
- Step 3: Photo upload (drag-drop, max 10, preview, reorder, set primary)
- Step 4: Preview & Submit → status=pending

### Image Handling
- Intervention Image for resize/thumbnail
- Store originals + thumbnails
- Delete images when car is deleted

### Edit → resets status to pending (re-approval needed)

---

## Phase 5: Public Car Listing (Front Page)

### Routes
- GET / → home listing page
- GET /cars/{slug}-{id} → detail page
- GET /api/cars → AJAX filter endpoint

### Home Page
- Filter sidebar (desktop) / slide-out drawer (mobile)
- Filters: Make, Model (dependent), Year, Price, Fuel, Transmission, Mileage, City
- Filter state in URL query params
- Sorting: newest, price low-high, price high-low, mileage low-high
- Pagination: 12 per page

### Car Card
- Primary image, title, price, badges, city, favorite icon, featured badge, share button

### Detail Page
- Image gallery (swipeable, lightbox)
- Vehicle info, price, description
- Seller info card, Contact Seller button
- Favorite + Share buttons
- View counter, Related cars

### SEO
- Auto slug: {make}-{model}-{year}-{id}
- OG meta tags, Twitter Card meta, title, description
- JSON-LD structured data
- Canonical URL

---

## Phase 6: Social Sharing

### Strategy: 3-tier approach
1. **Native Web Share API** (primary on mobile): navigator.share() → OS share sheet
2. **Direct platform links** (fallback): WhatsApp wa.me, Facebook dialog/share
3. **Copy Link** (universal fallback)

### Implementation
- Alpine.js share component
- WhatsApp: https://wa.me/?text={encoded}
- Facebook: https://www.facebook.com/dialog/share?app_id={ID}&href={url}
- Config: FACEBOOK_APP_ID in .env
- OG meta tags for rich previews

---

## Phase 7: Messaging System

### Routes
- GET /messages → inbox
- GET /messages/{conversation} → thread
- POST /messages/car/{car}/user/{seller} → send/create

### Behavior
- Create conversation on first "Contact Seller" click
- Chat-like UI, auto-scroll
- Polling every 10s via Alpine.js AJAX
- Unread badge in nav
- Mark read when conversation opened
- NewMessageNotification email

---

## Phase 8: Favorites / Wishlist

### Routes
- GET /favorites → list favorites
- POST /favorites/{car}/toggle → toggle

### Behavior
- Heart icon toggle on cards + detail page
- AJAX toggle, returns JSON
- Redirect to login if not auth'd
- Favorites page shows saved cars

---

## Phase 9: Email Notifications

| Notification | Trigger | Recipient |
|---|---|---|
| CarApprovedNotification | Admin approves car | Car owner |
| CarRejectedNotification | Admin rejects car | Car owner |
| NewMessageNotification | New message | Message receiver |
| PaymentSuccessNotification | Stripe payment OK | Payer |

- Notification classes in app/Notifications/
- Mailables in app/Mail/
- Templates in resources/views/emails/
- Queue for async sending

---

## Phase 10: Premium Listings (Stripe)

### Plans
| Plan | Price | Duration |
|---|---|---|
| Featured | $5 | 7 days |
| Premium Featured | $10 | 30 days |

### Flow
- Client clicks "Feature This" on approved car
- Select plan → Stripe Checkout → redirect
- Webhook updates payment, sets is_featured, featured_until
- Featured badge on cards, sorted first in results

### Auto-Expire
- Daily scheduled task checks featured_until, resets is_featured

---

## Phase 11: Admin Dashboard Stats

### Stats
- Total cars, pending, approved, rejected
- Total users, new this month
- Total revenue
- Cars by status (pie chart)
- New listings/week (line chart, 12 weeks)
- Top cities (bar chart)
- Revenue/month (bar chart)

### Charts: Chart.js via Alpine.js

---

## Phase 12: Mobile Responsiveness

- Bottom nav bar (Home, Search, Add, Messages, Profile)
- Slide-out filter drawer
- Touch-friendly cards
- Swipeable gallery
- Responsive grid: 1 col mobile → 2 col tablet → 3 col desktop

---

## Phase 13: Seeders & Sample Data

- UserSeeder (admin + 5 clients)
- CarSeeder (20-30 cars, various statuses)
- CarImageSeeder
- SettingSeeder
- Popular makes, cities, sample data

---

## Phase 14: Testing

- Auth feature tests
- Car CRUD tests
- Admin approval tests
- Favorites + Messages tests
- Filtering tests
- Payment tests
- Model relationship + scope tests

---

## Phase 15: Final Polish

- Custom error pages (404, 403, 500)
- Database indexes for performance
- Image lazy loading
- Query optimization (eager loading)
- CSRF, validation, authorization policies
- Rate limiting

---

## Implementation Order

| Phase | Description |
|---|---|
| 0 | Project setup + Breeze + deps |
| 1 | Migrations + Models |
| 2 | Auth + Roles + Middleware |
| 3 | Admin Panel + Approval |
| 4 | Car CRUD + Multi-step Form + Images |
| 5 | Public Listing + Filtering + Detail + SEO |
| 6 | Social Sharing |
| 7 | Messaging System |
| 8 | Favorites |
| 9 | Email Notifications |
| 10 | Stripe Payments |
| 11 | Admin Dashboard Stats |
| 12 | Mobile Responsiveness |
| 13 | Seeders |
| 14 | Testing |
| 15 | Final Polish |
