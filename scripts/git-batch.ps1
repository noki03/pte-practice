param (
    [switch]$Push
)

Write-Host "Organizing and Batching Commits..." -ForegroundColor Cyan

# 1. Docs
$docsChanged = (git status --porcelain | Select-String "docs/|CLAUDE.md|\.gitignore")
if ($docsChanged) {
    git add docs/ CLAUDE.md .gitignore
    git commit -m "docs: update documentation, standards, and tasks"
    Write-Host "✅ Committed documentation changes." -ForegroundColor Green
}

# 2. Backend
$backendChanged = (git status --porcelain | Select-String "backend/")
if ($backendChanged) {
    git add backend/
    git commit -m "feat(backend): implement Phase 3 audio upload infrastructure and tests"
    Write-Host "✅ Committed backend changes." -ForegroundColor Green
}

# 3. Frontend
$frontendChanged = (git status --porcelain | Select-String "frontend/")
if ($frontendChanged) {
    git add frontend/
    git commit -m "feat(frontend): implement Phase 3 UI updates"
    Write-Host "✅ Committed frontend changes." -ForegroundColor Green
}

if ($Push) {
    Write-Host "Pushing to remote..." -ForegroundColor Cyan
    git push origin develop
}

Write-Host "Batch commit process complete!" -ForegroundColor Green
