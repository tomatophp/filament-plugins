import 'package:get/get.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/shared/views/widgets/ThemeBuilder.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Settings/services/SettingsService.dart';

class SettingsController extends AppController {
  static SettingsController get instance {
    if (!Get.isRegistered<SettingsController>()) Get.put(SettingsController());
    return Get.find<SettingsController>();
  }

  /// Initialise [SettingModule] service
  final SettingsService _settingsService = SettingsService.instance;

  /// Observables
  RxString _selectedTheme = "system".obs;

  /// Getters
  String get selectedTheme => this._selectedTheme.value;

  @override
  void onInit() {
    super.onInit();
    _selectedTheme(storage.read('theme_mode'));
  }

  /// Theme Switcher
  void changeTheme(context, themeMode) {
    _selectedTheme(themeMode);
    ThemeBuilder.of(context)?.changeThemeTo(_selectedTheme.value);
  }


  Future<void> closeAccount() async {
    setBusy(true);
    String _client = "closeAccount";

    try {

      /// Initialize the Service and request server
      _settingsService.init(_client);

      ApiResponse response = await _settingsService.closeAccount(_client);

      print(response.toJson());

      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        setBusy(false);
        return;
      }

      /// Close the Service and request server
      _settingsService.close(_client);

      Toastr.show(message: "${response.message}");

      setBusy(false);

      auth.logoutSilently();
      Get.offAllNamed(AuthRoutes.login);
    } on Exception catch (e) {
      setBusy(false);
      Get.to(() => ErrorPage(message: "${e.toString()}"));
    }
  }


}
