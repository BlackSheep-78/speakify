# Rules for Speakify System Flow JSON Structure

This document outlines the rules and guidelines for creating and maintaining the JSON structure used to document API endpoint flows in Speakify (`speakify.system.flow.json`). Adhering to these rules ensures clarity, consistency, and easy maintenance.

---

## Root-Level Structure

At the root level, the JSON file must include:

- `file`: **Required**. String specifying the path to the JSON file itself.
- `description`: **Required**. Brief description of the JSON file purpose.
- API action objects, clearly named according to their purpose (`validate_session`, `register_user`, etc.).
- `files`: **Required**. List of all files involved in the project.
- `functions`: **Required**. List of functions or methods within files, including their relationships.

---

## API Action Objects

Each action object must have these fields:

| Field           | Required | Description |
|-----------------|----------|-------------|
| `description`   | ✅        | Brief summary explaining the API endpoint's purpose. |
| `entry`         | ✅        | URL endpoint that triggers this action. |
| `nodes`         | ✅        | List of components involved. |
| `relationships` | ✅        | List defining interactions between nodes. |
| `flow`          | ✅        | Ordered steps documenting the action's execution path. |

---

## Nodes

Each node must define:

| Field    | Required | Description |
|----------|----------|-------------|
| `id`     | ✅        | Unique identifier for the node. Use descriptive IDs (e.g., `entry_point`). |
| `type`   | ✅        | Type of node. Must be one of: `file`, `class_method`, or `database`. |
| `file`   | ✅ (if applicable) | Path to the relevant file. Required for types `file` and `class_method`. |
| `method` | ✅ (if `class_method`) | Name of the method within the specified file. |
| `table`  | ✅ (if `database`) | Name of the relevant database table. |

**Note:** Each node must have the correct fields according to its type.

---

## Relationships

Define clear, directional interactions between nodes:

| Field    | Required | Description |
|----------|----------|-------------|
| `from`   | ✅        | ID of the originating node. |
| `to`     | ✅        | ID of the destination node. |
| `action` | ✅        | Clearly describes the interaction (e.g., `routes to`, `requires`, `calls`, `queries`). |

---

## Files

Maintain a comprehensive list of all files:

- Each entry should clearly specify the relative path of the file.
- Ensure each file entry is unique and accurately reflects the actual file structure.

---

## Functions

Maintain a clear list of all functions or methods:

- Clearly specify the file each function/method belongs to.
- Describe how each function relates to other functions and files.
- Document method signatures clearly (parameters, return values, etc.).

---

## Flow Steps

Provide step-by-step actions detailing the execution path:

Each step must include:

- **For file-based steps:**
  - `file`: Path to the relevant file.
  - `action`: Short and clear explanation of what happens in the step.

- **For database-based steps:**
  - `db`: Database table involved.
  - `query`: Exact SQL query executed.

---

## Best Practices

- Ensure unique and meaningful node identifiers.
- Maintain consistent naming conventions.
- Clearly document each step to precisely reflect its logic and purpose.
- Regularly validate the JSON structure to prevent errors and ensure consistency.

---



