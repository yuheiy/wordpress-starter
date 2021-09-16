---
to: mytheme/assets/components/form/form-item.twig
---
{#
	{% from 'components/form/form-item.twig' import form_item as formItem %}
#}

{% macro label(item) %}
	<span>
		<span>{{ item.label }}</span>
		{% if 'require' in item.validates %}
			<span style="color: red;">*</span>
		{% endif %}
	</span>
{% endmacro %}

{% macro error(item) %}
	<span style="color: red; display: none" data-error="{{ item.name }}"></span>
{% endmacro %}

{% macro textarea(item) %}
	<label for="form-item-{{ item.name }}" class="u-d-block">
		{{ _self.label(item) }}
	</label>
	<textarea name="{{ item.name }}"
		id="form-item-{{ item.name }}"
		cols="30"
		rows="10"
		data-targets="{{ item.controller }}.inputs"
		style="border: 1px solid"></textarea>
{% endmacro %}

{% macro boxes(item) %}
	<fieldset>
		<legend>
			{{ _self.label(item) }}
		</legend>
		<ol>
			{% for d in item.options.data %}
				{% set name = item.type == 'radio'
					? item.name
					: item.name ~ '[' ~ loop.index0 ~ ']'
				%}
				<li class="u-d-inline-block">
					<input type="{{ item.type }}"
						name="{{ name }}"
						value="{{ d.value }}"
						id="form-item-{{ item.name }}-{{ loop.index0 }}"
						data-targets="{{ item.controller }}.inputs"
						style="border: 1px solid" />
					<label for="form-item-{{ item.name }}-{{ loop.index0 }}">
						<span>{{ d.label }}</span>
					</label>
				</li>
			{% endfor %}
		</ol>
	</fieldset>
{% endmacro %}

{% macro select(item) %}
	<label for="form-item-{{ item.name }}" class="u-d-block">
		{{ _self.label(item) }}
	</label>
	<select name="{{ item.name }}"
		id="form-item-{{ item.name }}"
		data-targets="{{ item.controller }}.inputs"
		style="border: 1px solid">
		{% for d in item.options.data %}
			<option value="{{ d.value }}">
				{{ d.label }}
			</option>
		{% endfor %}
	</select>
{% endmacro %}

{% macro input(item) %}
	<label for="form-item-{{ item.name }}" class="u-d-block">
		{{ _self.label(item) }}
	</label>
	<input type="{{ item.type }}"
		data-targets="{{ item.controller }}.inputs"
		id="form-item-{{ item.name }}"
		name="{{ item.name }}"
		style="border: 1px solid" />
{% endmacro %}

{% macro inputItem(item) %}
	<div data-form-item>
		{% if item.type == 'textarea' %}
			{{ _self.textarea(item) }}
		{% elseif item.type == 'select' %}
			{{ _self.select(item) }}
		{% elseif item.type == 'radio' or item.type == 'checkbox' %}
			{{ _self.boxes(item) }}
		{% else %}
			{{ _self.input(item) }}
		{% endif %}
		{{ _self.error(item) }}
	</div>
{% endmacro %}

{% macro output(item) %}
	<div data-form-item>
		<p>
			{{ item.label }}
		</p>
		<div data-targets="c-form.outputs" data-name="{{ item.name }}"></div>
	</div>
{% endmacro %}