---
name: Scientific Instrument Interface
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#45474c'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#75777d'
  outline-variant: '#c5c6cd'
  surface-tint: '#545f73'
  primary: '#091426'
  on-primary: '#ffffff'
  primary-container: '#1e293b'
  on-primary-container: '#8590a6'
  inverse-primary: '#bcc7de'
  secondary: '#0051d5'
  on-secondary: '#ffffff'
  secondary-container: '#316bf3'
  on-secondary-container: '#fefcff'
  tertiary: '#240f00'
  on-tertiary: '#ffffff'
  tertiary-container: '#422000'
  on-tertiary-container: '#d97705'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d8e3fb'
  primary-fixed-dim: '#bcc7de'
  on-primary-fixed: '#111c2d'
  on-primary-fixed-variant: '#3c475a'
  secondary-fixed: '#dbe1ff'
  secondary-fixed-dim: '#b4c5ff'
  on-secondary-fixed: '#00174b'
  on-secondary-fixed-variant: '#003ea8'
  tertiary-fixed: '#ffdcc3'
  tertiary-fixed-dim: '#ffb77d'
  on-tertiary-fixed: '#2f1500'
  on-tertiary-fixed-variant: '#6e3900'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Space Grotesk
    fontSize: 44px
    fontWeight: '600'
    lineHeight: 52px
    letterSpacing: -0.03em
  display-lg-mobile:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-xl:
    fontFamily: Space Grotesk
    fontSize: 30px
    fontWeight: '600'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 20px
    fontWeight: '500'
    lineHeight: 28px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Space Grotesk
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: 0em
  body-lg:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
    letterSpacing: -0.01em
  body-md:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 22px
    letterSpacing: 0em
  body-sm:
    fontFamily: Geist
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
    letterSpacing: 0em
  label-md:
    fontFamily: Geist
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-code:
    fontFamily: Geist
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.08em
spacing:
  gutter: 1rem
  gutter-lg: 1.5rem
  margin: 1rem
  margin-md: 1.5rem
  margin-lg: 2.5rem
  space-xxs: 0.125rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 0.75rem
  space-base: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  space-2xl: 3rem
---

## Brand & Style

This design system delivers an uncompromising, scientific-instrument aesthetic tailored for laboratory equipment oversight, scheduling, and asset delegation. Drawing inspiration from precision measurement consoles, academic laboratory apparatus, and structural Swiss modernism, the design language operates with extreme discipline.

The visual ethos rejects cosmetic trends, skeumorphism, decorative gradients, and atmospheric shadows in favor of absolute legibility, spatial efficiency, and structural integrity. Every element communicates technical accuracy and operational readiness. The interface prioritizes clarity over visual noise, evoking the calm, methodical rigor of an institutional research facility.

Key visual attributes:
- **Exactitude & Restraint:** Layouts align to a strict structural grid where fine hairline rules delimit functional zones.
- **Architectural Zero-Radius Edges:** Zero corner curvature (`roundedness: 0`) produces a razor-sharp, calibrated physical console feel.
- **Instrumental Typography:** The pairing of an austere geometric headline face (`Space Grotesk`) with an ultra-clean, technical body grotesque (`Geist`) gives metadata and inventory metrics immediate academic credibility.
- **Utilitarian Functionality:** Status signifiers and interactive states rely strictly on tonal contrast and deliberate, single-pixel perimeter bounds.

## Colors

The palette is engineered around high-legibility optical neutrals and high-contrast structural darks, accented by precise functional color roles.

### Primary & Base Surface Architecture
- **Primary (`#1E293B`):** Deep Slate Navy. Used for authoritative structural headers, high-priority buttons, selected navigational markers, and high-impact structural borders.
- **Surface Canvas (`#F8FAFC`):** Technical Off-White. The foundational workspace canvas that reduces screen glare across extended inventory audits.
- **Surface Layer 1 (`#FFFFFF`):** Instrument Pure White. Reserved for active inspection cards, data entry matrices, and dynamic readouts.
- **Surface Subtle (`#F1F5F9`):** Neutral inset tier for table headers, inactive control backgrounds, and segmented track grooves.

### Functional & State Roles
- **Secondary / Action Accent (`#2563EB`):** Precision Lab Cobalt. Denotes focused operational inputs, hyperlinks, and active scheduling states.
- **Tertiary / Warning & Pending (`#D97706`):** Calibrated Industrial Amber. Strictly designates pending checkout confirmations, calibration warnings, or restricted gear.
- **Success / Available (`#059669`):** Instrument Emerald. Used exclusively for items verified in stock, validated checkout manifests, and completed inspection logs.
- **Critical / Fault (`#DC2626`):** Diagnostic Crimson. Denotes overdue equipment, maintenance flags, or lockouts.
- **Perimeter Boundary Line (`#E2E8F0`):** Single-pixel structural baseline rule used across every card, table division, and docked panel.

## Typography

The typography reinforces technical precision and unambiguous data ingestion:

- **Headlines (`Space Grotesk`):** Displays structural clarity and mathematical proportions. Headlines set the context for equipment directories, workbench panels, and request workflows. Letter spacing remains neutral or slightly tightened to prevent loose text blocks.
- **Body & Data Grid (`Geist`):** Delivers clean neutral legibility. Geist handles technical documentation, device specs, serial numbers, and student authorization forms with high optical precision.
- **Technical Serial & Metadata (`label-code`):** Rendered uppercase with expanded tracking (`0.08em`) to mimic physical industrial stamps, inventory asset tags, and hardware diagnostic identifiers.
- **Hierarchy Rules:** Never stack more than three typographic levels in a single card module. Use structural position, hairline separators, and muted text shades rather than arbitrary font size leaps.

## Layout & Spacing

The spatial model relies on a strict 8px/4px atomic grid with architectural containment.

### Grid Architecture
- **Desktop (1024px and up):** 12-column fluid grid locked inside a max-width container of `1440px`. Gutters are maintained at `1.5rem` (`24px`), with outer horizontal margins at `2.5rem` (`40px`).
- **Tablet (768px - 1023px):** 8-column layout with `1rem` (`16px`) gutters and `1.5rem` (`24px`) margins. Sidebar navigation collapses into a dedicated dock or utility panel.
- **Mobile (< 768px):** 4-column column system with `0.75rem` (`12px`) gutters and `1rem` (`16px`) margins. Cards and tabular views unroll into single-column vertical instrument stacks.

### Structural Flow
- Density is calibrated to be crisp, disciplined, and functional: not hyper-compact like an IDE, yet devoid of consumer-marketing fluff.
- Card padding remains uniformly governed by `space-base` (`1rem`) for secondary items and `space-lg` (`1.5rem`) for primary staging areas.
- Internal component gaps strictly use the `space-sm` (`8px`) or `space-md` (`12px`) increments to maintain cohesive grouping.

## Elevation & Depth

This system operates entirely without blurred drop shadows, multi-tiered directional casting, or skeuomorphic bevels. Visual separation and layering are achieved through low-contrast outlines, spatial offsets, and planar tonal tiers.

### Depth Mechanics
1. **Planar Baseline (`#F8FAFC`):** The default backdrop canvas.
2. **Interactive Tile / Card (`#FFFFFF`):** Positioned against the baseline, delimited exclusively by a 1px solid hairline (`#E2E8F0`).
3. **Sub-panel Inset (`#F1F5F9`):** Indicates passive content, read-only telemetry, or deactivated zones, bordered with `#E2E8F0`.
4. **Modal Dialogues & Flyout Drawers:** Lifted using an explicit structural outline (`1px solid #1E293B`) against a translucent neutral veil (`rgba(15, 23, 42, 0.4)`). A single crisp, unblurred architectural offset shadow may be deployed for modals: `box-shadow: 4px 4px 0px 0px #1E293B`.
5. **State Focusing:** Interactive inputs never use glowing drop rings. Focus states swap the default border line (`#E2E8F0`) to a distinct double-weight or high-contrast border (`#1E293B` or `#2563EB`).

## Shapes

The shape system enforces an absolute zero-radius standard (`0px` / `0rem`).

- **Perimeter Discipline:** Buttons, text input enclosures, status indicators, badges, dropdown menus, and containment cards are strictly cut with 90-degree right angles.
- **Metaphor Alignment:** Sharp rectilinear forms echo physical electronic diagnostic racks, lab record manifests, optical sample holders, and precision-milled aluminum brackets.
- **Dividers:** Divider lines maintain a strict 1px thickness without end-caps, terminating flush with outer container boundaries.

## Components

### Buttons
- **Primary Button:** High-contrast solid dark slate fill (`#1E293B`), stark white text (`#FFFFFF`), `0px` border radius, 1px border (`#1E293B`). Hover: fill shifts to `#334155`. Active: `#0F172A`.
- **Secondary / Outline Button:** Background pure white (`#FFFFFF`), text dark slate (`#1E293B`), 1px border (`#CBD5E1`). Hover: surface tints to `#F8FAFC` with border `#94A3B8`.
- **Subtle / Ghost Button:** Transparent background, dark text (`#334155`), 1px transparent border. Hover: `#F1F5F9`.
- **Sizes:** Compact (`32px` height, `12px` x-padding), Standard (`40px` height, `16px` x-padding). All labels in `Geist` Medium.

### Input Fields & Controls
- **Form Inputs:** Pure white background (`#FFFFFF`), 1px solid border (`#CBD5E1`), 0px radius, height `40px`, padding `0 12px`. Placeholder text set in `#94A3B8`.
- **Focus State:** 1px solid `#1E293B` or `#2563EB` with an optional 1px interior offset line. Zero ambient glow.
- **Checkboxes & Radios:** Sharp square boxes (`16px` x `16px`), 1px solid border (`#94A3B8`). Checked checkbox renders a solid `#1E293B` box containing a crisp white geometric checkmark. Radio buttons render an inset solid square dot rather than a circle.

### Badges & Status Chips
- **Geometry:** Pure rectangular framing (`0px` radius), padding `2px 6px`, font `label-code` uppercase.
- **Available / Verified:** Background `#ECFDF5`, text `#065F46`, 1px border `#A7F3D0`.
- **Pending Checkout:** Background `#FFFBEB`, text `#92400E`, 1px border `#FDE68A`.
- **In-Use / Borrowed:** Background `#EFF6FF`, text `#1E40AF`, 1px border `#BFDBFE`.
- **Maintenance / Fault:** Background `#FEF2F2`, text `#991B1B`, 1px border `#FECACA`.

### Cards & Data Panels
- **Container Structure:** Pure white background (`#FFFFFF`), 1px perimeter border (`#E2E8F0`), zero border radius.
- **Card Header:** Distinct hairline divider (`1px solid #E2E8F0`) separating the title bar from the card body. Header incorporates device category tag, asset identifier, and action menu.

### Data Tables (Equipment Registry)
- **Table Structure:** Full-width cells bordered by horizontal `1px solid #E2E8F0` rules.
- **Headers:** Background `#F8FAFC`, uppercase `11px` Geist typography (`#64748B`), tracking `0.06em`, height `36px`.
- **Row Interaction:** Hover invokes a clean `#F1F5F9` background tint. Selected items exhibit a 2px vertical indicator bar on the left edge in `#1E293B`.

### Equipment Reservation Timeline / Schedule Matrix
- **Matrix View:** Horizontal grid representing hourly or daily availability slots. Empty slots delineated by dashed or fine `#E2E8F0` borders.
- **Reserved Blocks:** Solid slate blocks (`#1E293B`) or tinted operational spans (`#EFF6FF` with `1px solid #2563EB`), displaying the reserving student/lab group identity in crisp compact text.