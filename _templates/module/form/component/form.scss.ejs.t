---
to: mytheme/assets/components/form/form.scss
---
@use "../../styles/settings" as s;
@use "../../styles/tools" as t;

.c-form {
	display: block;
}

.c-form__stage {
	transition: opacity 0.4s;
}
.c-form__stage.is-hidden {
	opacity: 0;
}
