#!/bin/bash

# Usage: ./generate_file_structure.sh [root_folder] [output_file]
ROOT_DIR="${1:-.}"
OUTPUT_FILE="${2:-file_structure.json}"

echo "{" > "$OUTPUT_FILE"
echo "  \"structure\": [" >> "$OUTPUT_FILE"

# Find and exclude anything in .git directories
find "$ROOT_DIR" \
    -path '*/.git*' -prune -o \
    -type d -print | sed 's/^/    { "directory": "/;s/$/" },/' >> "$OUTPUT_FILE"

find "$ROOT_DIR" \
    -path '*/.git*' -prune -o \
    -type f -print | sed 's/^/    { "file": "/;s/$/" },/' >> "$OUTPUT_FILE"

# Remove the last comma to ensure valid JSON
sed -i '$ s/},/}/' "$OUTPUT_FILE"

echo "  ]" >> "$OUTPUT_FILE"
echo "}" >> "$OUTPUT_FILE"

echo "✅ File structure saved to: $OUTPUT_FILE"
