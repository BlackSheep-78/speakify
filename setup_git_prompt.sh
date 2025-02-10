#!/bin/bash

# Backup existing .bashrc
cp ~/.bashrc ~/.bashrc.backup_$(date +"%Y%m%d_%H%M%S")

# Function to add Git branch info
GIT_PROMPT='parse_git_branch() {
    git branch 2>/dev/null | sed -n "/\* /s///p"
}
PS1="\u@\h:\w \[\033[32m\]\$(parse_git_branch)\[\033[00m\] \$ "'

# Check if the function is already in .bashrc
if ! grep -q "parse_git_branch()" ~/.bashrc; then
    echo -e "\n# Show Git branch in terminal\n$GIT_PROMPT" >> ~/.bashrc
    echo "✅ Git branch display added to ~/.bashrc"
else
    echo "⚠️ Git branch display is already in ~/.bashrc"
fi

# Apply changes
source ~/.bashrc
echo "🔄 Bash configuration reloaded!"

