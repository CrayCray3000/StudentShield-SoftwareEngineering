# Figure 1 review

Revised 3 October 2026.

The figure is a software architecture overview with deployment boundaries. It uses C4 guidance for meaningful relationship labels and visible technologies; it is not presented as a strict UML diagram or a complete C4 model.

## Verified against the application

- `web-app-ci4/app/Controllers/VentController.php` sends vent text, student ID and recent risk history to `/analyze-text`, using JSON and `X-API-KEY`. The local fallback remains in the PHP application.
- `web-app-ci4/app/Controllers/VirtualMeetingController.php` stores a staff-published Google Meet link through `GoogleMeetSessions`. Its join action authorizes access and returns a browser redirect. It does not create Google Meet spaces through an API.
- `web-app-ci4/app/Libraries/NotificationService.php` posts SMS requests to a configured gateway URL and uses configured SMTP credentials for email. The figure does not assert a particular SMS vendor or successful delivery.
- Hosting, database and model names follow the repository's architecture instructions. This review did not independently inspect production infrastructure or test external service availability.

## Layout and export checks

- Black text and strokes on white; consistent serif typography.
- Hosting boundary contains the PHP application and its database.
- External screening, video and notification services sit in one aligned row.
- Directed connectors describe dependencies or initiated actions; the screening service description identifies its returned results.
- Native draw.io shapes, icons, text and connectors remain editable.
- The generator checks text bounds, text overlap, connector-label collisions and labels crossing box borders. The PNG preview was visually reviewed.

## References

- C4 notation: https://c4model.com/diagrams/notation
- C4 deployment diagrams: https://c4model.com/diagrams/deployment

These references guide the diagram notation and grouping, not claims about StudentShield's implementation.
