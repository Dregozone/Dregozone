# Test Plan — Dregozone Portfolio/Blog

## 1. Overview

Dregozone is a Laravel 12 + Livewire 4 personal portfolio and blog website. It supports three roles: unauthenticated visitors, authenticated users, and admins. The testing approach combines **unit tests** (model logic, scopes, helpers) and **feature tests** (HTTP routes, Livewire component interactions, form validation), using Pest 3 with `RefreshDatabase` to ensure a clean DB state per test.

---

## 2. Roles

| Role | Description |
|---|---|
| **Unauthenticated User** | Any visitor who is not logged in. Can browse public pages, submit contact forms, sign up for the newsletter, and unsubscribe. |
| **Authenticated User** | A registered, logged-in user. Can access all public pages plus personal settings and admin-protected routes (admin routes only require `auth` middleware). |
| **Admin User** | Authenticated user whose email is `aclearmonth@gmail.com`. Redirected to admin blog index via the dashboard. |

---

## 3. User Stories by Role

### Unauthenticated User

- As an unauthenticated user, I want to view the home page so that I can see featured projects and blog posts.
- As an unauthenticated user, I want to browse the blog list so that I can find interesting articles.
- As an unauthenticated user, I want to search blog posts by keyword so that I can quickly find relevant content.
- As an unauthenticated user, I want to filter blog posts by tag so that I can read articles on a topic I care about.
- As an unauthenticated user, I want to sort blog posts by latest, oldest, or most popular so that I can browse in my preferred order.
- As an unauthenticated user, I want to read a published blog post so that I can consume the content.
- As an unauthenticated user, I want to receive a 404 when visiting a non-existent blog slug so that I know the post doesn't exist.
- As an unauthenticated user, I want my visit to increment the blog post view count so that the author can see engagement.
- As an unauthenticated user, I want to view the projects page so that I can see the portfolio.
- As an unauthenticated user, I want to view the contact page so that I can reach out.
- As an unauthenticated user, I want to submit a general contact form so that I can send a message.
- As an unauthenticated user, I want to submit a work_request contact form so that I can request project work.
- As an unauthenticated user, I want to submit a partnership contact form so that I can propose a collaboration.
- As an unauthenticated user, I want contact form validation to reject invalid data so that incomplete messages are not sent.
- As an unauthenticated user, I want to sign up for the newsletter so that I can receive updates.
- As an unauthenticated user, I want newsletter signup validation to reject duplicates so that I'm not re-registered.
- As an unauthenticated user, I want to unsubscribe from the newsletter via email link so that I can opt out.
- As an unauthenticated user, I want to view the privacy policy page so that I understand how my data is used.
- As an unauthenticated user, I want to be redirected to login when accessing admin routes so that protected areas are secured.

### Authenticated User

- As an authenticated user, I want to access admin blog management so that I can manage blog content.
- As an authenticated user, I want to search and filter blog posts in the admin list so that I can find posts quickly.
- As an authenticated user, I want to delete a blog post so that outdated content can be removed.
- As an authenticated user, I want to toggle a blog post's publish status so that I can control visibility.
- As an authenticated user, I want to view and filter contact messages so that I can respond to inquiries.
- As an authenticated user, I want to update a contact message's status so that I can track follow-up.
- As an authenticated user, I want to view newsletter subscribers so that I can manage the mailing list.
- As an authenticated user, I want to export active subscribers as JSON so that I can use the data in other tools.
- As an authenticated user, I want to access the image library and converter so that I can manage media.

### Admin User

- As an admin user, I want to be redirected to the admin blog index from the dashboard so that I land in the right place.

---

## 4. Test Cases

### 4.1 Public Pages

| Area | Scenario | Type |
|---|---|---|
| Home | Page renders successfully | Normal |
| Blog List | Renders with published posts and tags | Normal |
| Blog List | Search filters by title/excerpt/content | Normal |
| Blog List | Tag filter returns only matching posts | Normal |
| Blog List | Sort by latest/oldest/popular | Normal |
| Blog List | Empty state when no published posts | Edge |
| Blog Detail | Renders for published post | Normal |
| Blog Detail | Returns 404 for non-existent slug | Invalid |
| Blog Detail | Increments view count on mount | Normal |
| Projects | Page renders | Normal |
| Contact | Page renders | Normal |
| Privacy Policy | Page renders | Normal |

### 4.2 Contact Form

| Scenario | Type |
|---|---|
| Renders with default type `general` | Normal |
| Valid general submission creates ContactMessage | Normal |
| work_request submission stores budget/timeline/projectType in metadata | Normal |
| partnership submission | Normal |
| Validation rejects missing name | Invalid |
| Validation rejects invalid email | Invalid |
| Validation rejects name too short | Invalid |
| Validation rejects message too short | Invalid |
| Validation rejects subject too short | Invalid |
| Validation rejects invalid type | Invalid |
| Shows success flash after submit | Normal |
| Resets form fields after submit | Normal |

### 4.3 Newsletter Signup & Unsubscribe

| Scenario | Type |
|---|---|
| Valid email creates subscriber | Normal |
| Component sets subscribed=true | Normal |
| Invalid email rejected | Invalid |
| Duplicate email rejected | Invalid |
| Record has subscribed_at timestamp | Normal |
| secret_key auto-generated | Normal |
| Name stored when provided | Normal |
| Blank name allowed | Edge |
| Unsubscribe with valid email+key succeeds | Normal |
| Unsubscribe with invalid key shows error | Invalid |
| Unsubscribe missing email/key shows error | Invalid |
| Unsubscribe already-unsubscribed shows message | Edge |
| Unsubscribe non-existent email shows error | Invalid |

### 4.4 Admin Blog Post List

| Scenario | Type |
|---|---|
| Guests redirect to login | Special |
| Authenticated user can access | Normal |
| Posts are listed | Normal |
| Search by title filters results | Normal |
| Search by excerpt filters results | Normal |
| Status filter works | Normal |
| Sort by oldest/title/views works | Normal |
| Delete post removes it | Normal |
| Toggle status draft→published sets published_at | Normal |
| Toggle status published→draft clears published_at | Normal |
| Delete shows success flash | Normal |

### 4.5 Admin Contact Messages

| Scenario | Type |
|---|---|
| Guests redirect to login | Special |
| Authenticated user can view | Normal |
| Messages are listed | Normal |
| Search filters by name | Normal |
| Search filters by email | Normal |
| Search filters by subject | Normal |
| Filter by status works | Normal |
| Filter by type works | Normal |
| Status updated to replied | Normal |
| Status updated to ignored | Normal |
| Status updated to actioned | Normal |
| Status update shows flash | Normal |

### 4.6 Admin Newsletter Subscribers

| Scenario | Type |
|---|---|
| Guests redirect to login | Special |
| Authenticated user can view | Normal |
| Subscribers are listed | Normal |
| Search by email works | Normal |
| Active stats shown | Normal |
| Filter by subscribed | Normal |
| Filter by unsubscribed | Normal |
| Export returns JSON | Normal |
| Export contains only subscribed emails | Normal |
| Export guest redirect | Special |

### 4.7 Admin Image Library & Converter

| Scenario | Type |
|---|---|
| Guests redirect from image library | Special |
| Authenticated user views image library | Normal |
| Authenticated user views image converter | Normal |
| Guests redirect from image converter | Special |

### 4.8 User Model (Unit)

| Scenario | Type |
|---|---|
| isAdmin() true for admin email | Normal |
| isAdmin() false for other email | Normal |
| initials() full name → two letters | Normal |
| initials() single name → one letter | Edge |
| initials() triple name → first two letters | Edge |

### 4.9 NewsletterSubscriber Model (Unit)

| Scenario | Type |
|---|---|
| secret_key auto-generated on create | Normal |
| secret_key not overridden if preset | Normal |
| unsubscribe() sets is_subscribed=false and unsubscribed_at | Normal |
| subscribed() scope returns only subscribed | Normal |

### 4.10 ContactMessage Model (Unit)

| Scenario | Type |
|---|---|
| markAsRead() sets status=read | Normal |
| markAsReplied() sets status+status_changed_at | Normal |
| markAsIgnored() sets status+status_changed_at | Normal |
| markAsActioned() sets status+status_changed_at | Normal |
| new() scope returns only new | Normal |
| unread() scope returns new and read | Normal |

### 4.11 Tag Model (Unit)

| Scenario | Type |
|---|---|
| createFromName() creates new tag with correct slug | Normal |
| createFromName() returns existing tag (idempotent) | Edge |
| allNames() returns alphabetical names | Normal |
| topByUsage() returns tags by published post count | Normal |

---

## 5. Test Plan Table

| ID | Test Area | Test Case | Type | Expected Result | Pest Test Name |
|---|---|---|---|---|---|
| T01 | Public Pages | Home page renders | Normal | 200 OK | `home page renders successfully` |
| T02 | Public Pages | Blog page renders with posts | Normal | 200 OK, posts visible | `blog page renders with published posts` |
| T03 | Public Pages | Blog search filters by title | Normal | Only matching post visible | `blog page search filters results by title` |
| T04 | Public Pages | Blog search filters by excerpt | Normal | Only matching post visible | `blog page search filters results by excerpt` |
| T05 | Public Pages | Blog tag filter | Normal | Only tagged post visible | `blog page tag filter works` |
| T06 | Public Pages | Blog sort latest | Normal | Posts ordered newest first | `blog page sort by latest works` |
| T07 | Public Pages | Blog sort oldest | Normal | Posts ordered oldest first | `blog page sort by oldest works` |
| T08 | Public Pages | Blog sort popular | Normal | Posts ordered by views desc | `blog page sort by popular works` |
| T09 | Public Pages | Blog empty state | Edge | No posts shown | `blog page shows empty state when no posts` |
| T10 | Public Pages | Blog post detail renders | Normal | 200 OK, title visible | `blog post detail page renders for published post` |
| T11 | Public Pages | Blog post 404 | Invalid | 404 response | `blog post detail page returns 404 for nonexistent slug` |
| T12 | Public Pages | Blog post increments views | Normal | views count increased by 1 | `blog post detail page increments view count` |
| T13 | Public Pages | Projects page | Normal | 200 OK | `projects page renders` |
| T14 | Public Pages | Contact page | Normal | 200 OK | `contact page renders` |
| T15 | Public Pages | Privacy policy page | Normal | 200 OK | `privacy policy page renders` |
| T16 | Contact Form | Form renders | Normal | Component rendered | `contact form renders` |
| T17 | Contact Form | Valid general submit | Normal | ContactMessage created, type=general | `contact form submits successfully with valid general data` |
| T18 | Contact Form | work_request submit with metadata | Normal | metadata has budget/timeline/projectType | `contact form submits with work_request type` |
| T19 | Contact Form | partnership submit | Normal | ContactMessage with type=partnership | `contact form submits with partnership type` |
| T20 | Contact Form | Missing name | Invalid | Validation error on name | `contact form validates required name` |
| T21 | Contact Form | Invalid email | Invalid | Validation error on email | `contact form rejects invalid email` |
| T22 | Contact Form | Name too short | Invalid | Validation error (min:2) | `contact form rejects name too short` |
| T23 | Contact Form | Message too short | Invalid | Validation error (min:10) | `contact form rejects message too short` |
| T24 | Contact Form | Subject too short | Invalid | Validation error (min:5) | `contact form rejects subject too short` |
| T25 | Contact Form | Invalid type | Invalid | Validation error on type | `contact form rejects invalid type` |
| T26 | Contact Form | Success flash | Normal | Session flash message set | `contact form shows success flash message after submit` |
| T27 | Contact Form | Reset fields | Normal | Fields empty after submit | `contact form resets fields after submit` |
| T28 | Newsletter | Valid email signup | Normal | Subscriber record created | `newsletter signup form submits with valid email` |
| T29 | Newsletter | Component sets subscribed=true | Normal | subscribed property is true | `newsletter signup sets subscribed to true after success` |
| T30 | Newsletter | Invalid email | Invalid | Validation error on email | `newsletter signup rejects invalid email` |
| T31 | Newsletter | Duplicate email | Invalid | Validation error (unique) | `newsletter signup rejects duplicate email` |
| T32 | Newsletter | subscribed_at timestamp | Normal | subscribed_at not null | `newsletter signup creates subscriber with subscribed_at` |
| T33 | Newsletter | secret_key generated | Normal | secret_key not empty | `newsletter signup auto-generates secret_key` |
| T34 | Newsletter | Name stored | Normal | name column has value | `newsletter signup with name stores name` |
| T35 | Newsletter | Blank name allowed | Edge | Record created without name | `newsletter signup with blank name still works` |
| T36 | Newsletter | Valid unsubscribe | Normal | 200 OK, is_subscribed=false | `unsubscribe with valid email and key succeeds` |
| T37 | Newsletter | Invalid key | Invalid | 200 with error message | `unsubscribe with invalid key shows error` |
| T38 | Newsletter | Missing params | Invalid | 200 with error message | `unsubscribe with missing email or key shows error` |
| T39 | Newsletter | Already unsubscribed | Edge | 200 with already-unsubscribed message | `unsubscribe when already unsubscribed shows message` |
| T40 | Newsletter | Non-existent email | Invalid | 200 with error message | `unsubscribe with non-existent email shows error` |
| T41 | Admin Blog List | Guest redirect | Special | Redirect to /login | `guests are redirected from admin blog list` |
| T42 | Admin Blog List | Auth user can access | Normal | 200 OK | `authenticated user can view admin blog list` |
| T43 | Admin Blog List | Posts displayed | Normal | Post titles visible | `admin blog list shows posts` |
| T44 | Admin Blog List | Search by title | Normal | Matching post visible | `admin blog list search by title works` |
| T45 | Admin Blog List | Search by excerpt | Normal | Matching post visible | `admin blog list search by excerpt works` |
| T46 | Admin Blog List | Filter by status | Normal | Only filtered status posts | `admin blog list filter by status works` |
| T47 | Admin Blog List | Sort by oldest | Normal | Ascending order | `admin blog list sort by oldest works` |
| T48 | Admin Blog List | Sort by title | Normal | Alphabetical order | `admin blog list sort by title works` |
| T49 | Admin Blog List | Sort by views | Normal | Descending views order | `admin blog list sort by views works` |
| T50 | Admin Blog List | Delete post | Normal | Post removed from DB | `admin blog post can be deleted` |
| T51 | Admin Blog List | Toggle draft→published | Normal | status=published, published_at set | `admin blog post toggle status draft to published sets published_at` |
| T52 | Admin Blog List | Toggle published→draft | Normal | status=draft, published_at null | `admin blog post toggle status published to draft clears published_at` |
| T53 | Admin Blog List | Delete flash | Normal | Flash message shown | `admin blog post delete shows success flash` |
| T54 | Admin Contact | Guest redirect | Special | Redirect to /login | `guests are redirected from admin contact messages` |
| T55 | Admin Contact | Auth user can view | Normal | 200 OK | `authenticated user can view admin contact messages` |
| T56 | Admin Contact | Messages listed | Normal | Message visible in list | `admin contact messages list shows messages` |
| T57 | Admin Contact | Search by name | Normal | Matching message shown | `admin contact messages search by name filters results` |
| T58 | Admin Contact | Search by email | Normal | Matching message shown | `admin contact messages search by email filters results` |
| T59 | Admin Contact | Search by subject | Normal | Matching message shown | `admin contact messages search by subject filters results` |
| T60 | Admin Contact | Filter by status | Normal | Only matching status | `admin contact messages filter by status works` |
| T61 | Admin Contact | Filter by type | Normal | Only matching type | `admin contact messages filter by type works` |
| T62 | Admin Contact | Update status replied | Normal | status=replied | `admin contact message status can be updated to replied` |
| T63 | Admin Contact | Update status ignored | Normal | status=ignored | `admin contact message status can be updated to ignored` |
| T64 | Admin Contact | Update status actioned | Normal | status=actioned | `admin contact message status can be updated to actioned` |
| T65 | Admin Contact | Update shows flash | Normal | Flash message shown | `admin contact message update status shows flash message` |
| T66 | Admin Newsletter | Guest redirect | Special | Redirect to /login | `guests are redirected from admin newsletter subscribers` |
| T67 | Admin Newsletter | Auth user can view | Normal | 200 OK | `authenticated user can view admin newsletter subscribers` |
| T68 | Admin Newsletter | Subscribers listed | Normal | Subscriber email visible | `admin newsletter list shows subscribers` |
| T69 | Admin Newsletter | Search by email | Normal | Matching subscriber shown | `admin newsletter list can search by email` |
| T70 | Admin Newsletter | Active stats shown | Normal | View contains stats | `admin newsletter list shows active subscription stats` |
| T71 | Admin Newsletter | Filter subscribed | Normal | Only subscribed shown | `admin newsletter list filters by subscribed status` |
| T72 | Admin Newsletter | Filter unsubscribed | Normal | Only unsubscribed shown | `admin newsletter list filters by unsubscribed status` |
| T73 | Admin Newsletter | Export JSON | Normal | JSON response | `export active subscribers returns json response` |
| T74 | Admin Newsletter | Export only subscribed | Normal | JSON excludes unsubscribed | `export json contains only subscribed emails` |
| T75 | Admin Newsletter | Export guest redirect | Special | Redirect to /login | `export guest is redirected to login` |
| T76 | Admin Images | Guest redirect image library | Special | Redirect to /login | `guests are redirected from image library` |
| T77 | Admin Images | Auth user image library | Normal | 200 OK | `authenticated user can view image library` |
| T78 | Admin Images | Auth user image converter | Normal | 200 OK | `authenticated user can view image converter` |
| T79 | Admin Images | Guest redirect image converter | Special | Redirect to /login | `guests are redirected from image converter` |
| T80 | User Model | isAdmin true | Normal | returns true | `isAdmin returns true for admin email` |
| T81 | User Model | isAdmin false | Normal | returns false | `isAdmin returns false for non-admin email` |
| T82 | User Model | initials full name | Normal | Two-letter string | `initials returns correct initials for full name` |
| T83 | User Model | initials single name | Edge | One-letter string | `initials returns single initial for single name` |
| T84 | User Model | initials triple name | Edge | First two letters | `initials handles triple names` |
| T85 | NewsletterSubscriber | secret_key generated | Normal | Not empty after create | `secret key is auto generated on create` |
| T86 | NewsletterSubscriber | secret_key not overridden | Normal | Preset value preserved | `secret key is not overridden if already set` |
| T87 | NewsletterSubscriber | unsubscribe() | Normal | is_subscribed=false, unsubscribed_at set | `unsubscribe sets is subscribed to false and sets unsubscribed at` |
| T88 | NewsletterSubscriber | subscribed() scope | Normal | Excludes unsubscribed | `subscribed scope returns only subscribed subscribers` |
| T89 | ContactMessage | markAsRead | Normal | status=read | `markAsRead sets status to read` |
| T90 | ContactMessage | markAsReplied | Normal | status=replied, status_changed_at set | `markAsReplied sets status to replied and sets status changed at` |
| T91 | ContactMessage | markAsIgnored | Normal | status=ignored, status_changed_at set | `markAsIgnored sets status to ignored and sets status changed at` |
| T92 | ContactMessage | markAsActioned | Normal | status=actioned, status_changed_at set | `markAsActioned sets status to actioned and sets status changed at` |
| T93 | ContactMessage | new() scope | Normal | Only new messages | `new scope returns only new messages` |
| T94 | ContactMessage | unread() scope | Normal | New and read messages | `unread scope returns new and read messages` |
| T95 | Tag Model | createFromName new | Normal | Tag created with slug | `createFromName creates new tag with correct slug` |
| T96 | Tag Model | createFromName idempotent | Edge | Returns existing tag | `createFromName returns existing tag if slug already exists` |
| T97 | Tag Model | allNames alphabetical | Normal | Alphabetically sorted | `allNames returns all tag names ordered alphabetically` |
| T98 | Tag Model | topByUsage | Normal | Sorted by post count | `topByUsage returns tags sorted by published post usage count` |
