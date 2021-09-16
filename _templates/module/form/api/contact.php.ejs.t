---
to: mytheme/inc/contact.php
---
<?php
mb_internal_encoding("UTF-8");

define("MAILER_PATH", get_template_directory() . "/inc/mailer");
require_once MAILER_PATH . "/Mailer.php";

use Mailer\Mailer;

add_action("timber/context", function ($context) {
	$form_config = json_decode(
		file_get_contents(
			get_theme_file_path("assets/components/form/inc/form.config.json")
		)
	);
	foreach ($form_config->items as $item) {
		$item->controller = $form_config->controller;
	}
	$context["form_config"] = $form_config;

	return $context;
});

add_action("rest_api_init", function (): void {
	register_rest_route("mytheme", "/contact", [
		[
			"methods" => WP_REST_Server::CREATABLE,
			"callback" => function (
				WP_REST_Request $request
			): WP_REST_Response {
				try {
					$form_config = json_decode(
						file_get_contents(
							get_theme_file_path(
								"assets/components/form/inc/form.config.json"
							)
						)
					);

					$mail = $request->get_param("custom-email");
          $name = $request->get_param("custom-name");
          $body = $request->get_param("body");
          $config = $form_config;
					$subject = $config->subjects;

          Mailer::$DEBUG = $config->debug;
          Mailer::$USE_LOG = $config->log;
					$mailer = new Mailer([
						"body" => $body,
						"user" => $mail,
						"name" => $name,
						"admins" => $config->admins,
						"site_name" => $config->site_name,
					]);

					$mailer->mail->setFrom(
						$config->from->email,
						$config->from->name
					);

					// user
					// =====================================
					$mailer->setupUser(
						$subject,
						MAILER_PATH . "/mail/user.php"
					);
					$mailer->mail->send();

					// admin
					// =====================================
					$mailer->setupAdmin("お問い合わせがありました");
					$mailer->mail->send();

					$response = [
						"message" => "ok",
					];
				} catch (Exception $e) {
					$response = [
						"message" =>
							"Message could not be sent. Mailer Error: ",
						//						"error" => $mailer->mail->ErrorInfo,
						//            $_POST,
						//            $config["mailer"],
					];
				}

				return rest_ensure_response($response);
			},
		],
	]);
});
