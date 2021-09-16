---
to: mytheme/assets/components/form/inc/form.config.json
---
{
	"debug": true,
	"log": false,
	"from": {
		"email": "reply@examle.com",
		"name": "お問い合わせフォーム"
	},
	"admins": ["info@examle.com"],
	"subjects": "お問い合わせありがとうございます",
	"site_name": "SITE NAME",
	"post": "/wp-json/mytheme/contact",
	"post_constants": ["custom-name", "custom-email"],
	"has_confirm": true,
	"error_messages": {
		"base": {
			"require": "入力して",
			"phone": "違うよ",
			"email": "違うよ",
			"same": "同じじゃないよ",
			"same:email": "email同じじゃないよ",
			"min": "たりない",
			"max": "おおい"
		},
		"custom-email.require": "Oh!",
		"custom-re_email.require": "Ohh!",
		"custom-re_email.same:email": "hmm...",
		"custom-message.min:5": "たりないたりない!",
		"custom-message.max": "Ohhhhhhh!"
	},
	"initialValues": {
		"custom-selection1": "1",
		"custom-selection2": "2",
		"custom-name": "Foo",
		"custom-email": "aa@aa.aa",
		"custom-re_email": "aa@aa.aa",
		"custom-checkbox": [true, false, true],
		"custom-radio": "1",
		"custom-message": "a\na\na\n"
	},
	"controller": "c-form",
	"items": [
		{
			"label": "Selection1",
			"name": "custom-selection1",
			"type": "select",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "on",
			"validates": ["require"],
			"options": {
				"data": [
					{
						"value": "",
						"label": "選択してください"
					},
					{
						"value": "1",
						"label": "custom-checkbox, custom-radio"
					},
					{
						"value": "2",
						"label": "custom-phone, custom-re_phone"
					},
					{
						"value": "3",
						"label": "NONE"
					}
				],
				"depended_on": [
					{
						"targets": ["custom-checkbox"],
						"value": ["1"]
					},
					{
						"targets": ["custom-phone", "custom-re_phone"],
						"value": ["2"]
					}
				]
			}
		},
		{
			"label": "Selection2",
			"name": "custom-selection2",
			"type": "select",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "on",
			"validates": ["require"],
			"options": {
				"data": [
					{
						"value": "",
						"label": "選択してください"
					},
					{
						"value": "1",
						"label": "A"
					},
					{
						"value": "2",
						"label": "custom-message"
					},
					{
						"value": "3",
						"label": "NONE"
					}
				],
				"depended_on": [
					{
						"targets": ["custom-message"],
						"value": ["2"]
					}
				]
			}
		},
		{
			"label": "Your Name",
			"name": "custom-name",
			"type": "text",
			"placeholder": "姓名",
			"autocomplete": "on",
			"validates": ["require"]
		},
		{
			"label": "Email address",
			"name": "custom-email",
			"type": "email",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "on",
			"validates": ["require", "email"]
		},
		{
			"label": "Re Email address",
			"name": "custom-re_email",
			"type": "email",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "off",
			"validates": ["require", "same:custom-email"]
		},
		{
			"label": "phone",
			"name": "custom-phone",
			"type": "tel",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "on",
			"validates": ["phone"]
		},
		{
			"label": "Re phone",
			"name": "custom-re_phone",
			"type": "tel",
			"placeholder": "contact@shiftbrain.com",
			"autocomplete": "on",
			"validates": ["same:custom-phone"]
		},
		{
			"label": "Checkbox",
			"name": "custom-checkbox",
			"type": "checkbox",
			"placeholder": "",
			"autocomplete": "",
			"validates": ["require", "min:2"],
			"options": {
				"data": [
					{
						"value": "1",
						"label": "A"
					},
					{
						"value": "2",
						"label": "B"
					},
					{
						"value": "3",
						"label": "C"
					}
				],
				"depended_on": [
					{
						"targets": ["custom-radio"],
						"value": ["3"]
					}
				]
			}
		},
		{
			"label": "Radio",
			"name": "custom-radio",
			"type": "radio",
			"placeholder": "",
			"autocomplete": "",
			"validates": ["require"],
			"options": {
				"data": [
					{
						"value": "1",
						"label": "A"
					},
					{
						"value": "2",
						"label": "B"
					},
					{
						"value": "3",
						"label": "C"
					}
				]
			}
		},
		{
			"label": "Message",
			"name": "custom-message",
			"type": "textarea",
			"placeholder": "Foo",
			"autocomplete": "off",
			"validates": ["min:5", "max:10"]
		}
	]
}
