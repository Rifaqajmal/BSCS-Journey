@echo off

mkdir lib\core\constants
mkdir lib\core\utils
mkdir lib\core\routes
mkdir lib\models
mkdir lib\services
mkdir lib\providers
mkdir lib\screens\splash
mkdir lib\screens\auth
mkdir lib\screens\home
mkdir lib\screens\birthday
mkdir lib\screens\settings
mkdir lib\widgets

type nul > lib\main.dart
type nul > lib\app.dart
type nul > lib\core\constants\app_colors.dart
type nul > lib\core\constants\app_strings.dart
type nul > lib\core\constants\app_theme.dart
type nul > lib\core\utils\age_calculator.dart
type nul > lib\core\utils\date_formatter.dart
type nul > lib\core\utils\validators.dart
type nul > lib\core\routes\app_routes.dart
type nul > lib\models\user_model.dart
type nul > lib\models\birthday_model.dart
type nul > lib\services\auth_service.dart
type nul > lib\services\database_service.dart
type nul > lib\services\notification_service.dart
type nul > lib\services\api_service.dart
type nul > lib\services\hive_service.dart
type nul > lib\services\biometric_service.dart
type nul > lib\providers\user_provider.dart
type nul > lib\providers\birthday_provider.dart
type nul > lib\screens\splash\splash_screen.dart
type nul > lib\screens\auth\login_screen.dart
type nul > lib\screens\auth\signup_screen.dart
type nul > lib\screens\home\home_screen.dart
type nul > lib\screens\birthday\add_edit_birthday_screen.dart
type nul > lib\screens\birthday\birthday_list_screen.dart
type nul > lib\screens\birthday\birthday_detail_screen.dart
type nul > lib\screens\settings\settings_screen.dart
type nul > lib\widgets\custom_button.dart
type nul > lib\widgets\custom_textfield.dart
type nul > lib\widgets\birthday_card.dart
type nul > lib\widgets\age_display_card.dart
type nul > lib\widgets\loading_widget.dart

echo Done! All folders and files created.
pause
