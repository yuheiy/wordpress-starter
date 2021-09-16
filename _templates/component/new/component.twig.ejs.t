---
to: mytheme/assets/components/<%- h.changeCase.param(name) %>/<%- h.changeCase.param(name) %>.twig
---
<% if(locals.macro){ -%>
{#
	{% from 'components/<%- h.changeCase.param(name) %>/<%- h.changeCase.param(name) %>.twig' import <%- h.changeCase.snake(name) %> as <%- h.changeCase.camel(name) %> %}
	{{ <%- h.changeCase.camel(name) %>() }}
	{{
		<%- h.changeCase.camel(name) %>({
			label: ''
		})
	}}
#}

{% macro <%- h.changeCase.snake(name) %>(options) %}
	{% set label = attribute(options, 'label') %}

	<%- locals.controller ? `<c-${h.changeCase.param(name)} class="c-${h.changeCase.param(name)}"></c-${h.changeCase.param(name)}>` : `<div class="c-${h.changeCase.param(name)}">{{ label|default('${h.changeCase.camel(name)}') }}</div>` %>
{% endmacro %}
<% } else { -%>
{# {% include 'components/<%- h.changeCase.param(name) %>/<%- h.changeCase.param(name) %>.twig' %} #}
<%- locals.controller ? `<c-${h.changeCase.param(name)} class="c-${h.changeCase.param(name)}"></c-${h.changeCase.param(name)}>` : `<div class="c-${h.changeCase.param(name)}">{{ label|default('') }}</div>` %>
<% } -%>

