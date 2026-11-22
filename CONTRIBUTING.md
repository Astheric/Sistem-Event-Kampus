# Contributing to EventHub

Ikuti model cabang, aturan commit, dan alur fase di prompt proyek.

## Model cabang

- `main`: stabil dan siap didemokan. Hanya menerima merge dari `develop` atau `hotfix/*`.
- `develop`: cabang integrasi. Menerima merge dari cabang kerja lewat PR.
- `<type>/<fase>-<deskripsi>`: cabang kerja, dibuat dari `develop`.

Jangan pernah commit langsung ke `main` atau `develop`.

## Aturan commit

Format Conventional Commits: `type(scope): subject`. Subject Bahasa Inggris, kalimat perintah, huruf kecil, tanpa titik, maksimal 72 karakter. Scope salah satu dari: `auth`, `event`, `ticket`, `registration`, `payment`, `checkin`, `certificate`, `committee`, `capacity`, `audience`, `moderation`, `notification`, `audit`, `ui`, `db`, `deps`, `tooling`.

Setiap commit harus atomik dan hijau: lulus `php artisan test --compact` dan `vendor/bin/pint --test`.

## Alur satu fase

1. `git switch develop && git switch -c <type>/<fase>-<deskripsi>`
2. Kerjakan dengan commit kecil.
3. Sebelum PR: `git fetch`, `git rebase develop`, jalankan gerbang kualitas.
4. Tulis deskripsi PR ke `docs/pr/<fase>.md`.
5. Merge lokal ke develop dengan `--no-ff` bila tidak ada remote/gh.
