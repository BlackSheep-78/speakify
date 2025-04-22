# SProject Flow JSON Rules

This document outlines the rules for structuring theflow.json` file. Following these guidelines ensures consistency, clarity, and easy maintenance.

---

## Root-Level Format

- The JSON must start with a **root object** containing the following fields:
  - **`file`**: A string representing the file path of this flow document (required).
  - **`description`**: A string briefly describing the purpose of the flow file (required).

- The root object may include **one or more named API action objects**:
  - Examples: `validate_session`, `register_user`.

---

## API Action Objects

Each action object must have:

- **`description`**: A short description of the API endpoint.
- **`entry`**: The full URL path where the endpoint can be called.
- **`flow`**: An ordered list of steps the request follows.

---

## Flow Steps

- The `flow` must be a **non-empty array** of step objects.
- Each step object must be one of the following types:
  
  1. **File-Based Step**:
     ```json
     {
       "file": "path/to/file.php",
       "action": "Description of what happens in this file"
     }
     ```
     - **`file`**: Path to the PHP file involved (required).
     - **`action`**: Short explanation of what the file does (required).

  2. **Database Step**:
     ```json
     {
       "db": "table_name",
       "query": "SQL query executed at this step"
     }
     ```
     - **`db`**: Database table name (required).
     - **`query`**: SQL query string (required).

---

## Additional Guidelines

- **Order matters:** Steps in the `flow` array must follow the sequence of execution.
- **One type per step:** Each step must be either a file-based step or a database step, never both.
- **Consistency:** Use consistent naming, indentation, and formatting to make the file easy to read and update.
- **Validation:** Check that:
  - All required fields are present.
  - `flow` contains valid step objects.
  - SQL queries are valid and match their described purpose.
  - Each `file` or `db` entry is relevant and not duplicated unless necessary.

---

## Example Structure

```json
{
  "file": "speakify/docs/dev/flows/speakify.system.flow.json",
  "description": "Flow documentation for core API endpoints in Speakify",
  "validate_session": {
    "description": "Validates a user session via token",
    "entry": "/public/api/index.php?action=validate_session",
    "flow": [
      {
        "file": "public/api/index.php",
        "action": "Routes to validate_session"
      },
      {
        "file": "backend/actions/validate_session.php",
        "action": "Calls SessionManager::validateToken"
      },
      {
        "db": "sessions",
        "query": "SELECT * FROM sessions WHERE token = ?"
      }
    ]
  }
}
