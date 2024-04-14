const String stub = """
import 'package:get/get.dart';

import '../../../shared/controllers/AppController.dart';

class {CONTROLLER}Controller extends AppController {
  /// Create and get the instance of the controller
  static {CONTROLLER}Controller get instance {
    if (!Get.isRegistered<{CONTROLLER}Controller>()) Get.put({CONTROLLER}Controller());
    return Get.find<{CONTROLLER}Controller>();
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
""";
