# StudentShield capstone template revision

The official `StudentShield Capstone.pdf` is the content authority. Its original 86-page version is preserved at `backups/capstone-before-template-2026-09-08/StudentShield Capstone.pdf`. The editable output is `StudentShield Capstone.docx` in the project root.

## Template application

Reviewed all seven rendered pages of `Capstone Documentation Template.docx`, all 251 paragraphs, the section settings, styles, and header/footer artwork before building the revision. Used that actual DOCX package as the formatting shell, retaining its institutional logo and border drawings.

- US Letter: 8.5 × 11 inches.
- Exact template section margins: top/right/bottom 1 inch; left 2,835 twips (1.96875 inches); header 850 twips; footer 0.
- Times New Roman 12-point body, headings, captions, and tables; existing branded-header type sizes retained.
- Body/contents spacing follows the template’s 1.15-line examples. Title uses its 1.5-line title spacing and centered institutional blocks.
- Chapter and numbered-section order follows the supplied template. Existing material was relocated into the equivalent sections.
- Preliminary pages use lowercase Roman numbers; Chapter 1 restarts Arabic numbering at 1. The template shows that numbering scheme in its contents example but does not contain actual page-number fields or prescribe a footer number position. Right-aligned footer numbers and actual page-reference fields were added.
- The template contains no sample tables or formatted chapter-body pages. Plain grid borders, repeating table headers, consistent 12-point text, and controlled row breaks were used where exact examples do not exist. Original Gantt shading and journal-title/volume italics were retained.
- Sample contents page numbers, red font instructions, and instructional guide prose were not copied as capstone content.

## Source preservation

The two similarly named Word drafts contain author-name differences and completed evaluation claims absent from the official PDF, including a 4.78 grand mean and 30 respondents. They were not used as content authorities.

A previously exported editable copy was compared paragraph by paragraph against the official PDF. Thirteen substantive differences were identified in `source-differences.json`. Outdated system descriptions were corrected against code. The official PDF’s Nokham literature entry and reference were restored in place of the editable copy’s different citations, with its binary-classification metric attribution corrected from the published paper.

Original authors and May 2026 title-page date were retained. Existing objectives, significance, scope, technical descriptions, requirements, proposed test scenarios, Gantt schedule, sampling plan, statistical formulas and scales, and reference subjects remain represented. Corrections distinguish implemented functions, proposed requirements, and unsupported outcome claims.

The pre-oral approval form is retained after the prescribed appendices as a supplementary unsigned form; unnamed panel positions and outcome assertions are not newly completed. The existing title-defense approval wording and named personnel were retained without adding signatures, dates, or a “Passed” grade.

`transfer-log.json` records transferred paragraphs and their destinations. `unmapped-paragraphs.json` contains superseded headings/captions, duplicate risk-band labels represented in the updated metrics table, approval-form labels, and the three citations absent from the official PDF. It also includes the title (copied separately) and a hardware-section introductory sentence subsumed by its new introduction. It is not a list of accidentally dropped substantive sections.

## Corrections supported by current code

| Topic | Correction | Repository evidence |
|---|---|---|
| Hosting | CodeIgniter/InfinityFree and FastAPI/PyTorch/Hugging Face Spaces; no runtime CLI on PHP hosting | `AGENTS.md`, `.github/workflows/ftp-deploy.yml`, `ml_service/Dockerfile` |
| Deployment target | Recorded the checked-in workflow’s underscore-form Space name and configured hyphen-form API endpoint as a deployment-verification issue | `.github/workflows/deploy_hf.yml`, `VentController.php` |
| History | Current 14-day tracking consumes MySQL risk labels/timestamps; no FAISS history store in the active path | `VentController.php:55`, `ml_service/risk_history.py`, `ml_service/main.py` |
| Risk weights | Current base score is 50% distress + 50% negative emotion; compound boost is additive | `ml_service/main.py:1307`, `ml_service/main.py:1418` |
| Risk meaning | Screening scores and recurrence heuristics are not clinically validated future-risk probabilities | Scoring and history functions; no corresponding completed validation in the original paper |
| ML request | Synchronous request; 15-second connection and 60-second overall timeout | `VentController.php:109` |
| Vent alerts | High routes to counselors; Critical also attempts eligible parent alerts, subject to parent hiding and cooldown | `VentController.php:203` |
| Incident alerts | Intake notifies counselors; staff verification precedes incident-parent alerts | `BullyReportController.php`, `AdminController.php:250` |
| Rejected reports | Incident-verification alerts are suppressed, but repeated false-report strikes can separately notify a reporter’s parent | `AdminController.php:315` |
| Accounts | Student/counselor/super-admin roles; name/password login; inactive self-registration requires approval | `AuthController.php`, `Routes.php` |
| OTP | Staff OTP has expiry and attempt limits; valid remembered-device behavior is an exception | `AuthController.php:174`, `AuthController.php:310` |
| Guardian consent | Terms checkbox and staff approval are not a stored verified guardian-consent workflow | `AuthController.php:432`, `schema.sql` |
| Encryption | AES-256-CTR normal path; current vent exception path uses Base64 and cannot be described as encrypted | `Config/Encryption.php`, `VentController.php:167` |
| Meetings | Jitsi requests and staff-started rooms are implemented; no persistent live chat or parent portal | `VirtualMeetingController.php`, `Routes.php` |
| Retention | Proposed enrollment/30-day deletion policy is not implemented automatic retention | Existing paper versus current controllers and schema |
| Database | Added a dictionary directly from repository `schema.sql`; distinguished `virtual_meetings` from the older `video_conferences` migration and noted trajectory-column differences | `schema.sql`, relevant migrations |

No application source code, live database, deployment, or accounts were modified for this document task. Database descriptions represent checked-in schema and controller usage, not a verified dump of the live production database.

## Figure and table decisions

| Original asset | Treatment |
|---|---|
| Conceptual IPO figure | Not presented as current: names Azure/FAISS, completed validation, and formal consent inputs. Its valid conceptual explanation remains, with corrections. |
| Architecture figure | Not presented as current: FAISS memory and “AES-256 Auth” are inaccurate. Current supported architecture is described in text. |
| Context / DFD Level 0 | Retained as a scoped high-level vent/report view. Caption/text explain omitted meeting and history details and conditional alerts. |
| DFD Level 1 | Not presented as current: direct incident-to-parent path, fixed Gmail assumption, and incomplete log-storage representation. Valid process explanation remains. |
| Agile model | Retained as a development-method figure; not evidence of completed phases. |
| Student flowchart | Not presented as current: shows student 2FA and misleading alert choices. Correct workflow remains in text. |
| Counselor/admin flowcharts | Not presented as current: show public staff sign-up. Valid role actions remain in text. |
| ERD | Not presented as current: omits case/meeting entities and differs from current fields. Valid relationship explanations remain; current repository dictionary added. Corrected ERD figure left unfinished. |
| Use-case figure | Not presented as current: does not encode staff-verification and parent-hiding conditions. Valid actors/use cases remain in text. |
| Risk metrics table | Updated from the active scoring and history functions; original 35/35/30 formula and exponential/FAISS assertions not retained as current. |
| Six module test tables | Retained with necessary scenario corrections and blank result cells. |
| Gantt plan | Original planned activities and shading retained. February–May versus ten-month labeling remains explicitly unresolved; month indices used without invented completion dates. |
| Mean/SD interpretation tables | Retained as proposed analysis scales, not survey results. |

All excluded original assets remain available in the PDF backup and existing source materials. No substitute screenshots, model-results charts, or newly invented system diagrams were generated.

## Unfinished material

Abstract, acknowledgment, dashboard screenshot space, evaluation results, findings, conclusions, recommendations, prescribed appendices, and CV remain unfinished. The original official PDF does not supply completed content for these sections. Existing separate questionnaire material was not represented as a finalized instrument: `docs/Evaluation_Questionnaires.md` specifies ISO 9126 and includes student history/status statements that conflict with the current implementation, whereas the official paper/template calls for ISO/IEC 25010. No respondent counts, survey scores, pass/fail findings, certificates, signatures, or screenshots were invented.

## Targeted literature corrections

The original literature topics and references were retained. Clear topic/claim mismatches were corrected using the cited publications: Ebro/Mantillas concerns institutional mental-health programs, Baharuddin et al. concerns academic librarians, Schreurs/Vandenbosch concerns appearance-focused social media and body dissatisfaction, and Schmidt et al. concerns story reception. Those publications do not evaluate StudentShield’s reporting UI or crisis-history algorithm.

Sources used for these corrections include the [Ebro and Mantillas paper](https://media.neliti.com/media/publications/582908-mental-health-program-implementation-of-8a263172.pdf), [Baharuddin et al.](https://ijere.iaescore.com/index.php/IJERE/article/download/34916/14747), [Schreurs and Vandenbosch](https://www.sciencedirect.com/science/article/pii/S0747563222001868), [Schmidt et al.](https://ssol-journal.com/articles/10.61645/ssol.177), [Shannen et al.](https://www.e-chnr.org/upload/pdf/chnr-27-1-65.pdf), [Nokham et al.](https://www.ijcaonline.org/archives/volume186/number63/classifying-the-severity-of-cyberbullying-from-social-media-comments/), and the [DepEd 2025 revised IRR](https://www.deped.gov.ph/wp-content/uploads/DM_s2025_090r.pdf).

This was a targeted correction of evident mismatches, not a new systematic literature review or independent replication of every cited study. The paper’s cited external-study results must not be confused with completed StudentShield experiments.

## Verification record

`verification.json` records page count, exact margin comparisons, fonts, retained template image hashes, required-heading order, blank test-result checks, content-transfer checks, and PDF geometry checks. Rendered contact sheets were reviewed across the document; title spacing, Gantt positioning, stale captions, compact-table pagination, and contents references were corrected during that review.

Final local verification: 77 pages; all section margins match; required headings are in order; template logo bytes are unchanged; all logged transferred paragraphs are located in the exported PDF; no body characters exceed the checked horizontal bounds. All six test-result columns remain blank. The reviewed export replaced the official PDF, and its SHA-256 matches the final candidate. The original backup remains intact.

## Google Drive delivery status

The reviewed PDF and editable Word file were uploaded into the `ChatGPT` folder. The connector's native-document import failed because the uploaded file reference lacked required MIME metadata. A browser conversion fallback requires the user to sign in. Native Google Docs conversion and its rendered layout verification remain pending; the Drive-hosted Word file must not be described as a completed native Google Doc.

- Reviewed PDF: https://drive.google.com/file/d/1n4EcYMbe7A0MlSjeQIA1Hd3rsWS0c-5O/view
- Uploaded Word file ID: `1z5GcvNiVqBrhvaSKDxSdyHcW6ZYgWvlc`
- Drive folder: https://drive.google.com/drive/folders/1Cmuvy6_kK9zEFAK1F9-GxH6cuLuzo7aj
