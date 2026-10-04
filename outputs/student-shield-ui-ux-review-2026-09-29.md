# StudentShield UI design review

Reviewed September 30, 2026, for SVBSM Junior High School students and guidance staff.

## Recommended direction

Use a calm school guidance portal with the existing green and gold identity, light reading and writing surfaces, clear headings, and compact navigation. Keep the school logo, the two student action cards, the desktop form split, and the emotion choices. Refine their placement and consistency.

For students, show the page's main choice or writing task early. For staff, give record sections stronger visual priority than charts. These are design judgments based on inspection; student and counselor preferences have not been measured.

## Review basis

This pass used the frontend-design and ui-ux-pro-max skills, fresh live views of student login, action selection, the authenticated Vent page, and staff login, and relevant view code. The student layouts were checked at 1280 px desktop width and 375–390 px phone widths. Incident Report became unavailable during this pass, so its visual assessment uses the earlier authenticated September 29–30 review and current view code. Staff dashboard, registrations, and user management observations come from that earlier authenticated review. No new staff-role usability test is claimed.

The review covers appearance: hierarchy, layout, spacing, alignment, typography, color, icon treatment, and responsive containment. Website behavior, privacy policy, model accuracy, and hosting diagnosis are outside this report's scope.

## Screen-specific recommendations

| Screen | Keep | Improve for the capstone |
| --- | --- | --- |
| Student login | Centered form, school logo, recognizable green and gold, and prominent main button. The phone view fits cleanly. | Simplify the background grid, glow, glass effects, and decorative status treatment. Use a stronger field surface and consistent typography. A quiet green backdrop and a clearly outlined form would connect better with the lighter student pages. |
| Staff login | A centered, compact form with a clear gold button and recognizable school branding. | Match its field treatment, heading scale, and border weight to student login. Reduce the decorative security badge and startup presentation so the form itself carries the visual emphasis. |
| Action selection | Two distinct choices with meaningful icons. Giving each action its own card is justified because the student is choosing between two paths. | Move the Meet card below the two choices. Shorten the introduction and reduce card padding. At 390 px, the first action began around 529 px and the second around 968 px down the page. Aim to make both choices easy to see within the first phone screen. |
| Vent, desktop | Green introduction panel and a light writing column. Main fields share a clear alignment. | Bring the writing field closer to its heading. Reduce the space occupied by the quote and meeting card. Keep one short supportive message rather than giving several decorative messages equal emphasis. |
| Vent, phone | Compact logo, orderly navigation, large writing area, and emotion chips that wrap into readable rows. | Order the page as navigation, heading, instruction, writing field, feelings, preference, and primary action. Place the quote and Meet component later or make them substantially smaller. Maintain the same left and right edges throughout. |
| Incident Report | A familiar form layout shared with Vent; distinct incident color cues and a prominent submit button. | Keep the same header and button dimensions as Vent. Change the mobile Safe Space subtitle to match the report page or use a neutral Student portal subtitle. Keep the optional evidence and draft controls visually secondary. |
| Staff dashboard | School branding, consistent overview cards, and readable status labels alongside colors. | Place the work sections above supporting charts. The long vent feed currently separates incident reports from the overview by extensive scrolling. Use tabs or separate section links and a bounded record list. These are proposed layout changes, not existing controls. |
| Pending Registrations | Clear title and familiar table styling. | Replace the crowded phone navigation with the dashboard's compact menu pattern. In the earlier 390 px check, the whole page expanded to 651 px. Keep table scrolling contained within the table area. |
| User Management | Clear primary Add User action and role filters. | Bring the directory closer to the title on phones by making summary cards more compact. Match its header spacing, logo size, and navigation treatment to the other staff pages. |

## Visual inconsistencies to correct

**Typography.** Keep Outfit for headings and Inter for body text and controls. The live Vent page also uses a serif quote and an Arial Meet button, creating four different type treatments. Let the meeting component inherit the surrounding type system. A suggested scale is 28–32 px for phone page titles, 20–24 px for section headings, 16 px for body text, and 14 px for supporting instructions. The inspected Vent page used 12 px for the emotion instruction and 11.5 px for preference explanatory text; these deserve more reading space.

**Spacing and shapes.** Use consistent 16–24 px phone content padding, 8–12 px gaps between related controls, and 24–32 px between sections. Keep modest rounding on form panels and buttons. Reserve pill shapes for emotion choices and small badges. Reduce repeated glows, large empty gaps, and oversized icon containers so headings and actions carry the emphasis.

**Buttons.** Use a shared 44–48 px height as the project's design target. The existing primary submission buttons are already prominent. Bring smaller mood, meeting, and staff navigation controls into the same sizing system. Keep the Back control distinct from the logo and place page-switch links and Sign Out consistently across forms.

**Color.** Use green for the main brand and action, gold sparingly for school identity, and a restrained red accent for incident cues. Prefer solid light form surfaces with dark text. Some secondary labels appear faint; measure their rendered contrast during implementation rather than declaring compliance from appearance alone. Continue pairing status colors with words.

**Containment.** Clip decorative background shapes inside their own containers. The checked student pages showed small horizontal overflow, including 383 px of page width at a 375 px viewport. The earlier staff session dropdown extended 23 px off the left edge at 390 px. Fix these specific components while preserving visible control outlines.

**Branding.** Keep the real school logo's proportions. Use one logo size for each header class and one consistent subtitle treatment. The Incident Report page should not visually identify itself as Safe Space.

## Suggested visual tokens

| Role | Recommendation |
| --- | --- |
| Main green | #0B5E2E |
| School gold accent | #EAB308 |
| Form surface | #FFFFFF |
| Light page background | #F6F9F6 |
| Main text | #24352B |
| Secondary text | #526259 |
| Heading type | Outfit, 600–700 weight |
| Body and controls | Inter, 400–600 weight |

These are a proposed shared palette and type system, not claims that every current page already uses them.

## Layout sketches

Open [the visual layout proposal](ui-design-review-2026-09-30/index.html) to compare recommended student action selection, Vent, and staff dashboard arrangements. The sketches use existing feature categories. Record lines and chart bars indicate placement only and contain no results or real records.

The local HTML structure was checked. Browser rendering of this local file could not be verified because the browser tool blocks file URLs; the sketches should be visually checked when opened locally.

## Order of visual work

1. Compact the student chooser and bring both actions above the Meet component.
2. Put the Vent heading and writing field earlier, especially on phones.
3. Standardize fonts, header treatment, controls, and content spacing across pages.
4. Fix mobile header, dropdown, and decorative overflow.
5. Bring staff work sections forward and contain long lists.

## Brief capstone report

StudentShield's visual design was reviewed for its intended Junior High School students and guidance staff. The school branding, two student action cards, light writing surfaces, and emotion choices provide a useful foundation. The recommended refinements are to shorten the student introduction, bring the main choices and writing field earlier, standardize typography and controls, and contain mobile elements within the screen. Staff pages would benefit from compact headers and work sections placed before supporting charts. These recommendations come from visual inspection and code review; they do not represent measured student or counselor design preferences.

## What changed in this review

The revised review gives a clearer design direction, identifies the action chooser as the first student layout priority, records the observed font mismatch and small-text sizes, and includes three layout sketches. It retains the earlier verified staff observations with their review dates and keeps the recommendations focused on visual design.
