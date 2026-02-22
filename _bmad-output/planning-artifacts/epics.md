---
stepsCompleted: ['step-01-validate', 'step-02-epics', 'step-03-stories', 'step-04-validation']
inputDocuments:
  - '_bmad-output/planning-artifacts/prd.md'
  - '_bmad-output/planning-artifacts/architecture.md'
  - 'stitch-assets/landing-page.html'
  - 'stitch-assets/landing-page-screenshot.png'
  - 'stitch-assets/images/'
  - '_bmad-output/planning-artifacts/landing-page-strategy.md'
  - '_bmad-output/planning-artifacts/cro-playbook.md'
---

# 1st Class Chimney & Air Duct - Epic Breakdown

## Overview

This document provides the complete epic and story breakdown for 1st Class Chimney & Air Duct, decomposing the requirements from the PRD, Architecture, and Stitch design assets into implementable stories.

## Requirements Inventory

### Functional Requirements

- **FR1:** Visitor can see a keyword-matched hero section with headline, sub-headline, and trust indicators above the fold
- **FR2:** Visitor can view a services grid showing all chimney services offered
- **FR3:** Visitor can see a secondary mention for air duct and dryer vent cleaning
- **FR4:** Visitor can view before/after proof imagery demonstrating work quality
- **FR5:** Visitor can read specific, detailed customer testimonials
- **FR6:** Visitor can see business credentials (CSIA/NFI certifications, insurance, background checks)
- **FR7:** Visitor can view trust guarantees (no-surprise pricing, clean-home guarantee, same-week availability)
- **FR8:** Visitor can navigate to page sections via deep-link anchors (`/#section`)
- **FR9:** Visitor can submit a lead form with name, phone number, and optional message
- **FR10:** Visitor can see conversion-focused micro-copy explaining what happens after submission
- **FR11:** Visitor can see a post-submission confirmation with visual timeline (submit → call → arrive)
- **FR12:** Visitor can initiate a phone call via sticky click-to-call bar on mobile
- **FR13:** Visitor can see an above-fold phone number for immediate calling
- **FR14:** System can send a branded lead notification to 3 business email addresses
- **FR15:** System can send a branded autoresponder to the homeowner
- **FR16:** Lead notification can display name, phone, message, and service type
- **FR17:** Lead notification can render with clickable phone number for mobile callback
- **FR18:** Autoresponder can set expectations for callback timing and process
- **FR19:** Page can include a GTM container slot
- **FR20:** System can fire a GTM event upon form submission
- **FR21:** Page can pass UTM parameters through to lead data
- **FR22:** Page can support deep-link anchors for ad campaign targeting
- **FR23:** Page can render with keyword-optimized title tag and meta description
- **FR24:** Page can include LocalBusiness structured data (JSON-LD)
- **FR25:** Page can generate a sitemap automatically
- **FR26:** Page can render with Open Graph tags for social sharing
- **FR27:** Page can render mobile-first (375px-428px primary)
- **FR28:** Page can render responsively across tablet (768px) and desktop (1280px+)
- **FR29:** Page can optimize images using WebP, srcset, and lazy loading
- **FR30:** Page can render with zero client-side JS by default (Astro islands)
- **FR31:** Visitor can navigate the page and submit the form using only keyboard
- **FR32:** Visitor can perceive all content with 4.5:1 minimum contrast
- **FR33:** Visitor can understand all images through descriptive alt text
- **FR34:** Form can display visible labels (not placeholder-only)
- **FR35:** Page can display service-specific icons in the services grid
- **FR36:** Page can display credential/certification badge icons in the trust strip
- **FR37:** Page can display guarantee shield icons alongside guarantee claims
- **FR38:** Page can display a hero visual directing attention toward the form
- **FR39:** Page can display section dividers or background patterns reinforcing brand
- **FR40:** Page can display a "What Happens Next" timeline with step icons
- **FR41:** Page can display a branded favicon and logo in the header
- **FR42:** Page can display review source icons (Google, Yelp) alongside testimonials
- **FR43:** Form can include a honeypot field invisible to real users
- **FR44:** Form can measure time-to-submit and flag submissions under 3 seconds
- **FR45:** System can validate phone number format (10-digit US)
- **FR46:** System can sanitize all form inputs to prevent injection attacks
- **FR47:** System can rate-limit submissions from the same IP (max 3/hour)
- **FR48:** Form can include an invisible challenge (e.g., Cloudflare Turnstile) blocking bots without user friction
- **FR49:** Page can detect known bot/datacenter user agents without blocking real browsers
- **FR50:** Page can implement basic fingerprinting to identify repeated fraud clicks
- **FR51:** System can log suspicious patterns (rapid repeats, zero scroll, zero time-on-page)
- **FR52:** Page can exclude known invalid traffic via GTM/GA4 referrer filtering
- **FR53:** System preserves all legitimate user paths — no CAPTCHA, no interstitials, no friction on load or scroll _(guardrail)_

### NonFunctional Requirements

- **NFR1:** Page fully interactive within 2s on 4G mobile (Lighthouse TTI)
- **NFR2:** LCP under 1.5s (Lighthouse)
- **NFR3:** CLS below 0.1 (Lighthouse)
- **NFR4:** Initial page weight under 500KB (DevTools Network)
- **NFR5:** Form submission acknowledgment within 500ms (Network waterfall)
- **NFR6:** SMTP2GO API response within 3s p95 (API monitoring)
- **NFR7:** Zero render-blocking JS in critical path (Lighthouse audit)
- **NFR8:** All form data over HTTPS TLS 1.2+ (SSL Labs)
- **NFR9:** Inputs sanitized against XSS/injection client + server (Pen test)
- **NFR10:** SMTP2GO API key server-side only, never in client bundle (Source audit)
- **NFR11:** Rate limiting per IP max 3 submissions/hour (Load test)
- **NFR12:** CSP headers preventing unauthorized scripts (SecurityHeaders.com)
- **NFR13:** No PII stored on static site — all data through SMTP2GO (Architecture audit)
- **NFR14:** SPF/DKIM/DMARC configured for sending domain (MXToolbox)
- **NFR15:** 99.9% uptime for static page (Hosting SLA + uptime monitor)
- **NFR16:** Graceful form degradation — show phone if SMTP2GO fails (Fault injection)
- **NFR17:** Email delivery > 98% for lead notifications (SMTP2GO dashboard)
- **NFR18:** Page renders correctly if GTM scripts fail (Script-blocking test)
- **NFR19:** SMTP2GO supports 3 simultaneous recipients (Integration test)
- **NFR20:** GTM loads asynchronously without blocking render (Lighthouse)
- **NFR21:** Ads conversion tracking fires within 500ms of submit (GTM debug mode)
- **NFR22:** JSON-LD validates without errors (Rich Results Test)
- **NFR23:** Content updates deployable within 10 minutes (Deployment test)
- **NFR24:** Design tokens centralized — brand changes from one file (Code review)
- **NFR25:** Zero vendor lock-in — SMTP2GO replaceable in 2 hours (Architecture audit)
- **NFR26:** Brand logo crisp at all viewports — SVG, min 150px width (Visual QA)
- **NFR27:** Certification badges (CSIA, NFI, BBB) min 48x48px, recognizable (Mobile visual QA)
- **NFR28:** Trust icons use consistent line weight/color from design tokens (Design review)
- **NFR29:** Service grid icons — same style family, stroke, size ratio (Side-by-side check)
- **NFR30:** Before/after images min 600px wide on desktop, no compression artifacts (Image audit)
- **NFR31:** Icons/logos include hover state or subtle animation (Interaction QA)
- **NFR32:** Review source logos (Google, Yelp) recognizable, within brand guidelines (Brand compliance)
- **NFR33:** Hero visual maintains quality across breakpoints — no pixelation/cropping (Responsive QA)
- **NFR34:** SVG for icons/logos, WebP for photos, PNG fallback (Asset format audit)

### Additional Requirements

**From Architecture:**

- Starter template: `npm create astro@latest ./ -- --template minimal --typescript strict --install --git` (Astro 5.17.3)
- PHP form handler at `api/submit.php` with `config.php` loading `env.php` for SMTP2GO secrets
- `.htaccess` for CSP headers, URL rewrites, and cache control
- Vanilla CSS with design tokens from Stitch (no Tailwind in production)
- Build output: `npm run build` → `dist/` → GitHub push → Hostinger auto-deploy
- Hybrid deploy: static HTML from Astro + PHP files in `api/` directory

**From Stitch Design (UX):**

- Dark premium theme: stone (#121212) base, charcoal (#1A1A1A/#2D2D2D) cards, gold (#D4AF37) accents
- Typography: Montserrat (display/body), Playfair Display (serif accents — service titles)
- Icons: Material Symbols Outlined (consistent throughout)
- Gold gradient: 135deg #D4AF37 → #F4D35E → #D4AF37 (logo, buttons, CTAs)
- Gold glow shadow: `0 4px 20px -5px rgba(212,175,55,0.3)` on CTAs
- Sticky header with backdrop blur, 80px height
- Before/after interactive slider with gold divider line
- Testimonials: horizontal snap-scroll carousel, 450px card width
- Contact form: charcoal card with gold top border accent, rounded-xl
- Mobile sticky CTA: fixed bottom bar with gold "Call Now" button
- 9 reference images provided (hero-bg, technician, 3 services, before-duct, 3 testimonial portraits)

**Form Field Resolution (2026 UX Best Practice):**

- PRD specifies: Name, Phone, Service (3 fields) — optimized for local service conversion
- Stitch shows: First/Last Name, Email, Service dropdown (4 fields)
- **Decision: Use PRD's 3-field approach** — phone number is critical for immediate callback in local services. Email captured in autoresponder flow. Fewer fields = higher conversion rate. This is the 2026 gold standard for high-intent local service landing pages.

### FR Coverage Map

| FR   | Epic   | Description                     |
| ---- | ------ | ------------------------------- |
| FR1  | Epic 2 | Keyword-matched hero section    |
| FR2  | Epic 2 | Services grid                   |
| FR3  | Epic 2 | Secondary services mention      |
| FR4  | Epic 2 | Before/after proof imagery      |
| FR5  | Epic 2 | Customer testimonials           |
| FR6  | Epic 2 | Business credentials            |
| FR7  | Epic 2 | Trust guarantees                |
| FR8  | Epic 2 | Deep-link anchors               |
| FR9  | Epic 3 | Lead form submission            |
| FR10 | Epic 3 | Conversion micro-copy           |
| FR11 | Epic 3 | Post-submission confirmation    |
| FR12 | Epic 3 | Sticky click-to-call            |
| FR13 | Epic 3 | Above-fold phone number         |
| FR14 | Epic 3 | Lead notification to 3 emails   |
| FR15 | Epic 3 | Branded autoresponder           |
| FR16 | Epic 3 | Lead notification content       |
| FR17 | Epic 3 | Clickable phone in notification |
| FR18 | Epic 3 | Autoresponder expectations      |
| FR19 | Epic 4 | GTM container slot              |
| FR20 | Epic 4 | GTM form submission event       |
| FR21 | Epic 4 | UTM parameter passthrough       |
| FR22 | Epic 4 | Deep-link anchor ad targeting   |
| FR23 | Epic 4 | SEO title/meta description      |
| FR24 | Epic 4 | LocalBusiness JSON-LD           |
| FR25 | Epic 4 | Auto-generated sitemap          |
| FR26 | Epic 4 | Open Graph tags                 |
| FR27 | Epic 5 | Mobile-first rendering          |
| FR28 | Epic 5 | Responsive breakpoints          |
| FR29 | Epic 5 | Image optimization              |
| FR30 | Epic 5 | Zero client-side JS             |
| FR31 | Epic 5 | Keyboard navigation             |
| FR32 | Epic 5 | 4.5:1 contrast ratio            |
| FR33 | Epic 5 | Descriptive alt text            |
| FR34 | Epic 5 | Visible form labels             |
| FR35 | Epic 2 | Service-specific icons          |
| FR36 | Epic 2 | Certification badges            |
| FR37 | Epic 2 | Guarantee shield icons          |
| FR38 | Epic 2 | Hero visual                     |
| FR39 | Epic 1 | Section dividers/patterns       |
| FR40 | Epic 2 | "What Happens Next" timeline    |
| FR41 | Epic 1 | Branded favicon and logo        |
| FR42 | Epic 2 | Review source icons             |
| FR43 | Epic 3 | Honeypot field                  |
| FR44 | Epic 3 | Time-to-submit check            |
| FR45 | Epic 3 | Phone format validation         |
| FR46 | Epic 3 | Input sanitization              |
| FR47 | Epic 3 | Rate limiting                   |
| FR48 | Epic 3 | Invisible bot challenge         |
| FR49 | Epic 6 | Bot/datacenter detection        |
| FR50 | Epic 6 | Fingerprinting                  |
| FR51 | Epic 6 | Suspicious pattern logging      |
| FR52 | Epic 6 | Invalid traffic filtering       |
| FR53 | Epic 6 | Zero-friction guardrail         |

## Epic List

### Epic 1: Project Foundation & Design System

Development environment is ready and branded design tokens are established — every subsequent component inherits the premium dark gold aesthetic from day one.
**FRs covered:** FR39, FR41
**NFRs covered:** NFR24, NFR26, NFR28-29, NFR34
**Includes:** Astro init, design-tokens.css, global.css, BaseLayout.astro, Header.astro, Footer.astro, favicon, font loading, .htaccess base config

### Epic 2: Landing Page Content & Visual Experience

Visitor lands on a premium, trust-building page with hero, services, proof imagery, testimonials, and trust credentials — everything needed to make the decision to contact.
**FRs covered:** FR1-8, FR35-38, FR40, FR42
**NFRs covered:** NFR1-4, NFR7, NFR26-27, NFR30-33
**Includes:** HeroSection, ServicesGrid, BeforeAfter slider, Testimonials carousel, TrustStrip, WhatsNext timeline, PhoneLink, Badge, Icon, all image assets

### Epic 3: Lead Capture & Form Submission

Visitor can submit a lead form (name, phone, service) and see confirmation, or call directly via sticky mobile CTA — the business receives the lead instantly via SMTP2GO.
**FRs covered:** FR9-18, FR43-48
**NFRs covered:** NFR5-6, NFR8-11, NFR13-14, NFR16-17
**Includes:** LeadForm, FormConfirmation, StickyCallBar, form-handler.js, api/submit.php, api/config.php, env.php, honeypot, phone validation, rate limiting, SMTP2GO integration

### Epic 4: SEO, Analytics & Conversion Tracking

Page is discoverable via Google, tracks visitor behavior through GTM, and attributes Google Ads conversions back to form submissions.
**FRs covered:** FR19-26
**NFRs covered:** NFR15, NFR18, NFR20-22
**Includes:** GTM snippet, conversion event firing, UTM passthrough, meta tags, JSON-LD, @astrojs/sitemap, OG tags, robots.txt

### Epic 5: Responsive Polish & Accessibility

Page works flawlessly on every device from 375px to 1440px+ and is fully usable by visitors with disabilities or assistive technology.
**FRs covered:** FR27-34
**NFRs covered:** NFR1-3, NFR4, NFR7
**Includes:** Mobile-first breakpoint tuning, keyboard nav, ARIA, contrast verification, alt text, visible labels, image optimization, Lighthouse pass

### Epic 6: Click Fraud Protection & Deployment

Page is shielded from ad fraud, deployed live on Hostinger via GitHub, and verified end-to-end with all systems working.
**FRs covered:** FR49-53
**NFRs covered:** NFR12, NFR15, NFR23, NFR25
**Includes:** Bot detection, fingerprinting, suspicious logging, GTM filtering, CSP finalization, GitHub → Hostinger deploy, Playwright E2E tests, production verification

---

## Epic 1: Project Foundation & Design System

Development environment is ready and branded design tokens are established — every subsequent component inherits the premium dark gold aesthetic from day one.

### Story 1.1: Initialize Astro Project & Base Configuration

As a **developer**,
I want an initialized Astro 5.17 project with TypeScript strict mode, proper directory structure, and `.gitignore`,
So that all subsequent component work has a solid, standards-compliant foundation.

**Acceptance Criteria:**

**Given** a clean project directory
**When** `npm create astro@latest ./ -- --template minimal --typescript strict --install --git` is run
**Then** `package.json` exists with Astro 5.17.x dependency
**And** `tsconfig.json` has `"strict": true`
**And** directory structure matches architecture: `src/layouts/`, `src/pages/`, `src/components/sections/`, `src/components/forms/`, `src/components/ui/`, `src/components/layout/`, `src/styles/`, `src/data/`, `src/scripts/`, `public/images/`, `api/`
**And** `.env.example` exists with SMTP2GO placeholder keys
**And** `.gitignore` includes `node_modules/`, `dist/`, `.env`

### Story 1.2: Design Token System & Global Styles

As a **developer**,
I want a centralized design-tokens.css and global.css that encode the brand's dark gold aesthetic,
So that every component inherits consistent colors, typography, spacing, and effects from a single source of truth (FR39, NFR24).

**Acceptance Criteria:**

**Given** the Astro project is initialized
**When** `design-tokens.css` is created
**Then** it defines CSS custom properties for: `--color-primary: #D4AF37`, `--color-stone: #121212`, `--color-charcoal: #1A1A1A`, `--color-navy: #0B132B`, `--color-text-light`, `--color-text-muted`
**And** typography tokens define Montserrat (body/display) and Playfair Display (serif accents)
**And** gold gradient is defined as `--gradient-gold: linear-gradient(135deg, #D4AF37, #F4D35E, #D4AF37)`
**And** gold glow shadow is defined as `--shadow-gold-glow: 0 4px 20px -5px rgba(212,175,55,0.3)`
**And** spacing scale, border-radius tokens, and breakpoint variables are defined
**And** `global.css` imports design-tokens.css, applies CSS reset, base typography, and utility classes
**And** all visual values reference tokens — zero hardcoded colors or fonts (NFR24)

### Story 1.3: BaseLayout with Head, Fonts & Meta Shell

As a **developer**,
I want a BaseLayout.astro that sets up `<head>` with font loading, viewport meta, and slots for page-specific meta,
So that every page inherits correct document structure, fonts render immediately, and SEO meta can be injected per-page.

**Acceptance Criteria:**

**Given** design tokens are established
**When** BaseLayout.astro is created
**Then** `<html lang="en">` wraps the document
**And** `<meta charset="UTF-8">` and `<meta name="viewport" content="width=device-width, initial-scale=1.0">` are present
**And** Montserrat and Playfair Display fonts are loaded (preconnect + stylesheet or self-hosted)
**And** `design-tokens.css` and `global.css` are imported
**And** a `<slot />` allows page content injection
**And** props accept `title`, `description`, `ogImage` for per-page meta
**And** page renders with correct fonts at all viewports (NFR26)

### Story 1.4: Header & Footer Layout Components

As a **visitor**,
I want a branded header with logo and navigation, and a footer with business info and legal links,
So that I can identify the business and navigate the page (FR41).

**Acceptance Criteria:**

**Given** BaseLayout is complete
**When** Header.astro is created
**Then** it displays a brand logo (icon + "1ST CLASS" text) using design tokens
**And** navigation links to `#services`, `#reviews`, `#contact` sections
**And** phone number is displayed on desktop with gold icon (FR13 prep)
**And** a "Get Inspection" CTA button uses `--color-primary` background and `--shadow-gold-glow`
**And** header is sticky with `backdrop-blur` and `border-bottom: 1px solid rgba(255,255,255,0.05)`
**And** header height is 80px

**Given** BaseLayout is complete
**When** Footer.astro is created
**Then** it displays a 4-column grid: brand + description, services links, company links, certifications
**And** copyright year is dynamic
**And** footer uses `--color-stone` background and `--color-text-muted` for text
**And** social media icons have hover state changing to `--color-primary` (NFR31)

### Story 1.5: Favicon, .htaccess & Landing Page Shell

As a **visitor**,
I want to see a branded favicon in my browser tab and fast page loads (FR41),
So that the business appears professional before I even read the content.

**Acceptance Criteria:**

**Given** Header and Footer are complete
**When** `public/favicon.svg` and `public/favicon.ico` are created
**Then** favicon uses the gold brand color and shield/verified icon motif
**And** BaseLayout references both favicon formats

**Given** the page needs to be served on Hostinger
**When** `.htaccess` is created
**Then** it includes basic cache-control headers for static assets (1 year for images, 1 week for HTML)
**And** it includes URL rewrite rules for clean paths
**And** it includes a stub CSP header (to be finalized in Epic 6)

**Given** all foundation components exist
**When** `src/pages/index.astro` is created
**Then** it imports BaseLayout, Header, and Footer
**And** it renders an empty page shell with correct `<title>` and placeholder `<meta description>`
**And** the page builds successfully with `npm run build`
**And** output in `dist/` contains `index.html` with inlined CSS

---

## Epic 2: Landing Page Content & Visual Experience

Visitor lands on a premium, trust-building page with hero, services, proof imagery, testimonials, and trust credentials — everything needed to make the decision to contact.

### Story 2.1: Hero Section with Trust Indicators

As a **visitor arriving from a Google Ad**,
I want to see a powerful headline, trust indicators, and a clear CTA above the fold,
So that I immediately know this is a credible, local chimney company worth contacting (FR1, FR38).

**Acceptance Criteria:**

**Given** the landing page loads
**When** HeroSection.astro renders
**Then** h1 displays "ELITE LEVEL PROTECTION" with gold gradient on "PROTECTION"
**And** sub-headline reads brand-appropriate copy with left gold border accent
**And** trust indicators show: "Certified & Insured", "5-Star Rated", "Background Checked" with gold checkmark icons
**And** primary CTA "Get Free Inspection" button uses `--color-primary` + `--shadow-gold-glow`
**And** secondary CTA "Watch Process" button has ghost/outline style
**And** hero background image displays right-aligned, grayscale with opacity overlay
**And** "Available Today" status badge appears on technician image card (desktop only)
**And** section minimum height is 85vh
**And** hero visual directs eye-flow toward the CTA (FR38)

### Story 2.2: "Why Choose Us" Differentiators Section

As a **visitor evaluating options**,
I want to see 4 clear reasons to choose 1st Class over competitors,
So that I understand the specific advantages before scrolling to services (FR6, FR7).

**Acceptance Criteria:**

**Given** the visitor scrolls past the hero
**When** the differentiators section renders
**Then** section heading displays "Why Choose 1st Class?"
**And** 4 differentiator cards display: Technician Excellence, Video Verification, Upfront Pricing, Health Focused
**And** each card has a Material Symbols icon in gold on dark background
**And** cards use `--color-charcoal` background
**And** layout is 2-column on desktop, single column on mobile
**And** icons use consistent line weight from design tokens (NFR28)

### Story 2.3: Services Grid with Icons & Badge

As a **visitor wanting to know what services are offered**,
I want to see service cards for chimney sweep, air duct cleaning, and dryer vent repair,
So that I can identify the service I need (FR2, FR3, FR35).

**Acceptance Criteria:**

**Given** the services section renders (id="services")
**When** ServicesGrid.astro loads
**Then** section displays "Our Expertise" label + "Comprehensive Home Care" heading + gold divider
**And** 3 service cards display: Chimney Sweep (with "Signature Service" badge), Air Duct Cleaning, Dryer Vent Repair
**And** each card has an image with hover zoom transition (scale 1.1, 700ms)
**And** card titles use Playfair Display serif font
**And** "Learn More" links with gold arrow icons appear on each card
**And** service-specific icons from Material Symbols are used (FR35)
**And** icons use same style family, stroke width, and size ratio (NFR29)
**And** chimney services are primary; air duct/dryer vent are secondary (FR3)
**And** service data is sourced from `src/data/services.json`

### Story 2.4: Before/After Interactive Slider

As a **visitor who needs proof of quality**,
I want to see before/after comparison imagery with an interactive slider,
So that I can visually verify the quality of work (FR4).

**Acceptance Criteria:**

**Given** the before/after section renders
**When** BeforeAfter.astro loads
**Then** a split-view shows "Before" (red label, left) and "After" (green label, right) images
**And** a gold divider line with compare arrows handle sits at 50% by default
**And** "Drag to Compare" tooltip appears on hover
**And** right panel displays "Real Results" heading + "The Invisible Danger Lurking in Your Walls" copy
**And** benefit cards show: "Fire Hazard Reduction" (red warning icon) + "Energy Efficiency" (gold eco icon)
**And** images are minimum 600px wide on desktop with no compression artifacts (NFR30)
**And** slider interaction works via mouse drag and touch

### Story 2.5: Customer Testimonials Carousel

As a **visitor seeking social proof**,
I want to read real customer testimonials with names, photos, and ratings,
So that I trust the business based on peer experience (FR5, FR42).

**Acceptance Criteria:**

**Given** the testimonials section renders (id="reviews")
**When** Testimonials.astro loads
**Then** section shows "Client Testimonials" heading with "4.9" aggregate rating and 5 gold stars
**And** "Based on 500+ reviews" subtext displays
**And** 3 testimonial cards scroll horizontally with snap-scroll behavior
**And** each card shows: 5-star rating, quoted review text (italic), customer photo (48px circle, gold border), name, and title
**And** cards are 450px wide on desktop, full-width on mobile
**And** review source icons (Google, Yelp) appear alongside testimonials (FR42)
**And** testimonial data is sourced from `src/data/testimonials.json`
**And** review source logos are recognizable within brand guidelines (NFR32)

### Story 2.6: Trust Strip with Certification Badges

As a **visitor checking credentials**,
I want to see professional certifications and trust guarantees,
So that I feel safe hiring this company (FR6, FR7, FR36, FR37).

**Acceptance Criteria:**

**Given** the trust strip section renders
**When** TrustStrip.astro loads
**Then** certification badges display: CSIA, NFI/NADCA (minimum 48x48px, NFR27)
**And** guarantee shields display: no-surprise pricing, clean-home guarantee, same-week availability
**And** each guarantee uses a shield icon from Material Symbols (FR37)
**And** badges have hover state with opacity/grayscale transition (NFR31)
**And** trust icons use consistent line weight and colors from design tokens (NFR28)

### Story 2.7: "What Happens Next" Timeline & Section Navigation

As a **visitor about to submit the form**,
I want to see a visual timeline of what happens after contact,
So that I know exactly what to expect (FR40, FR8).

**Acceptance Criteria:**

**Given** the page content is complete
**When** WhatsNext.astro renders
**Then** a 3-step visual timeline displays: Submit → Call → Arrive
**And** each step has an icon, title, and brief description
**And** timeline uses gold connecting lines and step icons
**And** section dividers/background patterns reinforce brand identity (FR39)

**Given** all sections have been created
**When** anchor IDs are verified
**Then** `#services`, `#reviews`, `#contact` anchors resolve to correct sections (FR8)
**And** header nav links scroll smoothly to each section
**And** deep-link URLs (e.g., `/#services`) scroll to correct position on page load (FR22 prep)
**And** business info data is sourced from `src/data/business-info.json`

---

## Epic 3: Lead Capture & Form Submission

Visitor can submit a lead form (name, phone, service) and see confirmation, or call directly via sticky mobile CTA — the business receives the lead instantly via SMTP2GO.

### Story 3.1: Lead Form with 3-Field Layout & Micro-Copy

As a **visitor ready to request service**,
I want a simple form with name, phone, and service dropdown,
So that I can request an inspection in under 15 seconds (FR9, FR10, FR34).

**Acceptance Criteria:**

**Given** the contact section renders (id="contact")
**When** LeadForm.astro loads
**Then** form displays 3 fields: Full Name (text), Phone Number (tel), Service Needed (dropdown)
**And** service dropdown includes: Chimney Inspection & Sweep, Air Duct Cleaning, Dryer Vent Repair, Other Inquiry
**And** all fields have visible `<label>` elements (not placeholder-only, FR34)
**And** labels are gold uppercase tracking-wider text
**And** inputs use `--color-stone` background with `--color-charcoal` border
**And** inputs show gold focus ring on focus
**And** "Submit Request" button spans full width with `--color-primary` background and gold glow
**And** privacy micro-copy shows "🔒 Your privacy is guaranteed. No spam." below button (FR10)
**And** form card has charcoal background with gold top border accent
**And** left panel shows phone, email, and address contact info

### Story 3.2: Client-Side Form Handler & Validation

As a **visitor submitting the form**,
I want instant validation and a smooth submission experience,
So that I know my request went through without page reload (FR9, FR45).

**Acceptance Criteria:**

**Given** the visitor fills out the lead form
**When** they click "Submit Request"
**Then** `form-handler.js` intercepts the submit event (no page reload)
**And** client-side validation checks: name is non-empty, phone matches 10-digit US format (FR45)
**And** if validation fails, inline error messages appear below the invalid field
**And** if validation passes, form data is POSTed to `api/submit.php` as JSON
**And** submit button shows loading state during request
**And** UTM parameters from URL are captured and included in form payload (FR21)
**And** a timestamp is recorded on form load to enable time-to-submit checking (FR44 prep)
**And** form submission acknowledgment occurs within 500ms of server response (NFR5)

### Story 3.3: Form Confirmation & "What Happens Next"

As a **visitor who just submitted the form**,
I want to see a clear confirmation with next steps,
So that I know my request was received and when to expect a call (FR11).

**Acceptance Criteria:**

**Given** the form submission succeeds (200 response)
**When** FormConfirmation.astro state activates
**Then** the form is replaced by a confirmation message with checkmark icon
**And** a visual timeline shows: ✅ Request Received → 📞 We'll Call You → 🏠 We Arrive
**And** messaging sets expectation: "We'll call you within [X hours] to confirm your appointment"
**And** a "Call Now" fallback link is visible for impatient visitors
**And** confirmation uses brand colors (gold accents on dark background)

**Given** the form submission fails (network error or non-200)
**When** error state activates
**Then** an error message appears: "Something went wrong. Please call us directly."
**And** phone number is prominently displayed as clickable link (NFR16)
**And** the form remains visible so user can retry

### Story 3.4: Sticky Mobile Click-to-Call Bar

As a **mobile visitor**,
I want a persistent "Call Now" bar at the bottom of my screen,
So that I can call the business instantly without scrolling back to the top (FR12, FR13).

**Acceptance Criteria:**

**Given** the page loads on a viewport under 768px
**When** StickyCallBar.astro renders
**Then** a fixed bar appears at the bottom of the screen
**And** bar contains "Call Now for Quote" with phone icon
**And** bar links to `tel:` with the business phone number
**And** bar uses `--color-primary` background with `--color-stone` text
**And** bar has `z-index: 50` and `border-top` with gold accent
**And** bar is hidden on desktop (md:hidden)
**And** page content has bottom padding to prevent bar from covering footer content

### Story 3.5: PHP Form Handler with SMTP2GO Integration

As a **business owner**,
I want lead form submissions to send branded email notifications to my 3 email addresses and an autoresponder to the homeowner,
So that no lead is missed and the homeowner feels acknowledged (FR14-18).

**Acceptance Criteria:**

**Given** a valid form submission arrives at `api/submit.php`
**When** the PHP handler processes it
**Then** it loads configuration from `config.php` → `env.php` (SMTP2GO API key, recipients)
**And** SMTP2GO API key is server-side only, never exposed to client (NFR10)
**And** it sends a branded lead notification email to 3 configured recipients (FR14)
**And** notification includes: name, phone, message, service type, timestamp, UTM source (FR16)
**And** notification renders clickable phone number for mobile callback (FR17)
**And** it sends a branded autoresponder to the homeowner (using phone → email mapping if available, FR15)
**And** autoresponder sets expectations: callback timing, process overview, company credentials (FR18)
**And** both emails use brand design tokens (gold, dark theme, logo)
**And** API response is JSON: `{"success": true, "message": "..."}` or `{"success": false, "error": "..."}`
**And** SMTP2GO API response completes within 3s p95 (NFR6)
**And** all form data transmitted over HTTPS (NFR8)
**And** no PII is stored on the server — data passes through to SMTP2GO only (NFR13)

### Story 3.6: Anti-Spam Protection Layer

As a **system administrator**,
I want multi-layered spam protection on the form,
So that bot submissions don't flood the business inbox (FR43-48).

**Acceptance Criteria:**

**Given** the form is rendered
**When** honeypot field logic is applied
**Then** a hidden text field (CSS `display:none`) is included in the form (FR43)
**And** if the honeypot field has a value on submission, the server silently rejects it (200 response, no email sent)

**Given** a form submission arrives at the server
**When** time-to-submit is checked
**Then** submissions completed in under 3 seconds from page load are flagged and rejected (FR44)

**Given** a form submission arrives at the server
**When** input validation runs
**Then** phone number is validated as 10-digit US format (FR45)
**And** all inputs are sanitized against XSS and SQL injection (FR46, NFR9)
**And** HTML entities are escaped in all fields

**Given** the same IP submits multiple times
**When** rate limiting is checked
**Then** submissions beyond 3 per hour from the same IP are rejected with a friendly message (FR47, NFR11)

**Given** advanced bot protection is needed
**When** Cloudflare Turnstile (or equivalent) is configured
**Then** invisible challenge runs without user friction (FR48)
**And** legitimate users experience zero CAPTCHA, interstitials, or friction (FR53 guardrail)

---

## Epic 4: SEO, Analytics & Conversion Tracking

Page is discoverable via Google, tracks visitor behavior through GTM, and attributes Google Ads conversions back to form submissions.

### Story 4.1: GTM Container & Conversion Event Firing

As a **marketing manager**,
I want GTM integrated and conversion events firing on form submit,
So that I can track which ads and keywords drive leads (FR19, FR20, FR21).

**Acceptance Criteria:**

**Given** BaseLayout.astro is loaded
**When** the GTM snippet is added
**Then** GTM loads asynchronously without blocking render (NFR20)
**And** page renders correctly if GTM scripts fail to load (NFR18)
**And** a `dataLayer.push` event fires on successful form submission (FR20)
**And** the event includes: event name, service type, UTM source/medium/campaign (FR21)
**And** ads conversion tracking fires within 500ms of form submit (NFR21)
**And** deep-link anchors (`/#services`, `/#contact`) work for ad campaign targeting (FR22)

### Story 4.2: SEO Meta, Structured Data & Sitemap

As a **search engine crawler**,
I want optimized meta tags, LocalBusiness JSON-LD, and a sitemap,
So that the page ranks for local chimney service searches (FR23-26).

**Acceptance Criteria:**

**Given** the landing page is built
**When** SEO elements are added to BaseLayout
**Then** `<title>` contains primary keyword + location + brand (FR23)
**And** `<meta name="description">` contains compelling, keyword-rich description under 160 chars (FR23)
**And** Open Graph tags set: `og:title`, `og:description`, `og:image`, `og:url`, `og:type` (FR26)
**And** `og:image` points to `/og-image.jpg` (1200x630px) (FR26)
**And** LocalBusiness JSON-LD is valid and includes: name, phone, address, hours, ratings, services (FR24)
**And** JSON-LD validates without errors on Google Rich Results Test (NFR22)
**And** `@astrojs/sitemap` is installed and generates `sitemap-index.xml` on build (FR25)
**And** `robots.txt` allows all crawlers and references sitemap URL

---

## Epic 5: Responsive Polish & Accessibility

Page works flawlessly on every device from 375px to 1440px+ and is fully usable by visitors with disabilities or assistive technology.

### Story 5.1: Mobile-First Responsive Tuning

As a **mobile visitor on a 375px screen**,
I want every section to look premium and be fully usable,
So that I have the same quality experience as desktop visitors (FR27, FR28).

**Acceptance Criteria:**

**Given** the complete page is built
**When** viewed at 375px (iPhone SE)
**Then** hero section stacks vertically, CTA is full-width, technician image is hidden
**And** services grid displays single-column cards
**And** before/after slider is full-width and touch-draggable
**And** testimonials scroll horizontally one card at a time
**And** form fills full width in contact section
**And** footer stacks to single column
**And** no horizontal overflow at any breakpoint from 320px to 1440px+

**Given** the page is viewed at 768px (tablet)
**When** responsive breakpoints apply
**Then** services grid shows 2-column layout
**And** hero shows both text and technician image side-by-side

**Given** the page is viewed at 1280px+ (desktop)
**When** desktop layout applies
**Then** all sections match the Stitch design reference layout (FR28)

### Story 5.2: Image Optimization & Performance Pass

As a **visitor on a slow connection**,
I want the page to load fast with optimized images,
So that I don't bounce before seeing the content (FR29, FR30).

**Acceptance Criteria:**

**Given** the page uses multiple images
**When** Astro `<Image>` component is used for all photos
**Then** images output as WebP format with appropriate quality (FR29)
**And** `srcset` generates multiple sizes for responsive loading (FR29)
**And** below-fold images use `loading="lazy"` (FR29)
**And** hero image uses `loading="eager"` for LCP
**And** zero client-side JS in the default bundle (Astro islands, FR30)
**And** only `form-handler.js` loads as a client-side script (and only when needed)
**And** initial page weight is under 500KB (NFR4)
**And** LCP is under 1.5s (NFR2)
**And** CLS is below 0.1 (NFR3)
**And** TTI is under 2s on 4G mobile (NFR1)
**And** zero render-blocking JS in critical path (NFR7)

### Story 5.3: Accessibility Audit & Keyboard Navigation

As a **visitor using assistive technology**,
I want the page to be fully navigable and understandable,
So that I can access all information and submit the form (FR31-34).

**Acceptance Criteria:**

**Given** the page is loaded
**When** navigating with keyboard only
**Then** Tab order follows logical reading order through all sections (FR31)
**And** all interactive elements (links, buttons, form fields) have visible focus indicators
**And** form can be fully completed and submitted via keyboard (FR31)
**And** Skip-to-content link is the first focusable element

**Given** the page uses colors for meaning
**When** contrast is tested
**Then** all text meets 4.5:1 minimum contrast ratio (FR32)
**And** gold (#D4AF37) on dark backgrounds passes WCAG AA

**Given** the page contains images
**When** alt text is verified
**Then** all `<img>` elements have descriptive alt text (FR33)
**And** decorative images use `alt=""`
**And** hero background image has appropriate `role` and `aria-label`

**Given** the form is rendered
**When** accessibility is verified
**Then** all form fields have associated `<label>` elements (FR34)
**And** error messages are associated via `aria-describedby`
**And** form status changes announce to screen readers via `aria-live`

---

## Epic 6: Click Fraud Protection & Deployment

Page is shielded from ad fraud, deployed live on Hostinger via GitHub, and verified end-to-end with all systems working.

### Story 6.1: Click Fraud Detection & Logging

As a **business owner running Google Ads**,
I want invalid/fraudulent clicks detected and logged,
So that my ad budget isn't wasted on bots (FR49-52).

**Acceptance Criteria:**

**Given** the page loads
**When** click fraud detection initializes (in `form-handler.js`)
**Then** known bot/datacenter user agents are detected without blocking real browsers (FR49)
**And** basic fingerprinting identifies repeated visits from the same source (FR50)
**And** suspicious patterns are logged: rapid repeat visits, zero scroll, zero time-on-page (FR51)
**And** logs are sent to a lightweight endpoint or GTM custom event for analysis
**And** GTM/GA4 referrer filtering excludes known invalid traffic (FR52)
**And** all detection runs passively — zero CAPTCHA, interstitials, or friction for real users (FR53 guardrail)
**And** legitimate user paths are never blocked or delayed

### Story 6.2: CSP Headers, Security Hardening & .htaccess Finalization

As a **system administrator**,
I want production-grade security headers and finalized server configuration,
So that the site is protected against XSS, clickjacking, and unauthorized scripts (NFR12).

**Acceptance Criteria:**

**Given** the `.htaccess` stub from Epic 1 exists
**When** security headers are finalized
**Then** Content-Security-Policy header whitelists only: self, GTM, Google Ads, SMTP2GO, Google Fonts, Material Symbols (NFR12)
**And** `X-Content-Type-Options: nosniff` is set
**And** `X-Frame-Options: DENY` is set
**And** `Referrer-Policy: strict-origin-when-cross-origin` is set
**And** HSTS is enabled if HTTPS is configured
**And** cache headers finalized: immutable for hashed assets, short TTL for HTML
**And** SPF/DKIM/DMARC records are documented for SMTP2GO sending domain (NFR14)

### Story 6.3: Build, Deploy & Production Verification

As a **developer deploying to production**,
I want a verified build pipeline from local → GitHub → Hostinger,
So that the site is live and all systems are working end-to-end (NFR15, NFR23).

**Acceptance Criteria:**

**Given** all epics are complete
**When** `npm run build` is executed
**Then** `dist/` contains all static HTML, CSS, JS, and optimized images
**And** build completes without errors or warnings

**Given** static files need to coexist with PHP
**When** deployment to Hostinger is configured
**Then** `dist/` contents deploy to `public_html/`
**And** `api/` directory with PHP files deploys alongside static files
**And** `.htaccess` deploys to root
**And** `env.php` is manually configured on server (never in git)

**Given** the site is deployed
**When** production verification runs
**Then** landing page loads over HTTPS
**And** form submission sends lead notification to all 3 emails
**And** autoresponder sends to test homeowner
**And** GTM fires conversion event on form submit
**And** all images load without 404s
**And** mobile sticky CTA functions on mobile devices
**And** content updates are deployable within 10 minutes (NFR23)
**And** SMTP2GO could be replaced within 2 hours (NFR25 — documented)

### Story 6.4: E2E Test Suite

As a **developer maintaining the site**,
I want automated E2E tests verifying the critical conversion path,
So that regressions are caught before they reach production.

**Acceptance Criteria:**

**Given** Playwright is configured in `tests/e2e/`
**When** `form-submission.spec.ts` runs
**Then** it navigates to the landing page
**And** it verifies hero section renders with h1
**And** it fills out the lead form with test data
**And** it submits the form and verifies confirmation appears
**And** it verifies sticky CTA is visible on mobile viewport
**And** it verifies all anchor links navigate to correct sections
**And** it verifies no console errors on page load
