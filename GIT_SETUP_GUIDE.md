# Git Authentication Setup for VPS Server

## ✅ Current Status
- SSH keys are already generated
- Remote URL changed to SSH: `git@github.com:rstc-iffat/real_gold_farming.git`
- SSH authentication works, but repository access may be limited

## 🔑 Option 1: SSH Keys (Recommended for Servers)

### Step 1: Get Your Public SSH Key
```bash
cat ~/.ssh/id_ed25519.pub
```

**Your current public key:**
```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIABvpdufLRcskgARxxEy0Zlg6sa/g2yFLnmPhJh/QX5k iqararahemad@rstopcoder.com
```

### Step 2: Add SSH Key to GitHub
1. Go to GitHub → Settings → SSH and GPG keys
2. Click "New SSH key"
3. Title: `VPS Server - rg-organic-mart`
4. Paste the public key above
5. Click "Add SSH key"

### Step 3: Verify Access
The SSH key needs to be added to the **rstc-iffat** GitHub account (or the account that owns the repository).

---

## 🔐 Option 2: Personal Access Token (PAT) - Quick Setup

### Step 1: Create GitHub Personal Access Token
1. Go to GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Click "Generate new token (classic)"
3. Name: `VPS Server - rg-organic-mart`
4. Select scopes: `repo` (full control of private repositories)
5. Click "Generate token"
6. **Copy the token immediately** (you won't see it again!)

### Step 2: Update Remote URL with Token
```bash
cd /home/expensi.in/public_html/rg-organic-mart
git remote set-url origin https://YOUR_TOKEN@github.com/rstc-iffat/real_gold_farming.git
```

**Or use credential helper (more secure):**
```bash
# Store credentials
git config --global credential.helper store
git pull origin rstc-iqarar
# Enter username: rstc-iffat
# Enter password: YOUR_PERSONAL_ACCESS_TOKEN
```

---

## 🔄 Option 3: Use Different SSH Key for This Repository

If you have access with a different GitHub account:

### Step 1: Generate New SSH Key
```bash
ssh-keygen -t ed25519 -C "your-email@example.com" -f ~/.ssh/id_ed25519_rstc_iffat
```

### Step 2: Add to SSH Config
```bash
cat >> ~/.ssh/config << EOF
Host github.com-rstc-iffat
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_rstc_iffat
EOF
```

### Step 3: Update Remote URL
```bash
cd /home/expensi.in/public_html/rg-organic-mart
git remote set-url origin git@github.com-rstc-iffat:rstc-iffat/real_gold_farming.git
```

### Step 4: Add New Public Key to GitHub
```bash
cat ~/.ssh/id_ed25519_rstc_iffat.pub
```
Add this key to the **rstc-iffat** GitHub account.

---

## ✅ Test Connection

After setup, test with:
```bash
cd /home/expensi.in/public_html/rg-organic-mart
git fetch origin
git pull origin rstc-iqarar
```

---

## 📝 Common Commands for Live Server

```bash
# Pull latest changes
git pull origin rstc-iqarar

# Merge into current branch
git merge origin/rstc-iqarar

# Check status
git status

# View branches
git branch -a

# Fetch all remote branches
git fetch --all
```

---

## ⚠️ Important Notes for Live Server

1. **Always backup before pulling** on production
2. **Test in staging first** if possible
3. **Use specific branches** (don't pull main directly)
4. **Check for conflicts** before merging
5. **Keep .env file safe** (it's in .gitignore)

---

## 🔧 Current Configuration

- **Remote URL:** `git@github.com:rstc-iffat/real_gold_farming.git`
- **Current Branch:** `rstc-test-live`
- **SSH Key:** `~/.ssh/id_ed25519`
- **Authenticated as:** `iqarar-rstc` (may need access to repository)
