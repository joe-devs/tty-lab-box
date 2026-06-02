# BashBox — Project Blueprint

> BashBox is a hands-on Linux job-simulation lab platform.
>
> Current phase: **Phase 1 — Stabilize and Rebrand**
>
> This project started as a working lab prototype and is being gradually transformed into BashBox without breaking the existing Laravel, LXD, terminal, and grader workflow.

---

## 1. Project Goal

BashBox is not intended to be a traditional course platform.

The long-term goal is to become a job-simulation platform where a user joins a virtual company, receives realistic Linux administration tasks, works inside real Linux lab environments, submits the task, gets graded, and receives feedback.

The current working prototype focuses on:

* Laravel-based web app
* Filament admin panel
* Browser terminal using xterm.js
* Node.js WebSocket terminal gateway
* LXD containers as lab runtime
* Bash grader scripts that return JSON
* Start → Terminal → Submit → Grade → Cleanup workflow

---

## 2. Current Stable Runtime

The current stable environment is:

* OS: Rocky Linux server VM
* Web app: Laravel
* Admin panel: Filament
* Terminal UI: xterm.js
* Terminal gateway: Node.js WebSocket server
* Lab runtime: LXD containers
* Grading: Bash script pushed into the container and executed during submission
* Current access:

  * Laravel: `http://<server-ip>:8080`
  * Terminal Gateway: `ws://<server-ip>:8081/ws`

Current confirmed stable behavior:

* Start Lab works
* LXD container is created
* Browser terminal opens a real shell
* Submit Lab works
* Grader script runs inside the container
* Lab container is destroyed after submission
* No old lab containers remain after cleanup

---

## 3. Current Architecture

```text
Browser
  |
  | HTTP
  v
Laravel Web App
  |
  | API calls
  v
Laravel Attempt / Terminal / Grader Controllers
  |
  | WebSocket token
  v
Node.js Terminal Gateway
  |
  | lxc exec
  v
LXD Lab Container
```

### Main Components

#### Laravel App

Responsibilities:

* Render user-facing pages
* Provide the lab page
* Manage attempts
* Start and stop lab sessions
* Generate terminal tokens
* Push and run grader scripts
* Store attempt/result data
* Provide Filament admin panel

#### Filament Admin Panel

Used to manage:

* Labs
* Nodes
* Steps
* Lab duration
* Published/unpublished status
* Grader script content

#### Node.js Terminal Gateway

Responsibilities:

* Accept WebSocket terminal connections
* Validate terminal request context
* Connect browser terminal to the correct LXD container
* Run interactive shell using `lxc exec`
* Stream terminal input/output between browser and container

#### LXD

Current runtime:

* Labs run as LXD containers.
* One container is created per lab attempt/node.
* Containers are destroyed after successful submit or stop.
* LXD virtual-machine mode is not used in the current working environment.

---

## 4. Current User Flow

1. Open the BashBox web app.
2. Select or open a lab.
3. Start the lab.
4. Laravel creates an attempt.
5. Laravel creates the required LXD container.
6. The user opens a browser terminal.
7. Terminal Gateway connects to the container shell.
8. The user completes the task.
9. The user submits the lab.
10. Laravel pushes the grader script into the container.
11. Laravel executes the grader script.
12. Laravel stores the result.
13. Laravel destroys the lab container.
14. The user sees the evaluation result.

---

## 5. Current Admin Flow

1. Open the Filament admin panel.
2. Create or edit a lab.
3. Add lab nodes.
4. Add instructions/steps.
5. Add or update the grader script.
6. Publish the lab.
7. Test the lab from the user-facing page.

---

## 6. Repository Layout

```text
project-root/
  laravel/
    app/
    config/
    database/
    resources/
    routes/
    .env.example

  terminal-gw/
    server.js
    package.json

  scripts/
    grade.sh

  PROJECT_BLUEPRINT.md
  ANTIGRAVITY_TASKS.md
```

Notes:

* `laravel/` contains the Laravel application.
* `terminal-gw/` contains the Node.js WebSocket terminal gateway.
* `scripts/` contains helper/sample scripts.
* This file is the current project blueprint.

---

## 7. Current Data Model

The current prototype uses a simple lab/attempt model.

Main tables:

* `users`
* `labs`
* `nodes`
* `steps`
* `attempts`
* `attempt_nodes`
* `results`
* `jobs`
* `cache`
* `personal_access_tokens`

Current concept mapping:

| Current Model | BashBox Future Meaning                 |
| ------------- | -------------------------------------- |
| Lab           | Work task / job-simulation task        |
| Step          | Task instructions                      |
| Node          | Lab server/container                   |
| Attempt       | User task session                      |
| Result        | Grader result / task submission result |

The current database model is good enough for Phase 1.

Do not rename the database model yet.

The BashBox job-simulation data model will be introduced gradually in later phases.

---

## 8. LXD Runtime Rules

Current runtime decision:

* Use LXD containers.
* Containers are faster and work in the current Rocky VM setup.
* The current implementation should keep working before deeper architecture changes are introduced.

Current lifecycle:

```text
Start Lab
  -> create LXD container
  -> open terminal shell
  -> submit lab
  -> push grader script
  -> run grader
  -> store result
  -> destroy container
```

Cleanup rule:

* Every lab container created for an attempt must be destroyed after submit or stop.
* No orphan lab containers should remain after a completed attempt.

---

## 9. Terminal Gateway Behavior

The terminal gateway connects the browser terminal to the correct LXD container.

Current expected behavior:

```text
Browser xterm.js
  -> WebSocket
  -> Node.js terminal gateway
  -> lxc exec
  -> shell inside LXD container
```

The terminal gateway should:

* Accept WebSocket connections on port `8081`
* Use `/ws` as the WebSocket path
* Validate the terminal request
* Open a shell inside the assigned container
* Stream input/output between xterm.js and the container shell
* Close the shell when the WebSocket disconnects

Current user-facing terminal messages should use the BashBox name.

---

## 10. Grader Contract

Each lab has a grader script.

Current grader flow:

1. User submits the lab.
2. Laravel writes the lab grader script to a temporary host file.
3. Laravel pushes it into the LXD container.
4. Laravel makes it executable.
5. Laravel runs it inside the container.
6. The script returns JSON.
7. Laravel stores and displays the result.
8. Laravel destroys the container.

Expected simple grader output:

```json
{
  "passed": true,
  "score": 100,
  "message": "Lab completed successfully."
}
```

A stronger future grader format may include detailed checks:

```json
{
  "passed": false,
  "score": 60,
  "checks": [
    {
      "name": "Report file exists",
      "passed": true
    },
    {
      "name": "Disk usage information included",
      "passed": false
    }
  ],
  "message": "Some required checks are missing."
}
```

Rules:

* The grader must output valid JSON.
* The grader should run inside the lab container.
* The grader should not depend on host-only state.
* The grader is the source of truth for pass/fail.

---

## 11. Current Development Commands

### Laravel

Run from the Laravel app directory:

```bash
cd laravel

php artisan optimize:clear

php artisan serve --host=0.0.0.0 --port=8080
```

### Terminal Gateway

Run from the terminal gateway directory:

```bash
cd terminal-gw

npm install

node server.js
```

### LXD Check

```bash
lxc list
```

After a successful submit, there should be no old lab containers left behind.

---

## 12. Current Stability Fixes

The following fixes are part of the current stable base:

* App timezone is configurable through `APP_TIMEZONE`.
* `.env.example` documents `APP_TIMEZONE=Europe/Copenhagen`.
* The lab countdown uses server time to avoid browser/server clock drift.
* Labs run as LXD containers.
* Grader script is pushed into the container before submission grading.
* User-facing text has been updated from the old project name to BashBox.

---

## 13. Phase 1 Scope

Phase 1 is about stabilizing the current working prototype and safely rebranding it.

### Phase 1 Goals

* Keep the current working lab flow stable.
* Keep Git history clean.
* Rebrand user-facing text to BashBox.
* Replace outdated user-facing virtual-machine wording with container wording.
* Update documentation so it matches the current working reality.
* Avoid large refactors.
* Avoid database renaming.
* Avoid changing runtime architecture unless required for stability.

### Phase 1 Non-goals

* Do not redesign the full product UX yet.
* Do not introduce the full job-simulation data model yet.
* Do not rename all legacy classes/methods yet.
* Do not convert the app into a full SaaS yet.
* Do not add billing, subscriptions, teams, or tenant separation yet.
* Do not rewrite the project from scratch.

---

## 14. Phase 2 — Job Simulation Conversion

Phase 2 is the point where BashBox starts moving from a technical lab platform into a real job-simulation experience.

The goal is not only to rename buttons or improve the UI.

The goal is to make the user feel like a new employee working inside a virtual company, receiving realistic tasks from a manager, completing them on real Linux systems, submitting work, and receiving feedback.

The current runtime model can stay the same during this phase.

Internally, the application may still use the existing `labs`, `steps`, `attempts`, `nodes`, and `results` tables. These names should not be renamed yet.

However, the user-facing experience should gradually move away from lab/course language and toward work/job-simulation language.

---

### 14.1 Product Experience Goal

The current technical flow is:

```text
Lab
  -> Steps
  -> Terminal
  -> Submit
  -> Result
```

The target BashBox experience is:

```text
Company
  -> Job Role
  -> Work Assignment
  -> Manager Message
  -> Employee Workspace
  -> Terminal Work
  -> Submit Work
  -> Feedback
  -> Progress
```

The first job-simulation track should be:

```text
Company: CloudNova Hosting
Role: Junior Linux Administrator
Phase: First Week at Work
Manager: Julian
First Task: Basic Server Inspection
```

---

### 14.2 Transitional Model Mapping

During Phase 2, the database model can remain unchanged.

The current model should be interpreted like this:

| Current Internal Model | User-Facing Meaning in BashBox             |
| ---------------------- | ------------------------------------------ |
| `labs`                 | Work assignments / tasks                   |
| `steps`                | Task brief, requirements, checklist, hints |
| `nodes`                | Assigned servers                           |
| `attempts`             | Work sessions                              |
| `results`              | Submission results / grader results        |

Important rule:

Do not rename database tables, Eloquent models, controllers, or routes during Phase 2 unless there is a strong technical reason.

The priority is to change the user-facing experience while keeping the runtime stable.

---

### 14.3 Product Language Rules

User-facing text should gradually follow these terms:

| Old Lab Platform Term | BashBox Job-Simulation Term |
| --------------------- | --------------------------- |
| Lab                   | Task / Work Assignment      |
| Start Lab             | Start Task                  |
| Submit Lab            | Submit Work                 |
| Instructions          | Task Brief                  |
| Steps                 | Requirements / Checklist    |
| Dashboard             | Employee Workspace          |
| Nodes                 | Assigned Servers            |
| Result                | Submission Result           |
| Score                 | Task Score                  |
| Student               | User / Employee             |
| Lab page              | Workspace                   |
| Grading               | Work Evaluation             |

Internal code names may still use the old terms temporarily.

User-facing screens should use BashBox terms.

---

### 14.4 Phase 2.1 — Rewrite User-Facing Language

The first implementation step is to update visible text only.

Examples:

* `Start Lab` becomes `Start Task`
* `Submit Lab` becomes `Submit Work`
* `Instructions` becomes `Task Brief`
* `Session Monitor` becomes `Work Session`
* `Nodes` becomes `Assigned Servers`
* `Dashboard` becomes `Employee Workspace`

This should be done without changing the database model or breaking the current start/terminal/submit/grader flow.

---

### 14.5 Phase 2.2 — Add Company and Role Context

Add a clear workplace context to the user-facing experience.

The first version should use:

* Company: `CloudNova Hosting`
* Role: `Junior Linux Administrator`
* Phase: `First Week at Work`
* Manager: `Julian`

This context should appear in the workspace header or task page.

The user should understand:

* Which company they are working for
* Which role they are acting as
* Which work phase they are in
* Who assigned the task

---

### 14.6 Phase 2.3 — Convert the First Lab into a Work Assignment

The existing Basic Server Inspection lab should become the first BashBox work assignment.

The technical task can stay the same:

* Create `server-report.txt`
* Include hostname
* Include current user
* Include operating system information
* Include disk usage
* Include memory usage

But the presentation should change.

Instead of presenting it as a lab exercise, it should be presented as a workplace task from Julian.

Example task framing:

```text
Julian, the Infrastructure Manager at CloudNova Hosting, assigned you your first server inspection task.

A newly provisioned Linux server needs a basic inspection report before it can be handed over to the infrastructure team.

Create a server report file and include the required system information.
```

---

### 14.7 Phase 2.4 — Evolve the Lab Page into an Employee Workspace

The current lab page should gradually become the Employee Workspace.

Target layout:

* Header:

  * Company
  * Role
  * Phase
  * Current assignment
* Left panel:

  * Manager Message
  * Task Brief
  * Requirements
  * Hints
* Center panel:

  * Terminal
* Right panel:

  * Work Session
  * Assigned Servers
  * Time Remaining
* Bottom or modal:

  * Submission Result
  * Feedback

This phase should reuse the existing working page as much as possible.

Do not rewrite the whole UI from scratch.

---

### 14.8 Phase 2.5 — Improve Feedback Style

The current result display should evolve from a generic evaluation result into work-style feedback.

Instead of only showing:

```text
Evaluation Complete
Score: 100
```

The product should move toward:

```text
Work Submitted
Julian's Feedback
Checks Passed
Missing Requirements
Next Task
```

For the first version, the feedback can be static and based on grader output.

AI-generated manager feedback should be added later, after the normal grader and submission flow remain stable.

---

### 14.9 Phase 2 Rules

During Phase 2:

* Keep the current runtime stable.
* Do not break Start Task, Terminal, Submit Work, Grader, or Cleanup.
* Do not rename database tables yet.
* Do not introduce SaaS billing or subscriptions yet.
* Do not introduce multi-tenant architecture yet.
* Do not rewrite the whole app.
* Prefer small commits.
* Test after each meaningful UI or content change.

Phase 2 is complete when the product no longer feels like a generic lab list and starts feeling like a workplace simulation.


## 15. Future BashBox Architecture Direction

The long-term BashBox direction includes:

* Job opportunities
* Hiring confirmation
* Employee workspace
* Company/role/task model
* User progress
* Task submissions
* AI manager feedback
* Multiple lab tasks
* Multiple Linux/DevOps tracks
* Production deployment
* SaaS-ready structure

Future production concerns:

* PostgreSQL instead of local SQLite
* Redis queues
* Background cleanup jobs
* Systemd services
* Reverse proxy with HTTPS/WSS
* Better terminal token security
* Per-user lab accounts inside containers
* Resource limits per container
* Network isolation
* Multi-host lab scheduling
* Monitoring and backups

---

## 16. Documentation Rules

Documentation should stay in English unless explicitly requested otherwise.

Important rules:

* Keep this blueprint aligned with the actual working code.
* Do not describe features as implemented unless they are already working.
* Mark future features clearly as future work.
* Do not mix large architecture changes with small fixes.
* Update this file when the project direction changes.
* Keep old planning files only as historical references unless they are intentionally updated.

---

## 17. Current Source of Truth

This file is the current source of truth for the active BashBox transformation.

The current strategy is:

```text
Stabilize first
Rebrand carefully
Keep the working runtime intact
Move toward BashBox gradually
Avoid risky rewrites
```

If implementation differs from this blueprint, update the blueprint or document the difference before continuing.
