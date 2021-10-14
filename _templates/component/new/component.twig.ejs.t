---
to: mytheme/templates/components/<%- h.changeCase.param(name) %>/<%- h.changeCase.param(name) %>.twig
---
<div class="c-<%- h.changeCase.param(name) %>"<%- locals.controller ? ` data-controller="c-${h.changeCase.param(name)}"` : '' %>>
</div>
