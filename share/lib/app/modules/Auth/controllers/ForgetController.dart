import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Auth/services/AuthService.dart';

class ForgetController extends AppController {
  /// Create and get the instance of the controller
  static ForgetController get instance {
    if (!Get.isRegistered<ForgetController>()) Get.put(ForgetController());
    return Get.find<ForgetController>();
  }

  final AuthService _authService = AuthService.instance;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController identifierInput = TextEditingController();
  final TextEditingController otpInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();
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
        "email": identifierInput.text
      };

      /// Initialize the Service and request server
      _authService.init(_client);

      /// Call api to login user
      ApiResponse response = await _authService.forget(body: body, client: _client);

      // log.w(response.data);
      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        return;
      }

      if(response.status == true){
        Toastr.show(message: "${response.message}");

        storage.write("email", identifierInput.text);

        /// Redirect user
        Get.offAllNamed(AuthRoutes.otp);
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

