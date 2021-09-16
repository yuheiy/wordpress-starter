---
to: mytheme/assets/components/form/form.twig
---
{# {% include 'components/form/form.twig' %} #}

{% macro input(items) %}
	{% import 'components/form/form-item.twig' as inputItem %}
	<template data-targets="c-form.fields" data-target="c-form.fieldInput">
		<form id="fieldInput"
			data-action="submit:c-form#confirm"
			class="o-stack-12">
			{% for item in items %}
				{{ inputItem.inputItem(item) }}
			{% endfor %}
			<button type="submit">Confirm</button>
		</form>
	</template>
{% endmacro %}
{% macro confirm(items) %}
	{% import 'components/form/form-item.twig' as inputItem %}
	<template data-targets="c-form.fields" data-target="c-form.fieldConfirm">
		<form id="fieldConfirm" data-action="submit:c-form#submit">
			<div class="o-stack-12">
				{% for item in items %}
					{{ inputItem.output(item) }}
				{% endfor %}
			</div>
			<div class="">
				<button type="button" data-action="click:c-form#back">Back</button>
				<button type="submit">Submit</button>
			</div>
		</form>
	</template>
{% endmacro %}
{% macro complte() %}
	<template data-targets="c-form.fields" data-target="c-form.fieldComplete">
		<div id="fieldComplete">
			<div class="o-stack-16">
				<h3>
					Complete
				</h3>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab
					accusantium amet debitis deleniti ducimus esse eum expedita explicabo
					harum iusto maiores nam odio quaerat qui, tenetur veritatis voluptas
					voluptate voluptates.
				</p>
			</div>
		</div>
	</template>
{% endmacro %}
{% macro error() %}
	<template data-targets="c-form.fields" data-target="c-form.fieldError">
		<div id="fieldError">
			<div class="o-stack-16">
				<h3 style="color: red;">
					Error!!
				</h3>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab
					accusantium amet debitis deleniti ducimus esse eum expedita explicabo
					harum iusto maiores nam odio quaerat qui, tenetur veritatis voluptas
					voluptate voluptates.
				</p>
			</div>
		</div>
	</template>
{% endmacro %}

<c-form class="c-form">
	<div class="c-form__stage" data-target="c-form.stage"></div>
	{{ _self.input(form_config.items) }}
	{{ _self.confirm(form_config.items) }}
	{{ _self.complte }}
	{{ _self.error }}

	<p class="u-mt-64">
		For debug
	</p>
	<div class="o-switcher u-pb-24">
		<button data-action="click:c-form#changeView" data-key="fieldInput">
			Input
		</button>
		<button data-action="click:c-form#changeView" data-key="fieldConfirm">
			Confirm
		</button>
		<button data-action="click:c-form#changeView" data-key="fieldComplete">
			Complete
		</button>
		<button data-action="click:c-form#changeView" data-key="fieldError">
			Error
		</button>
	</div>
</c-form>
