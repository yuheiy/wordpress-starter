---
inject: true
to: package.json
after: dependencies
skip_if: final-form
sh: cd <%= cwd %> && npm install
---
"final-form":"^4.20.2",