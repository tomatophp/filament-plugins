const String stub = """
import '../../../models/ApiResponse.dart';
import '../../../shared/services/Services.dart';
import 'package:get/get.dart';

import 'App{MODULE}Service.dart';
import 'Mock{MODULE}Service.dart';

abstract class {MODULE}Service extends BaseService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = true;

  /// Create and get the instance of [{MODULE}Service]
  static {MODULE}Service get instance {
    if (!Get.isRegistered<{MODULE}Service>()) Get.lazyPut<{MODULE}Service>(() => MOCK_ENABLED ? Mock{MODULE}Service() : App{MODULE}Service());
    return Get.find<{MODULE}Service>();
  }
  
  /// Do Something
  Future<ApiResponse> doSomething();
}
""";
