import '../../../models/ApiResponse.dart';
import '../../../shared/services/Services.dart';
import 'package:get/get.dart';

import 'AppLocationService.dart';
import 'MockLocationService.dart';

abstract class LocationService extends BaseService {
  /// Configure if Mock is enabled or not @accepts[true|false]
  static const MOCK_ENABLED = true;

  /// Create and get the instance of [LocationService]
  static LocationService get instance {
    if (!Get.isRegistered<LocationService>()) Get.lazyPut<LocationService>(() => MOCK_ENABLED ? MockLocationService() : AppLocationService());
    return Get.find<LocationService>();
  }
  
  /// Do Something
  Future<ApiResponse> doSomething();
}

