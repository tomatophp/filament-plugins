import 'package:get/get.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/modules/Modules.dart';
import '/app/shared/controllers/AppController.dart';
import '/config/Config.dart';

class DashboardController extends AppController {
  static DashboardController get instance {
    if (!Get.isRegistered<DashboardController>())
      Get.put(DashboardController());
    return Get.find<DashboardController>();
  }

  DashboardService _dashboardService = DashboardService.instance;

  @override
  void onInit() {
    super.onInit();

    if (Config.fireConnectActive) {
      getData();
    }
  }

  Future<void> getData() async {
    String _client = 'dashboard-get-data';
    _dashboardService.init(_client);
    String token;
    String type;
    if (storage.read('fcm-web') != null) {
      token = storage.read('fcm-web');
      type = 'fcm-web';
    } else {
      token = storage.read('fcm-api');
      type = 'fcm-api';
    }
    print(type);
    ApiResponse response =
        await _dashboardService.updateNotificationToken(_client, token, type);
    print(response.data);

    _dashboardService.close(_client);
  }
}
