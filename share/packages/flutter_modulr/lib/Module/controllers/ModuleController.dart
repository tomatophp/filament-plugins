const String stub = """
import 'package:get/get.dart';

import '../../../shared/controllers/AppController.dart';
import '../services/{MODULE}Service.dart';

class {MODULE}Controller extends AppController {
  /// Create and get the instance of the controller
  static {MODULE}Controller get instance {
    if (!Get.isRegistered<{MODULE}Controller>()) Get.put({MODULE}Controller());
    return Get.find<{MODULE}Controller>();
  }

  /// Initialise [{MODULE}Module] service
  final {MODULE}Service _{CAMEL_MODULE}Service = {MODULE}Service.instance;
  
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
