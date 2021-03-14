---
name: component
root: .
output: .
questions:
    name: Please enter component name.
---

# `my-theme/templates/components/{{ inputs.name | kebab }}.twig`

```twig
<div class="c-{{ inputs.name | kebab }}"></div>

```

# `resources/assets/components/{{ inputs.name | kebab }}.scss`

```scss
@use "../styles/abstracts" as *;

.c-{{ inputs.name | kebab }} {
}

.c-{{ inputs.name | kebab }}__ {
}

```
