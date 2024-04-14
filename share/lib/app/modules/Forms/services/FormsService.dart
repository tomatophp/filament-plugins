import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'package:get/get.dart';

import 'AppFormsService.dart';
import 'MockFormsService.dart';

abstract class FormsService extends BaseService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = false;

  /// Create and get the instance of [FormsService]
  static FormsService get instance {
    if (!Get.isRegistered<FormsService>()) Get.lazyPut<FormsService>(() => MOCK_ENABLED ? MockFormsService() : AppFormsService());
    return Get.find<FormsService>();
  }

  /// Start the server request
  void init(String client);

  /// Stop the server request
  void close(String client);

  /// Do Something
  Future<ApiResponse> index(client, key);

  /// Do Something
  Future<ApiResponse> store(client, endpoint, body, {key});
}

