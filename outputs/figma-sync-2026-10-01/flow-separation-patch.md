# Figma flow separation patch

## Student Flow

1. Student login / registration
2. Student home and action selection
3. Safe Space (venting)
4. Bullying report
5. Google Meet request state
6. Google Meet waiting / join state

Keep the existing student copy, controls, moods, and state text. Only apply the approved lighter green palette.

## Admin Flow

1. Staff login
2. Email OTP
3. Admin dashboard
4. Incident list and incident detail modal
5. Pending registrations
6. User management
7. Audit logs
8. Student case summary
9. Case event feed and notes
10. Case status modal
11. Google Meet publication modal

The admin frames must be grouped separately from the student frames and use a separate prototype starting point. Keep the scroll offsets that were captured for the long dashboard, incidents, case summary, and case notes screens.

## Correction required before the next Figma write

- On the incident list, change the prototype-only label `Reject (Fake)` to the actual UI label `Reject`. The backend state is `rejected_fake`, but that state name must not appear in the button label.
- Do not trigger Verify, Reject, Archive, Approve, Publish, or case status actions while wiring prototype navigation.

## Current Figma targets

- Student targets: `771:688`, `771:690`, `771:692`, `771:694`, `771:696`, `771:698`
- Admin targets: `771:700`, `771:702`, `771:704`, `771:706`, `771:708`, `771:710`, `771:712`, `771:714`, `771:716`, `771:718`, `771:720`, `771:722`, `771:724`
