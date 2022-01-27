---
to: views/components/<%- h.changeCase.param(name) %>.twig
---
<div class=""<%- locals.controller ? ` data-controller="${h.changeCase.param(name)}"` : '' %>>
</div>
