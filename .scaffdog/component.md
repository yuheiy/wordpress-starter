---
name: component
root: .
output: .
questions:
    name: Please enter component name.
---

# `my-theme/templates/partial/{{ inputs.name | kebab }}.twig`

```twig
<div class="{{ inputs.name | kebab }}"></div>

```

# `resources/assets/components/{{ inputs.name | kebab }}.scss`

```scss
@use "../styles/abstracts" as *;

.{{ inputs.name | kebab }} {
}

.{{ inputs.name | kebab }}__ {
}

```
