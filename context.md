# ESEF Centralized School Dashboard — Project Context

## Stack
| Layer | Technology |
|---|---|
| Framework | Laravel 12.62.0 |
| PHP | 8.2.12 (`C:\xampp\php\php.exe`) |
| Composer | 2.10.2 (`C:\laragon\bin\composer.phar`) |
| Database | MySQL/MariaDB 10.4.32 (XAMPP, 127.0.0.1:3306) |
| Frontend | Tailwind CSS v3 + Alpine.js + Chart.js (via Vite 7) |
| Auth scaffold | Laravel Breeze (Blade stack) |
| Node | 22.13.0 |

## Database
- **Main DB:** `centralized_dashboard`
- **Connection:** `mysql://root@127.0.0.1:3306/centralized_dashboard`
- **External placeholders:** `source_system_1`, `source_system_2` in `config/database.php`, credentials via `.env`

## Design System
- **Primary accent:** Indigo-Violet (`primary-*` — `#7c5df2` base)
- **Neutral base:** Slate (`surface-*`)
- **Fonts:** Space Grotesk (headings) + Inter (body) — Google Fonts
- **Semantic colors:** `success`, `warning`, `danger`, `info` — custom-tinted, not raw Tailwind defaults
- **Card style:** `rounded-card` (1rem), `shadow-card`, white bg with `border-surface-100`
- **Sidebar:** Dark (`#0f172a`) with active left-border indicator

## Test Accounts (seeded)
| Email | Password | Role |
|---|---|---|
| admin@school.com | password | admin |
| principal@school.com | password | principal |
| teacher@school.com | password | teacher |

---

## Key Files

### Backend
| File | Purpose |
|---|---|
| `app/Services/DashboardService.php` | All analytics: KPIs, Alerts, Campus Comparison, Trend, Fees, Exams |
| `app/Http/Controllers/DashboardController.php` | Thin controller → calls DashboardService |
| `app/Http/Controllers/AttendanceController.php` | Attendance drill-down: filter/sort/search/paginate |
| `app/Http/Controllers/AcademicsController.php` | Academics drill-down: filter/sort/search/paginate |
| `app/Http/Controllers/FinanceController.php` | Finance dashboard: Expected vs Collected metrics and trend |
| `app/Http/Controllers/StaffController.php` | Staff directory management with role updates & account creation |
| `app/Http/Middleware/CheckRole.php` | Route-level role guard: `role:admin`, `role:admin,principal` |
| `app/Models/User.php` | `role` enum + `hasRole()`, `hasAnyRole()`, `isAdmin()` helpers |
| `app/Models/AttendanceSummary.php` | Attendance summary model |
| `app/Models/FeeSummary.php` | Fee summary model |
| `app/Models/ExamSummary.php` | Exam summary model |
| `config/database.php` | Adds `source_system_1`, `source_system_2` placeholder MySQL connections |
| `routes/web.php` | Routing for all dashboard sections |
| `bootstrap/app.php` | Registers `role` middleware alias |

### Frontend / Views
| File | Purpose |
|---|---|
| `resources/views/components/layouts/dashboard.blade.php` | Main layout: dark sidebar, sticky topbar, mobile-responsive |
| `resources/views/components/kpi-card.blade.php` | Reusable KPI card with icon/trend/color variants |
| `resources/views/dashboard.blade.php` | Dashboard: KPIs → Alerts → Charts → Campus Comparison → Exam Table |
| `resources/views/attendance/index.blade.php` | Attendance drill-down with filters, stats, sortable paginated table |
| `resources/views/academics/index.blade.php` | Academics drill-down with KPIs, Chart.js class comparisons, paginated table |
| `resources/views/finance/index.blade.php` | Finance dashboard with expected/collected target KPIs, monthly collection line, paginated table |
| `resources/views/staff/index.blade.php` | Admin-only Staff directory grid, role update modals, safe user deletion, and add modals |
| `resources/css/app.css` | Design system CSS: component classes, Google Fonts import |
| `resources/js/app.js` | Alpine.js + Chart.js with themed global defaults |
| `tailwind.config.js` | Full design tokens: colors, fonts, radii, shadows |

### Migrations (in run order)
| Migration | Creates |
|---|---|
| `0001_01_01_000000` | `users` |
| `0001_01_01_000001` | `cache`, `cache_locks` |
| `0001_01_01_000002` | `jobs`, `job_batches`, `failed_jobs` |
| `2024_01_02_000001` | Adds `role` column to `users` |
| `2024_01_02_000002` | `attendance_summaries` |
| `2024_01_02_000003` | `fee_summaries` |
| `2024_01_02_000004` | `exam_summaries` |

---

## Dashboard Features

### KPI Cards (top row)
Four cards: Total Students, Avg Attendance %, Fee Collection %, Avg Exam Pass Rate.
Trend arrows compare latest month vs previous month.

### Needs Attention Alerts Widget
Auto-generated from thresholds (constants in `DashboardService`):
- Amber border = warning, Red border = critical (<50%)
- Max 5 items shown, "View all" link if more
- Each item has a "View →" link to filtered Attendance drill-down
- Green healthy empty state if no alerts fire

### Charts Row
- **Attendance Trend** (line): 6 months × 3 campuses
- **Fee Collection by Campus** (bar): Collected vs Outstanding, current month

### Campus Performance Comparison Table
Columns: Campus, Students, Attendance %, Fee Collection %, Pass Rate %, Health Score, Status.
- Health Score = average of the 3 percentage metrics, sorted descending
- Status badges: Excellent (≥90), Needs Attention (≥75), Critical (<75)
- Color-coded cells: green tint >90%, red tint <75%, with directional arrows
- Mini progress bar per health score
- **Every row is clickable** → `/attendance?campus=CampusName`

### Recent Exam Results Table
10 most recent exams, color-coded pass rate badges.

---

## Attendance Drill-Down (`/attendance`)
- **Filters:** Campus, Class, Date From, Date To, Class name search
- **Pre-filter from dashboard:** `?campus=X` passed as query param
- **Summary stats bar:** Avg Attendance (filtered), Best Class, Worst Class
- **Table:** Class / Campus / Month / Students / Present / Absent / Attendance %
  - All columns sortable (click header, toggles asc/desc)
  - Color-coded attendance % badges
- **Pagination:** 15 records/page, custom UI with page range links

---

## Academics Drill-Down (`/academics`)
- **Filters:** Campus, Class, Date From, Date To, Class/Exam name search
- **KPIs:** Avg Pass Rate, Avg Class Score, Best Performing Class name, Needs Improvement class name
- **Charts:** Chart.js bar chart showing average pass rate across classes
- **Table:** Exam / Campus / Class / Students / Pass % / Avg Score / Date
  - All columns sortable (click header, toggles asc/desc)
- **Pagination:** 15 records/page, custom UI

---

## Finance Drill-Down (`/finance`)
- **Filters:** Campus, Date From, Date To
- **KPIs:** Total Expected (₨), Total Collected (₨), Outstanding Balance (₨), Recovery Rate %
- **Charts:** Target vs Recovery comparative bar chart per month
- **Table:** Month / Campus / Expected Fee / Collected Fee / Outstanding / Collection %
  - All columns sortable (click header, toggles asc/desc)
- **Pagination:** 15 records/page, custom UI

---

## Staff Directory & Management (`/staff`)
- **Features:** Only accessible to `admin` role users.
- **Directories:** Search bar, role filter dropdown, profile initial avatar, joined date.
- **Actions:** Add Staff Member modal, inline Change Role modal, Delete Staff Member modal (safe guard preventing deleting or updating one's own role).
- **Pagination:** 10 records/page, custom UI

---

## Role-Based Access
| Role | Dashboard | Attendance | Academics | Finance | Staff |
|---|---|---|---|---|---|
| admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| principal | ✅ | ✅ | ✅ | ✅ | ❌ (403) |
| teacher | ✅ | ✅ | ✅ | ✅ | ❌ (403) |

Staff link hidden in sidebar for non-admins.

---

## Dev Commands

```bash
# Start dev server
C:\xampp\php\php.exe artisan serve --port=8000

# Rebuild frontend assets
npm run build        # production
npm run dev          # hot-reload

# Re-seed database (drops all tables)
C:\xampp\php\php.exe artisan migrate:fresh --seed

# Clear caches
C:\xampp\php\php.exe artisan view:clear
C:\xampp\php\php.exe artisan cache:clear
C:\xampp\php\php.exe artisan config:clear
```
