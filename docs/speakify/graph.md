# 📜 /speakify/backend/docs/graph.md
# 📜 JSON Graph Rules for Entity and Link Representation

## 🎯 Objective
This document defines a **strict schema** and **ruleset** for generating and maintaining a consistent JSON file that models a graph of:
- **Entities** (`files`, `folders`, `functions`)
- **Links** (relationships between entities)

The purpose is to allow reliable rendering, parsing, and use across multiple tools, systems, and workflows.

> 🧩 This file works in conjunction with:
> - `graph.template.json`: A starter reference with example content
> - `graph.json`: The generated data structure storing the full project graph
>
> These three together define and generate a real-time map of the project tree and code relationships.

---

## 📂 What `graph.json` Represents

The `graph.json` file will store the **structure of the entire project**, including:

- 📁 **Project Tree**:
  - Which folder belongs to whom (hierarchy via `contains` links)
  - Which files are inside which folders

- 📄 **File Relationships**:
  - Which file includes, uses, or imports another
  - Which file calls functions from others

- 🧠 **Function Relationships**:
  - Which function calls or includes which
  - Grouping functions by file, type, or module

- 🌐 **Endpoint Tracing**:
  - For a given API endpoint or URL (e.g. `/api?action=validate_session`), the flow of triggered code is traceable through linked entities

This enables:
- Visual or programmatic dependency mapping
- Function call tree exploration
- Folder ownership hierarchy
- Full codebase flow trace from any starting point (e.g. endpoint, UI view, CLI)

---

## 🧱 JSON Format Overview
Each graph follows this base structure:
```json
{
  "version": "1.0.0",
  "generated": "<ISO8601 datetime>",
  "entities": [],
  "links": []
}
```

---

## 🧩 Entities
Each entity must follow this format:
```json
{
  "id": "unique-id",
  "type": "file | folder | function",
  "name": "EntityName",
  "path": "/absolute/or/relative/path",
  "meta": {
    "created": "<ISO8601 date>",
    "modified": "<ISO8601 date>",
    "language": "optional-language-tag",
    "tags": ["optional", "tag", "array"]
  }
}
```

### Required Fields
- `id`: Unique slug or UUID across the graph
- `type`: One of `file`, `folder`, or `function`
- `name`: Human-readable name (e.g., `app.js`)
- `path`: Relative or absolute path (Unix style recommended)

### Optional Fields (in `meta`)
- `created`, `modified`: ISO 8601 format (e.g., `2025-04-01T12:00:00Z`)
- `language`: For `file` or `function` (e.g., `php`, `js`)
- `tags`: Free-form classification keywords

---

## 🔗 Links
Each link defines a directed relationship between two existing entities:
```json
{
  "source": "entity-id-A",
  "target": "entity-id-B",
  "relation": "uses | contains | calls | inherits | imports | exports | includes"
}
```

### Rules
- `source` and `target` **must exist** in the `entities` array
- `relation` must match one of the predefined values (case-sensitive)

### Supported `relation` Types
| Type     | Meaning                               |
|----------|----------------------------------------|
| `uses`   | General dependency                     |
| `calls`  | Function invocation                    |
| `contains` | Folder or file containing another     |
| `imports`| Module import                          |
| `exports`| Module export                          |
| `includes`| Template inclusion or code reference   |
| `inherits`| Class inheritance                      |

---

## 🧪 Validation
- Every `id` must be unique
- All referenced IDs in `links` must exist in `entities`
- All dates must be in valid ISO 8601 format

---

## 📁 Files
- Rules → `docs/graph.md`
- Template → `resources/graph/graph.template.json`
- Data → `resources/graph/graph.json`

---

## 📦 Example
See `graph.template.json` for a complete, copy-pasteable starter structure.

## Json structure template
```json
{
  
    "version": "1.0.0",
    "generated": "2025-04-01T12:00:00Z",
    "file": "/speakify/docs/graph.json",
    "description": "This file works in conformity with graph.md and should be viewed using graph-viewer.html.",
    "entities": [
      {
        "id": "app-js",
        "type": "file",
        "name": "app.js",
        "path": "/public/js/app.js",
        "meta": {
          "created": "2025-03-28T09:00:00Z",
          "modified": "2025-04-01T11:30:00Z",
          "language": "js",
          "tags": ["session", "playback"]
        }
      },
      {
        "id": "session-manager-validate",
        "type": "function",
        "name": "SessionManager::validate",
        "path": "/backend/classes/SessionManager.php",
        "meta": {
          "created": "2025-03-20T11:00:00Z",
          "language": "php",
          "tags": ["session"]
        }
      },
      {
        "id": "classes-folder",
        "type": "folder",
        "name": "classes",
        "path": "/backend/classes",
        "meta": {
          "created": "2025-03-01T09:00:00Z",
          "tags": ["backend"]
        }
      }
    ],
    "links": [
      {
        "source": "app-js",
        "target": "session-manager-validate",
        "relation": "calls"
      },
      {
        "source": "classes-folder",
        "target": "session-manager-validate",
        "relation": "contains"
      }
    ]
  }
  ```

---

## 📌 Root Entity

Every graph must include a root `folder` entity (e.g., `"speakify"`), which contains all top-level folders/files.

```json
{
  "id": "speakify-root",
  "type": "folder",
  "name": "speakify",
  "path": "/speakify",
  "meta": {
    "created": "2025-03-01T08:00:00Z",
    "tags": ["root"]
  }
}
```

---

## 🧠 Logical Entities

Entities that represent abstract runtime units or flows may use a generic `"type"` such as `"concept"` or `"logical"`.

Example:
```json
{
  "id": "TranslationBlock",
  "type": "concept",
  "name": "TranslationBlock",
  "path": "/virtual",
  "meta": { "tags": ["runtime", "playback"] }
}
```

---

## 🧬 ID Naming Recommendations

To maintain consistency and traceability, follow this pattern:
- Folder: `folder-name` or `folder-pathname` (slugged)
- File: `file-name` or `hash8-file-name`
- Function: `Class::Method` or `file::function`

Examples:
```json
{ "id": "backend-folder" }
{ "id": "app-js" }
{ "id": "SessionManager::validate" }
```

---

## 🎨 Visual Rendering Guidelines

These are suggested styles for tools like `graph-viewer.html`:

### Nodes
| Type        | Color       | Shape         |
|-------------|-------------|---------------|
| folder      | #bbf7d0     | rounded box   |
| file        | #dbeafe     | rounded box   |
| function    | #fcd34d     | ellipse       |
| concept     | #fca5a5     | diamond       |

### Edges
- `contains`: solid line
- `calls`, `uses`, `imports`: dashed or curved line
- Tooltips should include `path`, `tags`, and `language` if applicable.

---

## 🧭 Hierarchy Rules

- Use only immediate `contains` links (no deep nesting shortcuts).
- Folders must be explicitly declared in `entities`.
- Each file must belong to a parent folder.
