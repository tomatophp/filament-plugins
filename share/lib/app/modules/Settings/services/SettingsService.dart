import 'package:get/get.dart';

import '/app/models/ApiResponse.dart';
import 'AppSettingsService.dart';
import 'MockSettingsService.dart';

abstract class SettingsService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = false;

  /// Create and get the instance of [SettingsService]
  static SettingsService get instance {
    if (!Get.isRegistered<SettingsService>()) Get.lazyPut<SettingsService>(() => MOCK_ENABLED ? MockSettingsService() : AppSettingsService());
    return Get.find<SettingsService>();
  }

  /// Start the server request
  void init(String client);

  /// Stop the server request
  void close(String client);

  Future<ApiResponse> closeAccount(client);
}
