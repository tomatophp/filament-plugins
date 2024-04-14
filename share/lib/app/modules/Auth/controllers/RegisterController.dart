import 'package:flutter/material.dart';
import '/config/Config.dart';
import '/app/helpers/Global.dart';
import '/app/models/UserModel.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Auth/services/AuthService.dart';
import 'LoginController.dart';

class RegisterController extends AppController {
  static RegisterController get instance {
    if (!Get.isRegistered<RegisterController>()) Get.put(RegisterController());
    return Get.find<RegisterController>();
  }

  final LoginController loginController = LoginController.instance;
  final AuthService _authService = AuthService.instance;

  /// Observable
  var _selectedState = 0.obs;

  /// Getters
  int get selectedState => _selectedState.value;

  /// Variables
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController nameInput = TextEditingController();
  final TextEditingController emailInput = TextEditingController();
  final TextEditingController phoneInput = TextEditingController();
  final TextEditingController passwordInput = TextEditingController();
  final TextEditingController confirmPasswordInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();
  }

  Future<void> submit() async {
    setBusy(true);
    if (!formKey.currentState!.validate()) {
      return;
    }
    String _client = "registerSubmit";

    try {
      Map<String, dynamic> body = UserModel(
        name: nameInput.text?? "",
        email: emailInput.text?? "",
        password: passwordInput.text?? "",
        password_confirmation: confirmPasswordInput.text?? "",
        phone: phoneInput.text?? "",
      ).toJson();

      /// Initialize the Service and request server
      _authService.init(_client);

      ApiResponse response = await _authService.register(body: body, client: _client);

      print(response.toJson());

      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.show(message: "${response.message}");
        setBusy(false);
        return;
      }

      /// Close the Service and request server
      _authService.close(_client);

      Toastr.show(message: "${response.message}");

      setBusy(false);
      /// Login the user after registration

      if(Config.requiredOTP){
        storage.write("email", emailInput.text);
        storage.write("activated", true);
        Get.offAllNamed(AuthRoutes.otp);
      }
      else {
        Get.offAllNamed(AuthRoutes.login);
      }

    } on Exception catch (e) {
      setBusy(false);
      Get.to(() => ErrorPage(message: "${e.toString()}"));
    }
  }

  void onStateSelect(int state) {
    _selectedState(state);
  }
}
