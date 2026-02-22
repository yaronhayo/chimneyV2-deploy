---
stepsCompleted:
  [
    'step-01-init',
    'step-02-discovery',
    'step-02b-vision',
    'step-02c-executive-summary',
    'step-03-success',
    'step-04-journeys',
    'step-05-domain',
    'step-06-innovation',
    'step-07-project-type',
    'step-08-scoping',
    'step-09-functional',
    'step-10-nonfunctional',
    'step-11-polish',
  ]
inputDocuments:
  - 'stitch-assets/landing-page.html'
  - 'stitch-assets/landing-page-screenshot.png'
  - 'stitch-assets/images/' # 9 production images
  - '_bmad-output/planning-artifacts/landing-page-strategy.md'
  - '_bmad-output/planning-artifacts/cro-playbook.md'
workflowType: 'prd'
documentCounts:
  briefs: 0
  research: 2
  brainstorming: 0
  projectDocs: 0
  designs: 1
classification:
  projectType: web_app
  subType: astro-static-site
  domain: local-home-services-marketing
  complexity: low-medium
  projectContext: greenfield
---

# Product Requirements Document - 1st Class Chimney & Air Duct

**Author:** Yaron
**Date:** 2026-02-20

## 1. Project Discovery

### Classification

| Dimension    | Value                                               |
| ------------ | --------------------------------------------------- |
| Project Type | Astro static/SSR marketing site with lead-gen forms |
| Domain       | Local Home Services — Chimney & Air Duct            |
| Complexity   | Low-Medium                                          |
| Context      | Greenfield — new build                              |

### Technical Decisions

- **Framework:** Astro (SSG/SSR)
- **Styling:** Design tokens extracted from Stitch mockup, evolved from there
- **Email:** SMTP2GO — leads sent to 3 email addresses + branded autoresponder to client
- **Analytics:** GTM container slot provided; user manages tracking tool installation
- **Design baseline:** Stitch landing page is a rough starting point, not a pixel-perfect spec

### Scope at Launch (MVP)

- **One landing page** focused on all chimney services as primary content
- Air duct cleaning + dryer vent cleaning positioned under "Other Services" secondary section
- Multi-lander architecture (separate `/chimney-cleaning`, `/chimney-repair` routes) deferred to post-launch iteration
- CRO playbook principles applied from day one (micro-reviews near CTAs, objection-killing micro-copy, form clarity)

### Email Integration

- **Provider:** SMTP2GO
- **Lead notification:** Sent to 3 configured email addresses
- **Client autoresponder:** Branded confirmation with "what happens next" messaging
- **Templates:** Both emails use brand design tokens (colors, logo, typography)

## 2. Product Vision

### Vision Statement

Build 1st Class Chimney & Air Duct's digital presence from zero — a high-converting Astro landing page that owns the lead pipeline, eliminates dependency on third-party aggregator platforms (Angi, Thumbtack, HomeAdvisor), and maximizes Google Ads ROAS through precision message-match and trust-first design.

### Core Insight

Homeowners don't trust chimney companies routed through lead aggregators — they trust the local expert who shows up, explains everything with photos, and doesn't upsell. This page must make 1st Class _that company_ in the prospect's mind within 30 seconds of landing.

### Differentiator Stack

| Tier         | Differentiator                     | Placement Strategy                                |
| ------------ | ---------------------------------- | ------------------------------------------------- |
| Trust Killer | **Technician, not a call center**  | Hero trust line — strongest single differentiator |
| Trust Killer | **No-surprise pricing**            | Form area micro-copy                              |
| Trust Killer | **Photo & video documentation**    | Before/after section proof                        |
| Trust Killer | **Clean-home guarantee**           | Risk reversal near primary CTA                    |
| Credibility  | **CSIA/NFI certified**             | Trust bar badges                                  |
| Credibility  | **Family-owned, locally operated** | Footer + trust bar                                |
| Credibility  | **Background-checked & insured**   | Near technician photos                            |
| Accelerator  | **Same-week availability**         | Hero sub-line + form area                         |
| Accelerator  | **Seasonal safety authority**      | Education section positioning                     |

### Conversion Architecture (Reverse-Engineered)

Every section of the page must kill a specific doubt in the homeowner's mind:

| Page Section            | Doubt It Kills                        | Key Element                                                         |
| ----------------------- | ------------------------------------- | ------------------------------------------------------------------- |
| Google Ad → Click       | "Is this relevant to me?"             | Headline mirrors exact search query                                 |
| Hero + Value Props      | "Is this company legit vs. Angi?"     | "Technician, not a call center" + "Same-week availability"          |
| Services + Before/After | "What do they actually do? Worth it?" | Visual proof: dirty → clean flue + "Protect → Clean → Verify" strip |
| Safety / Education      | "Do I actually need this?"            | NFPA recommendation + fire risk education                           |
| Reviews                 | "Are these reviews even real?"        | Specific quotes with service details, not generic praise            |
| Form                    | "Will this spam me? Lock me in?"      | Outcome-driven button copy + "No spam" micro-copy                   |
| Submit Confirmation     | "What happens now?"                   | "A technician will call within 15–30 min with options"              |

### Design Principles (from Reverse Engineering)

1. **Trust is woven, not sectioned** — trust elements appear in every scroll band, not just one trust bar
2. **Form friction is psychological, not technical** — 3 fields is fine; the micro-copy around the form matters 10x more
3. **Review specificity > review quantity** — "Showed me photos of the buildup" beats "Great service!"
4. **Education = authority = trust** — the safety section converts browsers into form-scrollers
5. **Before/after is the visual proof moment** — makes the invisible (inside chimneys) visible and concrete

### Pre-Mortem Risk Register

| #   | Risk                                                | Severity    | Mitigation                                                                                   |
| --- | --------------------------------------------------- | ----------- | -------------------------------------------------------------------------------------------- |
| 1   | Message mismatch — H1 doesn't mirror search query   | 🔴 Critical | Keyword-aligned headline; broad but specific: "Expert Chimney Cleaning, Inspection & Repair" |
| 2   | Form abandonment — too many fields or generic copy  | 🔴 Critical | Max 3 fields, outcome-driven button copy, trust micro-copy adjacent                          |
| 3   | Poor mobile experience — truncated hero, buried CTA | 🔴 Critical | Mobile-first design, sticky click-to-call bar, stacked hero layout                           |
| 4   | Slow page load — unoptimized images, high LCP       | 🟠 High     | Astro `<Image>`, WebP/srcset, lazy loading, LCP < 1.5s target                                |
| 5   | No conversion attribution — can't optimize spend    | 🟡 Medium   | GTM event hooks on form submit, data attributes, UTM passthrough                             |
| 6   | Email deliverability — autoresponder hits spam      | 🟡 Medium   | SMTP2GO domain verification (SPF/DKIM/DMARC), branded templates, plain-text fallback         |

### SCAMPER Innovations

| Lens                 | Innovation                                                                                    |
| -------------------- | --------------------------------------------------------------------------------------------- |
| **Substitute**       | Replace generic contact form with outcome-framed "Get Your Quote & Availability" UX           |
| **Combine**          | Merge reviews + before/after into single "See What We Did" proof carousel                     |
| **Adapt**            | "Meet Your Technician" as a hero-adjacent trust strip, not a full section                     |
| **Modify**           | Magnify post-form experience: 3-step visual timeline (Submit → Tech Calls → Scheduled Visit)  |
| **Put to Other Use** | Design before/after section to export cleanly as Google Ads creative                          |
| **Eliminate**        | Kill separate "Other Services" section → single line mention under services grid              |
| **Reverse**          | Problem-first narrative — "You have a chimney problem → We solve it → Proof → Your next step" |

### Competitive Position

**Weighted Score: 4.7/5** vs. Template Sites (2.0/5) and Aggregator Profiles (3.5/5)

**Key exposures to mitigate:**

- **Review volume gap** — Counter with specificity and recency over quantity. Launch with 5-8 exceptional testimonials.
- **Aggregator comparison advantage** — Counter with "a technician, not a marketplace" positioning
- **Speed to market** — Launch fast, iterate

### Stakeholder Insights

| Stakeholder           | Key Concern                                            | Resolution                                                                            |
| --------------------- | ------------------------------------------------------ | ------------------------------------------------------------------------------------- |
| **Business Owner**    | No reviews at launch                                   | Launch with 5-8 specific testimonials; "New to the area" freshness angle              |
| **Lead Technician**   | Will page show real work vs. stock?                    | Spec post-launch content pipeline: every job → before/after → media library           |
| **Homeowner Persona** | 6-second mobile test: nearby + available + fair price  | Above-fold mobile must show: location, "same-week," "no-surprise pricing"             |
| **Ads Manager**       | One page dilutes keyword match for cleaning vs. repair | Deep-link anchoring (`/#repair-services`) for ad targeting; future multi-lander split |

## 3. Executive Summary

1st Class Chimney & Air Duct is building its digital presence from zero. This project delivers a single, high-converting Astro landing page designed to capture chimney service leads directly from Google Ads — eliminating dependency on third-party aggregator platforms (Angi, Thumbtack, HomeAdvisor) and maximizing ROAS through precision message-match and trust-first conversion design.

The target user is a homeowner searching "chimney cleaning near me" or "chimney repair [city]" on mobile while deciding between 3-5 options on screen. They have approximately 6 seconds to be convinced this company is nearby, available soon, and won't overcharge. Every section of the page exists to kill a specific objection in sequence — from "Is this company legit?" (hero trust line) to "What happens after I submit?" (post-form visual timeline).

The page serves all chimney services (cleaning, inspection, repair, relining) as primary content, with air duct and dryer vent cleaning as a secondary "Other Services" mention — not a separate section. This single-page MVP sets the foundation for a future multi-lander architecture with keyword-specific routes. Deep-link anchoring (`/#section`) enables ad-level targeting within the single page during the interim.

Lead capture flows through a 3-field form (name, phone, optional message) into SMTP2GO, which sends branded notifications to 3 business email addresses and a branded autoresponder to the homeowner confirming their request and setting expectations. GTM integration points are built in from day one; the business owner manages tracking tool installation.

### What Makes This Special

The differentiator stack is engineered to be unreplicable by aggregator platforms:

- **"Technician, not a call center"** — the single strongest conversion line. A real chimney expert calls you back, not a dispatcher routing to whoever's available.
- **No-surprise pricing** — written estimate before any work, explained in plain language. Directly counters aggregator horror stories.
- **Photo & video documentation** — before/after proof of every inspection, making the invisible (inside chimneys) visible and concrete.
- **Clean-home guarantee** — drop cloths, sealed fireplace, vacuumed floors. Kills the silent objection: "Is chimney cleaning messy?"
- **CSIA/NFI certified** — industry authority most competitors don't display.
- **Family-owned, locally operated** — anti-corporate positioning for a high-trust home service.
- **Background-checked & insured** — non-negotiable when strangers enter your home near fire and air systems.
- **Same-week availability** — beats the 2-week aggregator wait without fake urgency.
- **Seasonal safety authority** — educational positioning as the expert, not just a vendor.

The page follows a problem-first narrative ("You have a chimney problem → We solve it → Here's proof → Your next step") rather than a company-first pitch. Trust elements are woven into every scroll band, not isolated in a single trust bar. Reviews prioritize specificity over quantity. At launch, 5-8 exceptional testimonials replace volume-based social proof, with a content pipeline planned to capture real before/after photos from every job.

### Project Classification

| Dimension         | Detail                                                                            |
| ----------------- | --------------------------------------------------------------------------------- |
| **Type**          | Astro static/SSR marketing site with SMTP2GO email integration                    |
| **Domain**        | Local Home Services — Chimney & Air Duct                                          |
| **Complexity**    | Low-Medium — standard web development with email integration and CRO optimization |
| **Context**       | Greenfield — brand new digital presence, no legacy systems                        |
| **Framework**     | Astro (SSG)                                                                       |
| **Design System** | Custom tokens extracted from Stitch mockup, evolved independently                 |

## 4. Success Criteria

### User Success

- **6-second clarity test passed** — Homeowner lands from Google Ad and instantly sees: location relevance, availability, and fair pricing above the mobile fold
- **Form feels safe** — User submits without hesitation because micro-copy kills every objection (no spam, no pressure, real tech calls back)
- **Branded autoresponder received** — Client gets confirmation in inbox (not spam) within 60 seconds with "what happens next" messaging
- **Post-form confidence** — Visual timeline confirms what happens next; no "did that work?" anxiety

### Business Success

| Metric                         | 90-Day Target             | 12-Month Target   |
| ------------------------------ | ------------------------- | ----------------- |
| **Leads/month**                | 40-60                     | 80-120            |
| **Cost per lead**              | < $60                     | < $40             |
| **Form conversion rate**       | > 8% of visitors          | > 12% (optimized) |
| **Aggregator spend reduction** | 50% reduction             | Full elimination  |
| **Lead-to-job close rate**     | Baseline (target: 25-35%) | 35%+              |
| **Google Ads Quality Score**   | 7+                        | 8+                |

**"This is working" moment:** The first week where leads from the website exceed leads from aggregator platforms.

### Technical Success

| Metric                           | Target                 |
| -------------------------------- | ---------------------- |
| **LCP**                          | < 1.5s                 |
| **Mobile PageSpeed**             | > 90                   |
| **Desktop PageSpeed**            | > 95                   |
| **Form submission success rate** | > 99%                  |
| **Email deliverability (inbox)** | > 95%                  |
| **TTFB**                         | < 200ms (Astro static) |
| **JavaScript errors**            | Zero console errors    |

### Measurable Outcomes

1. **Week 1:** Page live, GTM firing, first form submissions flowing through SMTP2GO
2. **Month 1:** 30+ leads, CPL established, first optimizations
3. **Month 3:** 40-60 leads/month sustained, aggregator spend halved, real before/after photos in pipeline
4. **Month 6:** Multi-lander expansion, CPL < $40, aggregator dependency eliminated

## 5. Product Scope

### MVP — Minimum Viable Product

- Single Astro landing page with all chimney services as primary content
- "Other Services" secondary mention (air duct, dryer vent) — single line, not a section
- 3-field lead capture form (name, phone, optional message)
- SMTP2GO integration: 3-recipient notification + branded client autoresponder
- GTM container slot with form submission event hook
- Deep-link anchoring for ad targeting (`/#chimney-cleaning`, `/#chimney-repair`)
- Mobile-first responsive design with sticky click-to-call bar
- Design token system extracted from Stitch mockup
- Image optimization (WebP, srcset, lazy loading via Astro `<Image>`)
- 5-8 curated launch testimonials
- Before/after proof section (generated imagery at launch)
- SEO foundations: meta tags, structured data, semantic HTML

### Growth Features (Post-MVP)

- Multi-lander architecture: separate `/chimney-cleaning`, `/chimney-repair` routes
- Dynamic phone number insertion for call tracking attribution
- Real before/after photo library from completed jobs
- A/B testing framework for headlines and form copy
- Review volume scaling (Google Business Profile integration)
- Blog/education content hub for organic SEO authority
- Service area pages for geo-targeting

### Vision (Future)

- Full service website with online booking/scheduling
- Customer portal with job history and documentation
- Automated review request pipeline post-job
- Seasonal campaign landing pages
- Multi-location expansion template

## 6. User Journeys

### Journey 1: Sarah — "My Fireplace Smells Weird" (Primary Conversion Path)

**Sarah, 38, suburban homeowner.** Bought her house 4 years ago, never had the chimney cleaned. Last night she lit the fireplace and noticed an acrid smell. Googled "chimney smell when burning" and learned about creosote and chimney fires. Now she's anxious.

**Opening:** Searches "chimney cleaning near me" on iPhone. Taps the ad: "CSIA-Certified Chimney Cleaning — No-Mess Guarantee · Same-Week Availability."

**Rising Action:** Page loads in 1.2s. Hero: "Expert Chimney Cleaning, Inspection & Repair" + "Family-owned · Background-checked · Same-week availability." She scrolls. Before/after section shows clogged vs. clean flue. Review: _"They showed me pictures of the creosote in my chimney"_ — Mark R.

**Climax:** Form: "Get Your Chimney Cleaning Quote & Availability." Three fields. Micro-copy: "Takes 60 seconds · No spam · A technician (not a call center) calls you back." She fills it out.

**Resolution:** Branded email arrives instantly: "Hi Sarah — one of our techs will call within 15-30 minutes with options and clear pricing." She shows her husband: _"A real technician is calling, not some dispatch center."_

### Journey 2: David — "Something's Leaking From My Chimney" (Emergency Path)

**David, 55, homeowner during a rainstorm.** Water dripping from inside his chimney, staining the wall. Needs someone NOW.

**Opening:** Searches "chimney repair emergency" on phone while panicking.

**Rising Action:** Lands on page. Doesn't read — eyes scan for phone number and repair confirmation. Sticky click-to-call bar: "Call Now: (555) 123-4567." Taps it.

**Climax:** Phone rings. A technician (not a receptionist) answers, walks through the likely issue, schedules next-day inspection.

**Resolution:** David hangs up feeling heard. He wouldn't have filled out a form — he needed human voice contact immediately.

### Journey 3: Mike — Business Owner Receives a Lead

**Mike, owner of 1st Class Chimney.** On a job site when his phone buzzes.

**Opening:** Branded email: "New Lead from 1st Class Chimney Website." Subject includes service type. Body: Sarah M., (555) 987-6543, "Fireplace smells when we light it."

**Rising Action:** Template is clean — brand colors, logo, clear formatting. Taps phone number directly from email.

**Climax:** Calls Sarah within 18 minutes. She's impressed. He explains inspection process, gives ballpark price, schedules Thursday.

**Resolution:** Lead converted to appointment in under 20 minutes. No CRM needed — just a well-formatted email on his phone.

### Journey 4: Yaron — Ads Manager Optimizes Performance

**Yaron, digital ads manager.** Page live for 3 weeks. Time to optimize.

**Opening:** Opens Google Ads + GTM. Form submission events firing correctly. Sees keyword-to-lead performance data.

**Rising Action:** "Chimney repair" keywords have lower conversion rate — message match is weaker on single page. Adjusts ad to link to `/#chimney-repair` and tests repair-specific ad copy.

**Climax:** After 2 weeks, CPL drops from $72 to $48. Anchor linking improved Quality Score by 1.5 points.

**Resolution:** Builds case for separate repair lander (Growth spec). Single page with anchored sections + GTM data provides enough signal to optimize now.

### Journey Requirements Summary

| Capability                                   | Journeys                            |
| -------------------------------------------- | ----------------------------------- |
| Mobile-first hero with message match         | Sarah, David                        |
| Sticky click-to-call bar                     | David (critical), Sarah (secondary) |
| Before/after proof section                   | Sarah                               |
| Review specificity                           | Sarah                               |
| 3-field form with micro-copy                 | Sarah                               |
| SMTP2GO autoresponder (branded)              | Sarah, Mike                         |
| SMTP2GO notification (branded, 3 recipients) | Mike                                |
| Deep-link anchoring (`/#section`)            | David, Yaron                        |
| GTM event hooks                              | Yaron                               |
| Post-form visual timeline                    | Sarah                               |
| Phone number above fold                      | David                               |

## 7. Web App & Platform Requirements

### Project-Type Overview

Static marketing landing page built with Astro (SSG). Multi-Page Architecture — Astro renders static HTML with zero JavaScript by default, adding interactivity only where needed (form submission, scroll behaviors). Not a SPA — a performance-optimized static site served from CDN.

### Browser Support

| Browser                   | Support                            |
| ------------------------- | ---------------------------------- |
| Chrome (mobile + desktop) | ✅ Primary (65%+ expected traffic) |
| Safari (mobile + desktop) | ✅ Primary (iOS audience)          |
| Firefox                   | ✅ Supported                       |
| Edge                      | ✅ Supported                       |
| IE 11                     | ❌ Not supported                   |

### Responsive Design

| Breakpoint | Target                   | Priority     |
| ---------- | ------------------------ | ------------ |
| 375px      | iPhone SE / small phones | 🔴 Primary   |
| 390-428px  | iPhone 14/15 Pro         | 🔴 Primary   |
| 768px      | iPad portrait            | 🟡 Secondary |
| 1280px+    | Desktop                  | 🟡 Secondary |

**Key:** Sticky click-to-call (mobile only), form visible without scroll on mobile, hero text readable at 375px, swipe-friendly before/after section.

### Performance Targets

> _Measurable thresholds and measurement methods defined in §10 NFR1-NFR7._

| Metric            | Target          |
| ----------------- | --------------- |
| LCP               | < 1.5s          |
| FID/INP           | < 100ms         |
| CLS               | < 0.1           |
| TTFB              | < 200ms         |
| Total page weight | < 500KB initial |
| Mobile PageSpeed  | > 90            |
| Desktop PageSpeed | > 95            |

**Strategy:** Astro zero-JS default + `<Image>` WebP/srcset + font preloading + critical CSS inline.

### SEO Strategy

| Element          | Implementation                                         |
| ---------------- | ------------------------------------------------------ |
| Title tag        | "Chimney Cleaning & Repair [City] · 1st Class Chimney" |
| Meta description | Benefit + differentiator + CTA                         |
| H1               | Single, keyword-aligned, mirrors ad headline           |
| Structured data  | LocalBusiness schema (JSON-LD)                         |
| Canonical        | Self-referencing                                       |
| Open Graph       | Title, description, image                              |
| Sitemap          | Auto-generated via Astro                               |

**Note:** SEO secondary to paid conversion. Organic ranking is a bonus.

### Accessibility (WCAG 2.1 AA)

> _Testable requirements defined in §9 FR31-FR34._

- Visible form labels (not placeholder-as-label)
- 4.5:1 minimum color contrast for body text
- Visible focus ring on all interactive elements
- Descriptive alt text on all images
- Full keyboard navigability for form + CTAs

### Real-Time Requirements

**None.** Static page with standard form POST. No WebSocket, SSE, or polling.

## 8. Project Scoping & Phased Development

> _Expands on §5 MVP scope with rationale for each capability._

### MVP Strategy

**Approach:** Problem-Solving MVP — deliver the minimum experience that captures a chimney service lead and routes it to the business owner.

**Philosophy:** "If a homeowner can land, trust the page, submit a form, and get called back — we've won."

**Resources:** Solo developer with design system. No backend team — Astro static + SMTP2GO API. Estimated 2-3 week build.

### MVP Must-Have Capabilities

| #   | Capability                                        | Rationale                       |
| --- | ------------------------------------------------- | ------------------------------- |
| 1   | Astro landing page with message-match hero        | No page = no conversions        |
| 2   | 3-field lead form with micro-copy                 | No form = no leads              |
| 3   | SMTP2GO: 3-recipient notification + autoresponder | No notification = lost leads    |
| 4   | Sticky click-to-call bar (mobile)                 | Emergency callers bypass form   |
| 5   | Trust strip (certifications, guarantees)          | Without trust = no submissions  |
| 6   | Before/after proof section                        | Visual proof beats words        |
| 7   | 5-8 curated testimonials                          | Social proof floor              |
| 8   | Services grid (chimney services)                  | Visitors need to see scope      |
| 9   | GTM container + form event hook                   | Day-1 tracking or blind spend   |
| 10  | Deep-link anchoring (`/#section`)                 | Ad targeting within single page |
| 11  | Mobile-first responsive (375px → 1280px)          | 65%+ traffic is mobile          |
| 12  | Image optimization (WebP, srcset, lazy)           | LCP < 1.5s or bounce            |
| 13  | LocalBusiness structured data                     | Quality Score + organic         |

### Phased Roadmap

**Phase 2 — Growth (Month 2-4):**

- Multi-lander split: `/chimney-cleaning`, `/chimney-repair`
- Dynamic phone number insertion (call tracking)
- Real before/after photo library
- A/B headline testing
- Review volume scaling (GBP integration)

**Phase 3 — Expansion (Month 6+):**

- Blog/education hub for organic authority
- Service area pages for geo-targeting
- Online booking/scheduling
- Customer portal with job history
- Automated review requests post-job
- Seasonal campaign landers

### Risk Mitigation

| Risk Type     | Risk                      | Mitigation                                                             |
| ------------- | ------------------------- | ---------------------------------------------------------------------- |
| **Technical** | SMTP2GO deliverability    | SPF/DKIM/DMARC pre-launch; plain-text fallback; inbox provider testing |
| **Technical** | Image weight blows LCP    | Astro `<Image>` format negotiation; hero image cap 100KB               |
| **Market**    | Low ad volume in area     | Broad match start; test 3 keyword groups; scale winners                |
| **Market**    | Zero reviews hurt trust   | 5-8 specific testimonials; "New to the area" freshness                 |
| **Resource**  | Solo developer bottleneck | Astro reduces complexity; design tokens reduce decisions; tight scope  |
| **Resource**  | Content not ready         | Generated imagery at launch; templated reviews; pipeline post-launch   |

## 9. Functional Requirements

### Landing Page Content & Structure

- **FR1:** Visitor can see a keyword-matched hero section with headline, sub-headline, and trust indicators above the fold
- **FR2:** Visitor can view a services grid showing all chimney services offered
- **FR3:** Visitor can see a secondary mention for air duct and dryer vent cleaning
- **FR4:** Visitor can view before/after proof imagery demonstrating work quality
- **FR5:** Visitor can read specific, detailed customer testimonials
- **FR6:** Visitor can see business credentials (CSIA/NFI certifications, insurance, background checks)
- **FR7:** Visitor can view trust guarantees (no-surprise pricing, clean-home guarantee, same-week availability)
- **FR8:** Visitor can navigate to page sections via deep-link anchors (`/#section`)

### Lead Capture & Conversion

- **FR9:** Visitor can submit a lead form with name, phone number, and optional message
- **FR10:** Visitor can see conversion-focused micro-copy explaining what happens after submission
- **FR11:** Visitor can see a post-submission confirmation with visual timeline (submit → call → arrive)
- **FR12:** Visitor can initiate a phone call via sticky click-to-call bar on mobile
- **FR13:** Visitor can see an above-fold phone number for immediate calling

### Email Notifications

- **FR14:** System can send a branded lead notification to 3 business email addresses
- **FR15:** System can send a branded autoresponder to the homeowner
- **FR16:** Lead notification can display name, phone, message, and service type
- **FR17:** Lead notification can render with clickable phone number for mobile callback
- **FR18:** Autoresponder can set expectations for callback timing and process

### Analytics & Tracking

- **FR19:** Page can include a GTM container slot
- **FR20:** System can fire a GTM event upon form submission
- **FR21:** Page can pass UTM parameters through to lead data
- **FR22:** Page can support deep-link anchors for ad campaign targeting

### SEO & Discoverability

- **FR23:** Page can render with keyword-optimized title tag and meta description
- **FR24:** Page can include LocalBusiness structured data (JSON-LD)
- **FR25:** Page can generate a sitemap automatically
- **FR26:** Page can render with Open Graph tags for social sharing

### Responsive Design & Performance

- **FR27:** Page can render mobile-first (375px-428px primary)
- **FR28:** Page can render responsively across tablet (768px) and desktop (1280px+)
- **FR29:** Page can optimize images using WebP, srcset, and lazy loading
- **FR30:** Page can render with zero client-side JS by default (Astro islands)

### Accessibility

- **FR31:** Visitor can navigate the page and submit the form using only keyboard
- **FR32:** Visitor can perceive all content with 4.5:1 minimum contrast
- **FR33:** Visitor can understand all images through descriptive alt text
- **FR34:** Form can display visible labels (not placeholder-only)

### Visual Design & Iconography

- **FR35:** Page can display service-specific icons in the services grid
- **FR36:** Page can display credential/certification badge icons in the trust strip
- **FR37:** Page can display guarantee shield icons alongside guarantee claims
- **FR38:** Page can display a hero visual directing attention toward the form
- **FR39:** Page can display section dividers or background patterns reinforcing brand
- **FR40:** Page can display a "What Happens Next" timeline with step icons
- **FR41:** Page can display a branded favicon and logo in the header
- **FR42:** Page can display review source icons (Google, Yelp) alongside testimonials

### Spam & Bot Protection

- **FR43:** Form can include a honeypot field invisible to real users
- **FR44:** Form can measure time-to-submit and flag submissions under 3 seconds
- **FR45:** System can validate phone number format (10-digit US)
- **FR46:** System can sanitize all form inputs to prevent injection attacks
- **FR47:** System can rate-limit submissions from the same IP (max 3/hour)
- **FR48:** Form can include an invisible challenge (e.g., Cloudflare Turnstile) blocking bots without user friction

### Click Fraud Protection

- **FR49:** Page can detect known bot/datacenter user agents without blocking real browsers
- **FR50:** Page can implement basic fingerprinting to identify repeated fraud clicks
- **FR51:** System can log suspicious patterns (rapid repeats, zero scroll, zero time-on-page)
- **FR52:** Page can exclude known invalid traffic via GTM/GA4 referrer filtering
- **FR53:** System preserves all legitimate user paths — no CAPTCHA, no interstitials, no friction on load or scroll _(guardrail)_

## 10. Non-Functional Requirements

### Performance

| NFR      | Requirement                                   | Measurement       |
| -------- | --------------------------------------------- | ----------------- |
| **NFR1** | Page fully interactive within 2s on 4G mobile | Lighthouse TTI    |
| **NFR2** | LCP under 1.5s                                | Lighthouse        |
| **NFR3** | CLS below 0.1                                 | Lighthouse        |
| **NFR4** | Initial page weight under 500KB               | DevTools Network  |
| **NFR5** | Form submission acknowledgment within 500ms   | Network waterfall |
| **NFR6** | SMTP2GO API response within 3s (p95)          | API monitoring    |
| **NFR7** | Zero render-blocking JS in critical path      | Lighthouse audit  |

### Security

| NFR       | Requirement                                              | Measurement         |
| --------- | -------------------------------------------------------- | ------------------- |
| **NFR8**  | All form data over HTTPS (TLS 1.2+)                      | SSL Labs            |
| **NFR9**  | Inputs sanitized against XSS/injection (client + server) | Pen test            |
| **NFR10** | SMTP2GO API key server-side only, never in client bundle | Source audit        |
| **NFR11** | Rate limiting per IP (max 3 submissions/hour)            | Load test           |
| **NFR12** | CSP headers preventing unauthorized scripts              | SecurityHeaders.com |
| **NFR13** | No PII stored on static site — all data through SMTP2GO  | Architecture audit  |
| **NFR14** | SPF/DKIM/DMARC configured for sending domain             | MXToolbox           |

### Reliability

| NFR       | Requirement                                             | Measurement                  |
| --------- | ------------------------------------------------------- | ---------------------------- |
| **NFR15** | 99.9% uptime for static page                            | Hosting SLA + uptime monitor |
| **NFR16** | Graceful form degradation — show phone if SMTP2GO fails | Fault injection              |
| **NFR17** | Email delivery > 98% for lead notifications             | SMTP2GO dashboard            |
| **NFR18** | Page renders correctly if GTM scripts fail              | Script-blocking test         |

### Integration

| NFR       | Requirement                                          | Measurement       |
| --------- | ---------------------------------------------------- | ----------------- |
| **NFR19** | SMTP2GO supports 3 simultaneous recipients           | Integration test  |
| **NFR20** | GTM loads asynchronously without blocking render     | Lighthouse        |
| **NFR21** | Ads conversion tracking fires within 500ms of submit | GTM debug mode    |
| **NFR22** | JSON-LD validates without errors                     | Rich Results Test |

### Maintainability

| NFR       | Requirement                                             | Measurement        |
| --------- | ------------------------------------------------------- | ------------------ |
| **NFR23** | Content updates deployable within 10 minutes            | Deployment test    |
| **NFR24** | Design tokens centralized — brand changes from one file | Code review        |
| **NFR25** | Zero vendor lock-in — SMTP2GO replaceable in 2 hours    | Architecture audit |

### Visual Asset Quality

| NFR       | Requirement                                                               | Measurement        |
| --------- | ------------------------------------------------------------------------- | ------------------ |
| **NFR26** | Brand logo crisp at all viewports — SVG, min 150px width                  | Visual QA          |
| **NFR27** | Certification badges (CSIA, NFI, BBB) min 48x48px, recognizable           | Mobile visual QA   |
| **NFR28** | Trust icons use consistent line weight/color from design tokens           | Design review      |
| **NFR29** | Service grid icons — same style family, stroke, size ratio                | Side-by-side check |
| **NFR30** | Before/after images min 600px wide on desktop, no compression artifacts   | Image audit        |
| **NFR31** | Icons/logos include hover state or subtle animation                       | Interaction QA     |
| **NFR32** | Review source logos (Google, Yelp) recognizable, within brand guidelines  | Brand compliance   |
| **NFR33** | Hero visual maintains quality across breakpoints — no pixelation/cropping | Responsive QA      |
| **NFR34** | SVG for icons/logos, WebP for photos, PNG fallback                        | Asset format audit |
