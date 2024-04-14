import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Auth/services/AuthService.dart';

class ResetPasswordController extends AppController {
  /// Create and get the instance of the controller
  static ResetPasswordController get instance {
    if (!Get.isRegistered<ResetPasswordController>()) Get.put(ResetPasswordController());
    return Get.find<ResetPasswordController>();
  }

  final AuthService _authService = AuthService.instance;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController passwordInput = TextEditingController();
  final TextEditingController confirmPasswordInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();

    checkOtp();
  }

  Future<void> checkOtp() async {
    var checkOTP = storage.read("otp");
    if(checkOTP != true){
      Get.offAllNamed(AuthRoutes.login);
    }
  }

  Future<void> reset() async {
    String _client = "forgetSubmit";
    if (!formKey.currentState!.validate()) {
      Toastr.show(message: "Please fill all the required fields!");
      return;
    }

    try {
      /// Prepare form data to be sent to server
      Map<String, dynamic> body = {
        "email": storage.read("email"),
        "otp_code": storage.read("otp_code"),
        "password": passwordInput.text,
        "password_confirmation": confirmPasswordInput.text
      };


      /// Initialize the Service and request server
      _authService.init(_client);

      /// Call api to login user
      ApiResponse response = await _authService.password(body: body, client: _client);

      // log.w(response.data);
      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        return;
      }

      if(response.status == true){
        Toastr.show(message: "${response.message}");

        storage.remove("email");
        storage.remove("otp");
        storage.remove("otp_code");

        /// Redirect user
        Get.offAllNamed(AuthRoutes.login);
      }
      else {
        Toastr.error(message: "${response.message}");
        return;
      }

      /// Close the Service and request server
      _authService.close(_client);

    } on Exception catch (e) {
      Get.to(() => ErrorPage(message: "${e.toString()}"));
    }
  }
}

