# System Flowchart (By Role)

This flowchart separates the user journeys based on their specific roles (Student, Counselor, and Admin) after the initial login and Two-Factor Authentication (TFA) process.

```mermaid
flowchart TD
    %% Authentication Flow
    Login([Start: Login Pages]) --> Creds[/Input: Credentials/]
    Creds --> Submit{Credentials Valid?}
    Submit -->|No| Login
    Submit -->|Yes| CheckRole{Determine Role}

    %% Role Branches
    CheckRole -->|Student| SLogin[Create Login Session]
    CheckRole -->|Counselor / Admin| GenOTP[Suspend Login & Send OTP]
    
    GenOTP --> TFA[/Input: TFA Code/]
    TFA --> ValidTFA{TFA Valid?}
    ValidTFA -->|No| TFA
    ValidTFA -->|Yes| ALogin[Create Login Session]
    
    SLogin --> SDash[Student Dashboard]
    ALogin --> AssignRole{Assign Dashboard}
    
    AssignRole -->|Counselor| CDash[Counselor Dashboard]
    AssignRole -->|Admin| ADash[Admin Dashboard]

    %% Student Flow
    SDash --> SVent[/Form: Anonymous Vent/]
    SDash --> SReport[/Form: Bullying Report/]
    SVent --> VentProc[(Database: Vents & AI)]
    SReport --> RepProc[(Database: Reports)]

    %% Counselor Flow
    CDash --> RTM[Process: Real-time Monitoring]
    CDash --> BIR[Process: Incident Reports]
    
    RTM --> LIF[Display: Live Intelligence Feed]
    LIF --> SBT[Display: Student Behavior]
    RTM --> ADM[Display: Alert Delivery]
    
    BIR --> ReviewReport[Action: Review Case Details]
    ReviewReport --> CheckInc{Is Incident Valid?}
    CheckInc -->|True| VerifyAlert[Action: Verify & Alert Parents]
    CheckInc -->|False| Reject[Action: Reject as False]
    VerifyAlert --> SaveDec[(Database: Save Decision)]
    Reject --> SaveDec

    %% Admin Flow
    ADash --> UM[Process: User Management]
    UM --> ViewD[/Display: System Directory/]
    UM --> AddU[Action: Add User]
    UM --> EditU[Action: Edit User]
    UM --> DelU[Action: Delete User]
    
    %% Logout
    VentProc -.-> Logout([End: Logout])
    RepProc -.-> Logout
    SaveDec -.-> Logout
    SBT -.-> Logout
    ADM -.-> Logout
    AddU -.-> Logout
    EditU -.-> Logout
    DelU -.-> Logout
    ViewD -.-> Logout
```

### How to open this in Draw.io
To get this exact flowchart with native editable shapes in Draw.io:
1. Open [app.diagrams.net](https://app.diagrams.net/).
2. In the top menu, go to **Arrange** > **Insert** > **Advanced** > **Mermaid...**
3. Copy the code from the `mermaid` block above and paste it into the text box.
4. Click **Insert**. Draw.io will automatically read the shapes (databases, decisions, etc.) and lay them out for you perfectly.

