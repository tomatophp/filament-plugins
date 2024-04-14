import '/app/models/ApiResponse.dart';
import 'package:get/get.dart';

import 'AppProfileService.dart';
import 'MockProfileService.dart';

abstract class ProfileService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = false;

  /// Create and get the instance of [ProfileService]
  static ProfileService get instance {
    if (!Get.isRegistered<ProfileService>()) Get.lazyPut<ProfileService>(() => MOCK_ENABLED ? MockProfileService() : AppProfileService());
    return Get.find<ProfileService>();
  }

  /// Start the server request
  void init(String client);

  /// Stop the server request
  void close(String client);

  /// Do Something
  Future<ApiResponse> getData(client);

  Future<ApiResponse> update(client, body);

  Future<ApiResponse> password(client, body);
}
