import '/app/models/ApiResponse.dart';
import 'package:get/get.dart';

import 'AppAuthStateService.dart';
import 'MockAuthStateService.dart';
import '/app/shared/services/Services.dart';

abstract class AuthStateService extends BaseService {
  /// Configure if Mock is enabled or not
  static const MOCK_ENABLED = true;

  /// Create and get the instance of [AuthStateService]
  static AuthStateService get instance {
    if (!Get.isRegistered<AuthStateService>()) Get.lazyPut<AuthStateService>(() => MOCK_ENABLED ? MockAuthStateService() : AppAuthStateService());
    return Get.find<AuthStateService>();
  }

  /// Get and refresh user data
  Future<ApiResponse> getUser();

  /// Logout the user from system
  Future<ApiResponse> logout();
}
