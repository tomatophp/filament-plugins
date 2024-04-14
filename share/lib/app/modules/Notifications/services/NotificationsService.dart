import 'package:get/get.dart';

import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'AppNotificationsService.dart';
import 'MockNotificationsService.dart';

abstract class NotificationsService extends BaseService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = false;

  /// Create and get the instance of [NotificationsService]
  static NotificationsService get instance {
    if (!Get.isRegistered<NotificationsService>())
      Get.lazyPut<NotificationsService>(() => MOCK_ENABLED
          ? MockNotificationsService()
          : AppNotificationsService());
    return Get.find<NotificationsService>();
  }

  /// Start the server request
  void init(String client);

  /// Stop the server request
  void close(String client);

  /// Do Something
  Future<ApiResponse> index(String client, String? page);

  /// Do Something
  Future<ApiResponse> show(String client, String id);

  /// Do Something
  Future<ApiResponse> delete(String client);
}
