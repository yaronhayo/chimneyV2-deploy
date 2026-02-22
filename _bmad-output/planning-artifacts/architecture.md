---
stepsCompleted:
  [
    'step-01-init',
    'step-02-context',
    'step-03-starter',
    'step-04-decisions',
    'step-05-patterns',
    'step-06-structure',
    'step-07-validation',
    'step-08-complete',
  ]
inputDocuments:
  - '_bmad-output/planning-artifacts/prd.md'
  - '_bmad-output/planning-artifacts/landing-page-strategy.md'
  - '_bmad-output/planning-artifacts/cro-playbook.md'
workflowType: 'architecture'
status: 'complete'
completedAt: '2026-02-20'
project_name: '1st Class Chimney & Air Duct'
user_name: 'Yaron'
date: '2026-02-20'
---

# Architecture Decision Document

_This document builds collaboratively through step-by-step discovery. Sections are appended as we work through each architectural decision together._

## Project Context Analysis

### Requirements Overview

**Functional Requirements: 53 FRs across 10 capability areas**

| Component Group                 | FRs | Architectural Implication                       |
| ------------------------------- | --- | ----------------------------------------------- |
| Static Content (FR1-8, FR35-42) | 16  | Astro pages + components — zero-JS, pure SSG    |
| Form & Conversion (FR9-13)      | 5   | Client-side form with server-side handler       |
| Email Integration (FR14-18)     | 5   | SMTP2GO API — the only backend dependency       |
| Analytics (FR19-22)             | 4   | GTM script injection — no architectural weight  |
| SEO (FR23-26)                   | 4   | Build-time generation — Astro handles natively  |
| Responsive/Perf (FR27-30)       | 4   | CSS + Astro `<Image>` — build-time optimization |
| Accessibility (FR31-34)         | 4   | HTML semantics — no architectural component     |
| Spam/Bot Protection (FR43-48)   | 6   | Server-side validation layer needed             |
| Click Fraud (FR49-53)           | 5   | Client-side detection + GTM filtering           |

**Architecture-Shaping NFRs:**

| NFR      | Constraint                               | Architectural Impact                      |
| -------- | ---------------------------------------- | ----------------------------------------- |
| NFR1-7   | Performance (LCP <1.5s, <500KB, zero JS) | Forces SSG-only, no client framework      |
| NFR10    | API key server-side only                 | Requires server-side form handler         |
| NFR11    | Rate limiting per IP                     | Server-side middleware or edge function   |
| NFR12    | CSP headers                              | Server/CDN response header config         |
| NFR13    | No PII on static site                    | Data flows through handler → SMTP2GO only |
| NFR16    | Graceful degradation                     | Fallback to phone number if SMTP2GO fails |
| NFR23-25 | Maintainability                          | Git-push deploy pipeline                  |

### Scale & Complexity

- **Complexity level:** Low — Single static page + one API integration
- **Primary domain:** Static web with serverless form handler
- **Estimated architectural components:** 5
  1. Astro SSG build pipeline
  2. Form handler (serverless function or Astro SSR endpoint)
  3. SMTP2GO integration layer
  4. Anti-spam/fraud detection layer
  5. CDN/hosting with edge config (headers, redirects)

### Technical Constraints & Dependencies

| Constraint                | Source    | Impact                                              |
| ------------------------- | --------- | --------------------------------------------------- |
| Astro framework           | PRD §1    | Locks SSG build pipeline, enables zero-JS           |
| SMTP2GO provider          | PRD §1    | Single external API dependency                      |
| No database               | PRD scope | All state is ephemeral — no persistence layer       |
| No auth/login             | PRD scope | No identity architecture needed                     |
| Solo developer            | PRD §8    | Complexity ceiling — no multi-service orchestration |
| Design tokens from Stitch | PRD §1    | CSS custom properties as design system              |

### Cross-Cutting Concerns

1. **Form submission security** — Spans FR9 (form), FR43-48 (spam), NFR8-14 (security). The ONE path where data flows through a server. Every security concern concentrates here.
2. **Email deliverability** — Spans FR14-18 (notifications), NFR14 (SPF/DKIM), NFR17 (98% delivery). Domain DNS + template quality + SMTP2GO config must all align.
3. **Performance budget** — Spans FR27-30, FR35-42 (visual assets), NFR1-7. Every icon, image, and font competes for the 500KB budget.
4. **Click fraud vs. user experience** — FR49-53 must coexist with FR53's guardrail: zero friction for real users.

## Starter Template Evaluation

### Primary Technology Domain

Static web (SSG) with hybrid SSR for a single form endpoint — identified from PRD §1, §7, §8.

### Starter Options Considered

| Option                    | Setup                            | Verdict                                                 |
| ------------------------- | -------------------------------- | ------------------------------------------------------- |
| `--template minimal`      | Empty Astro shell, zero opinions | ✅ **Selected** — clean canvas for Stitch design tokens |
| `--template landing-page` | Pre-built marketing layout       | ❌ Conflicts with Stitch design tokens                  |
| `--template blog`         | Content-heavy with MDX           | ❌ Wrong domain                                         |

### Selected Starter: Astro Minimal

**Rationale:** Design system comes from Stitch mockups → design tokens. A pre-built template would fight that. Minimal gives zero opinions to override.

**Initialization Command:**

```bash
npm create astro@latest ./ -- --template minimal --typescript strict --install --git
```

**Astro Version:** 5.17.3 (latest stable). Astro 6 in beta — not recommended for production.

### Architectural Decisions Provided by Starter

**Language & Runtime:**

- TypeScript (strict mode) — catches form handler type issues at build time
- Node.js runtime for SSR form endpoint

**Styling Solution:**

- Vanilla CSS with custom properties from Stitch design tokens (per PRD)
- No Tailwind, no CSS framework

**Build Tooling:**

- Vite (bundled with Astro) — CSS, image optimization, dev server
- Astro `<Image>` for WebP/srcset/lazy (FR29, NFR4)

**Testing Framework:**

- None included — add Playwright for form submission E2E tests

**Code Organization:**

```
src/
  components/    ← Astro components (hero, form, services, testimonials)
  layouts/       ← Base layout with head/meta/GTM
  pages/         ← index.astro (landing page)
  pages/api/     ← form-submit.ts (SSR endpoint for SMTP2GO)
  styles/        ← design-tokens.css + global.css
  data/          ← testimonials.json, services.json
public/          ← favicon, OG image, robots.txt
```

**Key Astro Features:**

- `@astrojs/sitemap` — Auto-sitemap generation (FR25)
- Pure SSG output — no adapter needed (form handled by PHP)
- Astro `<Image>` — WebP/srcset/lazy loading at build time

**Note:** Project initialization using this command should be the first implementation story.

## Core Architectural Decisions

### Decision Priority Analysis

**Critical Decisions (Block Implementation):**

1. Form handler pattern → PHP endpoint
2. Hosting platform → Hostinger
3. Anti-spam strategy → Multi-layer, zero-friction
4. Secret management → `env.php`

**Deferred Decisions (Post-MVP):**

- Multi-page routing (Growth Phase)
- Call tracking integration (Growth Phase)
- A/B testing framework (Growth Phase)
- Blog/CMS architecture (Expansion Phase)

### Decision 1: Form Handler — PHP Endpoint

**Decision:** PHP form handler at `/api/submit.php`
**Rationale:** Hostinger shared hosting runs PHP natively. No Node.js runtime available — Astro SSR endpoints not supported.
**Affects:** FR9-13 (form), FR14-18 (email), FR43-48 (spam protection)

**Flow:**

```
Browser form POST → /api/submit.php → validate + sanitize → SMTP2GO API (curl) → JSON response
```

**Handler Responsibilities:**

- Input sanitization (XSS/injection prevention)
- Honeypot field check
- Time-to-submit validation (<3s = reject)
- Phone number format validation (10-digit US)
- IP rate limiting (file-based counter, 3/hour)
- SMTP2GO API call (lead notification to 3 recipients + autoresponder)
- Graceful degradation: return phone number if SMTP2GO fails (NFR16)

### Decision 2: Hosting & Deployment — Hostinger via GitHub

**Decision:** Hostinger shared hosting with GitHub auto-deploy
**Rationale:** Existing hosting infrastructure. PHP support. Free SSL. CDN included.
**Affects:** NFR15 (uptime), NFR23 (deployment speed)

**Pipeline:**

```
Local dev → git push → GitHub → Hostinger auto-deploy → Live
```

**Build Strategy:**

- Astro builds static HTML/CSS/JS locally or in CI
- `dist/` output deployed to Hostinger `public_html/`
- PHP files deployed alongside static assets
- `.htaccess` for URL rewriting, CSP headers, caching rules

### Decision 3: Anti-Spam — Multi-Layer, Zero Friction

**Decision:** 4-layer server-side protection with no user-facing CAPTCHA
**Rationale:** FR53 guardrail — zero friction for real users.
**Affects:** FR43-48, FR53

| Layer               | Implementation                    | Catches       |
| ------------------- | --------------------------------- | ------------- |
| 1. Honeypot         | Hidden CSS field in form          | Dumb bots     |
| 2. Time-to-submit   | Server-side timestamp check (<3s) | Fast bots     |
| 3. Phone validation | PHP regex: 10-digit US format     | Garbage input |
| 4. IP rate limiting | File-based counter, max 3/hour    | Spam floods   |

**Upgrade path:** Cloudflare Turnstile can be added later with no architecture change.

### Decision 4: Environment Configuration — `env.php`

**Decision:** PHP include file outside web root for secrets
**Rationale:** Battle-tested pattern across existing sites. Server-side only. Never in client bundle (NFR10).
**Affects:** NFR10, NFR13

**Structure:**

```php
<?php
// env.php — outside web root, gitignored
return [
    'SMTP2GO_API_KEY' => 'api-xxxxx',
    'SMTP2GO_SENDER' => 'info@1stclasschimney.com',
    'NOTIFICATION_RECIPIENTS' => ['mike@...', 'office@...', 'backup@...'],
];
```

**Security:**

- File outside web root — not accessible via HTTP
- Gitignored — never in repository
- PHP `return` pattern — no global variables
- Template `env.php.example` committed with placeholder values

### Decision Impact: Astro Configuration

**Pure SSG mode (simplified from original hybrid plan):**

```diff
- output: 'hybrid' (SSG + SSR)
+ output: 'static' (SSG only)
- @astrojs/cloudflare adapter
+ No adapter needed
- astro:actions for form handling
+ Standard <form> POST to /api/submit.php
- TypeScript form handler
+ PHP form handler
```

**Security Headers via `.htaccess`:**

- Content-Security-Policy (NFR12)
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- Referrer-Policy: strict-origin-when-cross-origin

### Cross-Component Dependencies

```
Astro Build ──→ Static HTML/CSS/JS ──→ Hostinger public_html/
                                        ├── index.html
                                        ├── api/submit.php ──→ env.php (secrets)
                                        │                  ──→ SMTP2GO API
                                        ├── .htaccess (headers, rewrites)
                                        └── assets/ (images, fonts, icons)
```

## Implementation Patterns & Consistency Rules

### Naming Conventions

| Category         | Pattern                 | Example                                           |
| ---------------- | ----------------------- | ------------------------------------------------- |
| Astro components | PascalCase `.astro`     | `HeroSection.astro`, `LeadForm.astro`             |
| CSS files        | kebab-case `.css`       | `design-tokens.css`, `global.css`                 |
| CSS custom props | `--{category}-{name}`   | `--color-primary`, `--font-heading`, `--space-lg` |
| Data files       | kebab-case `.json`      | `testimonials.json`, `services.json`              |
| PHP files        | kebab-case `.php`       | `submit.php`, `env.php`                           |
| PHP variables    | `$snake_case`           | `$api_key`, `$form_data`                          |
| PHP functions    | `snake_case()`          | `send_lead_notification()`, `validate_phone()`    |
| Image assets     | kebab-case, descriptive | `chimney-before-after-01.webp`, `csia-badge.svg`  |
| HTML IDs/classes | kebab-case              | `lead-form`, `trust-strip`, `services-grid`       |

### Component Organization

```
src/components/
  sections/          ← Page sections (HeroSection, ServicesGrid, Testimonials)
  ui/                ← Reusable elements (Button, PhoneLink, Badge)
  forms/             ← Form-specific (LeadForm, FormConfirmation)
  layout/            ← Header, Footer, StickyCallBar
```

**Rule:** One component per file. Section components map 1:1 to page sections. UI components are reusable.

### CSS Architecture

```css
/* design-tokens.css — Single source of truth */
:root {
  /* Colors */
  --color-primary: #...;
  --color-primary-dark: #...;
  --color-text: #...;
  --color-bg: #...;

  /* Typography */
  --font-heading: '...', sans-serif;
  --font-body: '...', sans-serif;
  --font-size-hero: clamp(2rem, 5vw, 3.5rem);

  /* Spacing */
  --space-xs: 0.25rem;
  --space-sm: 0.5rem;
  --space-md: 1rem;
  --space-lg: 2rem;
  --space-xl: 4rem;
}
```

**Rule:** No hardcoded colors, fonts, or spacing in components. Always use tokens. Brand changes come from one file.

### PHP Form Handler Patterns

```php
// Standard response format
function json_response(bool $success, string $message, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}
```

**Rules:**

- Always return JSON (`Content-Type: application/json`)
- Standard structure: `{success: bool, message: string}`
- Validate and sanitize BEFORE any processing
- Never echo raw user input
- Always exit after response

### Error Handling

| Context              | Pattern                                                         |
| -------------------- | --------------------------------------------------------------- |
| PHP validation error | `json_response(false, 'Specific error message', 400)`           |
| SMTP2GO failure      | `json_response(false, 'Please call us at (555) 123-4567', 503)` |
| Rate limit exceeded  | `json_response(false, 'Please try again later', 429)`           |
| Client JS error      | Log to console, show phone fallback, never block page           |

### Image Standards

| Asset Type         | Format                      | Max Size                | Naming                        |
| ------------------ | --------------------------- | ----------------------- | ----------------------------- |
| Photos (hero, B/A) | WebP via Astro `<Image>`    | 100KB hero, 80KB others | `chimney-{context}-{nn}.webp` |
| Icons/logos        | SVG inline or sprite        | <5KB each               | `icon-{name}.svg`             |
| Cert badges        | SVG preferred, PNG fallback | <10KB                   | `badge-{org}.svg`             |
| Favicon            | ICO + SVG                   | <5KB                    | `favicon.ico`, `favicon.svg`  |

### Enforcement Guidelines

**All AI Agents MUST:**

1. Reference design tokens — never hardcode visual values
2. Use the naming conventions table for all new files
3. Return standard JSON format from PHP endpoints
4. Include `alt` text on every `<img>` and Astro `<Image>`
5. Test at 375px viewport first, then scale up

## Project Structure & Boundaries

### Complete Project Directory Structure

```
1st-class-chimney/
├── README.md
├── package.json
├── astro.config.mjs
├── tsconfig.json
├── .gitignore
├── .env.example                      ← Template for local dev
│
├── src/
│   ├── layouts/
│   │   └── BaseLayout.astro          ← Head, meta, GTM, fonts, global CSS
│   │
│   ├── pages/
│   │   └── index.astro               ← Landing page (assembles all sections)
│   │
│   ├── components/
│   │   ├── sections/
│   │   │   ├── HeroSection.astro     ← FR1: keyword-matched hero
│   │   │   ├── ServicesGrid.astro    ← FR2, FR3: services + secondary mention
│   │   │   ├── BeforeAfter.astro     ← FR4: proof imagery
│   │   │   ├── Testimonials.astro    ← FR5: customer testimonials
│   │   │   ├── TrustStrip.astro      ← FR6, FR7: certifications + guarantees
│   │   │   └── WhatsNext.astro       ← FR11, FR40: post-form timeline
│   │   │
│   │   ├── forms/
│   │   │   ├── LeadForm.astro        ← FR9, FR10: 3-field form + micro-copy
│   │   │   └── FormConfirmation.astro ← FR11: success state
│   │   │
│   │   ├── ui/
│   │   │   ├── Button.astro          ← Reusable CTA
│   │   │   ├── PhoneLink.astro       ← FR13: clickable phone
│   │   │   ├── Badge.astro           ← FR36: certification badge
│   │   │   └── Icon.astro            ← FR35, FR37: SVG icon wrapper
│   │   │
│   │   └── layout/
│   │       ├── Header.astro          ← FR41: logo + nav
│   │       ├── Footer.astro          ← Contact, legal
│   │       └── StickyCallBar.astro   ← FR12: mobile click-to-call
│   │
│   ├── styles/
│   │   ├── design-tokens.css         ← Colors, fonts, spacing
│   │   └── global.css                ← Resets, base styles
│   │
│   ├── data/
│   │   ├── services.json             ← Service grid content
│   │   ├── testimonials.json         ← Launch testimonials
│   │   └── business-info.json        ← Phone, address, hours
│   │
│   └── scripts/
│       └── form-handler.js           ← Client: POST to PHP, show confirmation
│
├── public/
│   ├── favicon.ico
│   ├── favicon.svg
│   ├── og-image.jpg                  ← FR26: Open Graph
│   ├── robots.txt
│   ├── images/
│   │   ├── hero/
│   │   ├── before-after/
│   │   └── icons/
│   └── fonts/
│
├── api/
│   ├── submit.php                    ← FR14-18, FR43-48: form + SMTP2GO
│   └── config.php                    ← Loads env.php, constants
│
├── .htaccess                         ← CSP, rewrites, caching
│
└── tests/
    └── e2e/
        └── form-submission.spec.ts   ← Playwright: form + email
```

### FR-to-File Mapping

| FR Category         | Primary Files                                                              | FRs     |
| ------------------- | -------------------------------------------------------------------------- | ------- |
| Content & Structure | `HeroSection`, `ServicesGrid`, `BeforeAfter`, `Testimonials`, `TrustStrip` | FR1-8   |
| Lead Capture        | `LeadForm`, `FormConfirmation`, `StickyCallBar`, `form-handler.js`         | FR9-13  |
| Email               | `api/submit.php`, `api/config.php`                                         | FR14-18 |
| Analytics           | `BaseLayout.astro` (GTM snippet)                                           | FR19-22 |
| SEO                 | `BaseLayout.astro` (meta), `astro.config.mjs` (sitemap)                    | FR23-26 |
| Responsive/Perf     | `design-tokens.css`, `astro.config.mjs`                                    | FR27-30 |
| Accessibility       | All components (semantic HTML, alt text, labels)                           | FR31-34 |
| Visual Design       | `icons/`, `Badge`, `Icon`, `WhatsNext`                                     | FR35-42 |
| Anti-Spam           | `api/submit.php`                                                           | FR43-48 |
| Click Fraud         | `form-handler.js` + GTM config                                             | FR49-53 |

### Architectural Boundaries

**Single boundary: Browser ↔ PHP API**

```
┌─────────────────────────┐     ┌──────────────────────┐
│  Static (Astro SSG)     │     │  Server (PHP)        │
│  index.html             │────▶│  api/submit.php      │
│  form-handler.js        │◀────│    → env.php         │
│  assets/*               │     │    → SMTP2GO API     │
└─────────────────────────┘     └──────────────────────┘
```

### External Integrations

| Service    | Integration Point               | Data Flow                                 |
| ---------- | ------------------------------- | ----------------------------------------- |
| SMTP2GO    | `api/submit.php` → REST API     | Lead data → notifications + autoresponder |
| GTM/GA4    | `BaseLayout.astro` → script tag | Page events → analytics                   |
| Google Ads | GTM → conversion tracking       | Form submit → conversion event            |

### Build & Deploy Flow

```
npm run build → dist/ (static HTML/CSS/JS)
                  ↓
          git push to GitHub
                  ↓
       Hostinger auto-deploy
                  ↓
    public_html/ (static + PHP + .htaccess)
```

## Architecture Validation Results

### Coherence Validation ✅

**Decision Compatibility:** All technology choices verified compatible — Astro 5.17 SSG + TypeScript strict + vanilla CSS + PHP form handler + Hostinger Apache. No version conflicts.

**Pattern Consistency:** Naming conventions (kebab-case files, PascalCase components, snake_case PHP) are internally consistent and match Astro/PHP ecosystem standards.

**Structure Alignment:** Project directory maps 1:1 to architectural decisions. Component organization follows the established section/ui/forms/layout pattern.

### Requirements Coverage ✅

**All 53 Functional Requirements mapped:**

| FR Range | Component                                                             | ✅      |
| -------- | --------------------------------------------------------------------- | ------- |
| FR1-8    | Section components (Hero, Services, BeforeAfter, Testimonials, Trust) | Covered |
| FR9-13   | LeadForm, FormConfirmation, StickyCallBar, PhoneLink                  | Covered |
| FR14-18  | api/submit.php → SMTP2GO                                              | Covered |
| FR19-22  | BaseLayout (GTM)                                                      | Covered |
| FR23-26  | BaseLayout (meta), astro.config.mjs (sitemap)                         | Covered |
| FR27-30  | design-tokens.css, Astro Image                                        | Covered |
| FR31-34  | Cross-cutting: all components                                         | Covered |
| FR35-42  | Icon, Badge, WhatsNext, image assets                                  | Covered |
| FR43-48  | api/submit.php (4-layer anti-spam)                                    | Covered |
| FR49-53  | form-handler.js + GTM config                                          | Covered |

**All 34 Non-Functional Requirements addressed:**

- NFR1-7 (Performance): Astro SSG, Image optimization, CDN, design tokens
- NFR8-14 (Security): env.php, .htaccess CSP, input sanitization, rate limiting
- NFR15-18 (Reliability): Graceful degradation, phone fallback on SMTP2GO failure
- NFR19-24 (Maintainability): Component structure, naming conventions, one-file tokens
- NFR25-30 (SEO): Meta tags, schema markup, sitemap, heading hierarchy
- NFR31-34 (Accessibility): Semantic HTML, ARIA, keyboard navigation, focus management

### Implementation Readiness ✅

- [x] Framework version locked (Astro 5.17.3)
- [x] Init command documented
- [x] File tree is file-level specific (every file named)
- [x] FR traceability complete (53/53 mapped)
- [x] PHP handler patterns with code examples
- [x] CSS token architecture documented
- [x] Naming conventions table (9 categories)
- [x] Build + deploy pipeline end-to-end

### Gap Analysis

**Critical Gaps:** None.

**Minor Items (non-blocking):**

1. Astro `dist/` output + PHP `api/` folder deploy coordination — document in first story
2. No CI pipeline — MVP uses local build + git push; CI is Growth Phase
3. Playwright tests need local PHP dev server — document in test setup

### Readiness Assessment

**Status:** ✅ READY FOR IMPLEMENTATION

**Confidence:** HIGH — Low-complexity static site with single PHP endpoint. Mirrors proven patterns from existing sites.

**Key Strengths:**

- Minimal moving parts, minimal failure modes
- Proven stack matching existing infrastructure
- Complete FR traceability to specific files
- Design token architecture for single-file brand changes

### Implementation Handoff

**First Step:**

```bash
npm create astro@latest ./ -- --template minimal --typescript strict --install --git
```

**AI Agent Guidelines:**

1. Follow all architectural decisions exactly as documented
2. Use implementation patterns consistently across all components
3. Respect project structure and component boundaries
4. Reference design tokens — never hardcode visual values
5. Refer to this document for all architectural questions
