# DFD notation and balancing research

Checked 4 October 2026 against university teaching material, official tool documentation, and the current StudentShield source. These are logical data-flow diagrams, not deployment diagrams or execution flowcharts.

## Recommended convention

Use **Context diagram** for the whole system as process 0; **Level 0 DFD** for major processes numbered 1.0, 2.0, etc.; and **Level 1 DFD — decomposition of process N.0** for subprocesses N.1, N.2, etc. State this convention in the caption or methodology. The context contains external entities and boundary flows, with no internal stores. Processes have verb phrases; flows have noun phrases. [Bradley University, Drawing DFDs](https://personalpages.bradley.edu/~simonp/atg383/documentation/dfd.html).

Terminology is not universal: Visual Paradigm's official tutorial calls the single-process context diagram **Level 0**, then calls the major-process expansion **Level 1**. This is a different numbering convention, not a different modeling principle. Do not combine conventions in one figure set. [Visual Paradigm, leveled DFD tutorial](https://www.visual-paradigm.com/tutorials/leveled-dfd.jsp).

## Symbols and permitted connections

- Use one consistent notation: Gane–Sarson rounded/divided process boxes, rectangular external entities, open-ended rectangular data stores, labeled directional arrows. Avoid deployment icons and flowchart diamonds.
- A process transforms incoming data; every process needs input and output. Stored records may feed processing. Output must be plausible from the inputs and retained data, avoiding unexplained outputs.
- Logical DFDs emphasize functions and information, so frameworks, hosting providers, protocols, servers, and encryption algorithms normally belong in architecture or supporting text rather than process names.

[Visual Paradigm, DFD guide and notation](https://www.visual-paradigm.com/guide/data-flow-diagram/what-is-data-flow-diagram/).

External entities are sources/recipients beyond the chosen system boundary; they can be people or other systems. Internal processes describe system work, and data stores represent retained information. Every ordinary arrow must touch a process: process–process, entity–process, or store–process. Never entity–store or store–store. For this capstone, omit entity–entity arrows rather than introducing optional outside-scope conventions. University of Cape Town allows specially dashed external-to-external context annotations, but these are outside the modeled system and unnecessary here. [University of Cape Town, DFD elements](https://www.cs.uct.ac.za/mit_notes/software/htmls/ch06s05.html); [Visual Paradigm, connection rules](https://www.visual-paradigm.com/support/documents/vpuserguide/1284/992/36119_dataflowdiag.html).

## Balance and decomposition

The child diagram must preserve the parent process's boundary inputs and outputs, including their direction and source/recipient. Internal arrows and stores may add detail; new boundary exchanges require corresponding changes in the parent diagram. Maintain a small flow inventory to check this explicitly. [University of Cape Town, Balancing](https://www.cs.uct.ac.za/mit_notes/software/htmls/ch06s10.html).

Decompose one named parent process per detailed diagram. A store or another process that was internal to the overall system can be outside the selected decomposition's boundary. Preserve its identity instead of converting a store into an external-entity symbol. Number children from their parent, and retain matching boundary labels. [University of Cape Town, hierarchical decomposition](https://www.cs.uct.ac.za/mit_notes/software/htmls/ch06s08.html).

Arrows describe information such as “Vent submission,” “Risk assessment,” or “Session request”; avoid labels such as “Next,” “If high,” or “Redirect now.” Time-triggered execution is not itself a data flow. A meaningful request/status record can be data, but the DFD should not become a sequence of control decisions. [University of Cape Town, worked review](https://www.cs.uct.ac.za/mit_notes/software/htmls/ch06s15.html).

## StudentShield boundary application (source-based inference)

Assuming the modeled boundary is the **StudentShield PHP web application and its application data stores**:

- Student, Counselor, Administrator, and Parent are external roles even when they belong to the school.
- The ML service is an external system. Model the outgoing analysis payload and returning assessment. `web-app-ci4/app/Controllers/VentController.php` sends `raw_text`, `student_id`, and `risk_history` to `/analyze-text`; it reads risk and trajectory results. At an appropriate level, use “Vent text and risk history” and “Risk and trajectory assessment” (the current endpoint does not return emotion labels). Keep local risk fallback inside PHP processing.
- Google Meet is outside the boundary but is **not a direct PHP API participant in the present implementation**. The counselor supplies a Meet URL and host confirmation; StudentShield stores the session and returns an authorized link to the user's browser. Model those exchanges with the human roles and the session store. Explain external conferencing in the caption/architecture figure if necessary rather than adding nonexistent PHP–Meet flows.
- Evidence: `web-app-ci4/app/Controllers/VirtualMeetingController.php::start()` accepts `meet_link` and `host_confirmed`; `join()` returns an HTTP redirect. `web-app-ci4/app/Libraries/GoogleMeetSessions.php::normalizeLink()` validates URL format only; `publish()` stores it; `join()` checks assignment. The controller explicitly states that the counselor creates the Meet externally and that the app does not create Meet spaces via OAuth. Do not draw Google-created meeting IDs, call media, attendance, or call-ended feedback entering PHP unless another verified implementation supports them.

If the scope instead includes the Python service as part of the whole StudentShield system, ML inference becomes an internal process. Choose and disclose one scope consistently across all levels.

