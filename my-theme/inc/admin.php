<?php

declare(strict_types=1);

add_action("admin_menu", function (): void {
  remove_menu_page("edit.php");
  remove_menu_page("edit-comments.php");
});

add_action("wp_dashboard_setup", function (): void {
  remove_action("welcome_panel", "wp_welcome_panel");
  remove_meta_box("dashboard_primary", "dashboard", "side");
  remove_meta_box("dashboard_quick_press", "dashboard", "side");
  remove_meta_box("dashboard_site_health", "dashboard", "normal");
  remove_meta_box("dashboard_right_now", "dashboard", "normal");
  remove_meta_box("dashboard_activity", "dashboard", "normal");
});
