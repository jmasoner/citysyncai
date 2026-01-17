# How to Start a New Project - Step-by-Step Guide

## 📋 Overview

This guide walks you through the complete process of starting a new project from scratch, including Git/GitHub setup, project structure, and best practices.

---

## 🎯 Step 1: Close Out Current Project

### A. Finalize Current Project
- [ ] Commit all changes
- [ ] Push to GitHub
- [ ] Create a status document (like `CITYSYNCAI_STATUS.md`)
- [ ] Tag a release (optional): `git tag v1.0.0` and `git push origin v1.0.0`

### B. Document Current State
- Create a `PROJECT_STATUS.md` or `STATUS.md` file
- Document what's complete
- Document what's pending
- Note the stopping point

### C. You're Done!
- Close the project folder (optional)
- No need to delete anything - everything is saved in Git

---

## 🚀 Step 2: Plan Your New Project

### Questions to Answer:
1. **What's the project name?** (e.g., "webdisk-deployment-tool")
2. **What's the project purpose?** (brief description)
3. **What technology/language?** (PHP, Python, Node.js, etc.)
4. **Where will it live?** (local path, e.g., `C:\Users\john\OneDrive\MyProjects\project-name`)

---

## 📁 Step 3: Create Project Folder

### Option A: Command Line (Recommended)
```bash
# Navigate to your projects directory
cd C:\Users\john\OneDrive\MyProjects

# Create new project folder
mkdir project-name
cd project-name
```

### Option B: Windows File Explorer
1. Navigate to `C:\Users\john\OneDrive\MyProjects`
2. Right-click → New → Folder
3. Name it your project name (use lowercase, hyphens: `webdisk-deployment-tool`)

---

## 🔧 Step 4: Initialize Git Repository

```bash
# Make sure you're in the project folder
cd C:\Users\john\OneDrive\MyProjects\project-name

# Initialize Git
git init

# Create initial .gitignore (if needed)
# (We'll create this together)
```

---

## 📝 Step 5: Create Project Structure

### Standard Structure:
```
project-name/
├── README.md              # Project description, setup instructions
├── .gitignore            # Files to ignore in Git
├── LICENSE               # License file (if open source)
├── docs/                 # Documentation
├── src/                  # Source code (or just root if simple)
├── tests/                # Test files (if applicable)
└── config/               # Configuration files
```

### For WordPress/Small Projects:
```
project-name/
├── README.md
├── .gitignore
└── [main files]
```

---

## 🌐 Step 6: Create GitHub Repository

### Option A: Via GitHub Website (Recommended)
1. Go to https://github.com/new
2. **Repository name:** `project-name` (match your folder name)
3. **Description:** Brief project description
4. **Visibility:** Private (unless open source)
5. **Initialize:** 
   - ❌ Don't add README (we'll create one)
   - ❌ Don't add .gitignore (we'll create one)
   - ❌ Don't add license (unless needed)
6. Click **Create repository**

### Option B: Via GitHub CLI (if installed)
```bash
gh repo create project-name --private --source=. --remote=origin --push
```

---

## 🔗 Step 7: Connect Local Project to GitHub

After creating the GitHub repo, GitHub will show you commands. Use these:

```bash
# Make sure you're in your project folder
cd C:\Users\john\OneDrive\MyProjects\project-name

# Add the remote (replace with your GitHub username)
git remote add origin https://github.com/jmasoner/project-name.git

# OR if you want to use SSH (if set up):
# git remote add origin git@github.com:jmasoner/project-name.git

# Verify it was added
git remote -v
```

---

## 📄 Step 8: Create Initial Files

### A. README.md
```markdown
# Project Name

Brief description of what this project does.

## Features

- Feature 1
- Feature 2

## Installation

[Setup instructions]

## Usage

[How to use it]

## Requirements

- Requirement 1
- Requirement 2
```

### B. .gitignore
Create based on your project type. Examples:

**For PHP:**
```
*.log
*.tmp
*.swp
.DS_Store
vendor/
.env
node_modules/
```

**For Node.js:**
```
node_modules/
.env
*.log
.DS_Store
dist/
build/
```

**For Python:**
```
__pycache__/
*.pyc
.env
venv/
*.log
.DS_Store
```

---

## 💾 Step 9: Initial Commit

```bash
# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Project setup

- Created project structure
- Added README and documentation
- Set up Git repository"

# Push to GitHub (if main branch)
git branch -M main
git push -u origin main

# OR if default branch is different
git push -u origin main
```

---

## ✅ Step 10: Verify Everything Works

1. **Check GitHub:** Visit https://github.com/jmasoner/project-name
   - Should see your README.md
   - Files should be there

2. **Test Git:**
   ```bash
   git status
   git log
   git remote -v
   ```

---

## 📚 Step 11: Create Project Documentation

Create initial documentation:
- `README.md` - Project overview
- `SETUP.md` - Setup instructions
- `CHANGELOG.md` - Version history (optional)

---

## 🔄 Workflow Going Forward

### Daily Workflow:
```bash
# Make changes to files
# ...

# Check what changed
git status

# Add changes
git add .

# OR add specific files
git add file1.php file2.php

# Commit with descriptive message
git commit -m "Add feature: description of what you did"

# Push to GitHub
git push
```

### Good Commit Messages:
- ✅ "Add feature: user authentication"
- ✅ "Fix bug: form validation error"
- ✅ "Update: improve API response handling"
- ❌ "updates"
- ❌ "fix stuff"
- ❌ "changes"

---

## 📋 Quick Checklist for New Projects

- [ ] Plan project (name, purpose, tech stack)
- [ ] Create project folder
- [ ] Initialize Git (`git init`)
- [ ] Create GitHub repository (via website)
- [ ] Connect local to GitHub (`git remote add origin`)
- [ ] Create README.md
- [ ] Create .gitignore
- [ ] Create initial project structure
- [ ] Initial commit
- [ ] Push to GitHub
- [ ] Verify on GitHub website

---

## 💡 Best Practices

1. **Use descriptive names:** `webdisk-deployment-tool` not `tool1`
2. **Use lowercase with hyphens** for project names
3. **Commit often:** Small, frequent commits are better
4. **Write good commit messages:** Describe what and why
5. **Keep README updated:** First thing people see
6. **Use .gitignore:** Don't commit secrets, logs, dependencies
7. **Document as you go:** Easier than documenting later

---

## 🎓 Example: Starting "Web Disk Deployment Tool"

Let's follow this process together for your new project!


