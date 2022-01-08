---
to: "<%- locals.controller ? `templates/components/${h.changeCase.param(name)}/${h.changeCase.param(name)}.controller.ts` : null %>"
---
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
}
