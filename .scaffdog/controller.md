---
name: controller
root: .
output: .
questions:
    name: Please enter controller name.
---

# `resources/assets/controllers/{{ inputs.name | kebab }}.js`

```javascript
import { Controller } from "stimulus";

export default class extends Controller {
}

```