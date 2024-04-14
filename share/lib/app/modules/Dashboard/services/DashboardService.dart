import 'package:get/get.dart';

import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'AppDashboardService.dart';
import 'MockDashboardService.dart';

abstract class DashboardService extends BaseService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = false;

  /// Create and get the instance of [DashboardService]
  static DashboardService get instance {
    if (!Get.isRegistered<DashboardService>()) Get.lazyPut<DashboardService>(() => MOCK_ENABLED ? MockDashboardService() : AppDashboardService());
    return Get.find<DashboardService>();
  }

  /// Do Something
  Future<ApiResponse> updateNotificationToken(String client, String token, String type);
}
