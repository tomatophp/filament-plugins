import 'package:get/get.dart';

import '/app/shared/controllers/AppController.dart';

class HomeControllerController extends AppController {
  /// Create and get the instance of the controller
  static HomeControllerController get instance {
    if (!Get.isRegistered<HomeControllerController>()) Get.put(HomeControllerController());
    return Get.find<HomeControllerController>();
  }

  /// Observables
  var _exampleBool = false.obs;

  /// Getters
  bool get exampleBool => _exampleBool.value;

  @override
  void onInit() {
    super.onInit();
    /// Do something here
  }

  void exampleMethod() {
    // TODO: implement exampleMethod
    throw UnimplementedError();
  }
}

