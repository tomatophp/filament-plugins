import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Dashboard/routes/DashboardRoutes.dart';
import '/app/modules/Auth/services/AuthService.dart';

class LoginController extends AppController {
  static LoginController get instance {
    if (!Get.isRegistered<LoginController>()) Get.put(LoginController());
    return Get.find<LoginController>();
  }

  final AuthService _authService = AuthService.instance;

  /// Variables
  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController identifierInput = TextEditingController();
  final TextEditingController passwordInput = TextEditingController();



  Future<void> submit() async {
    String _client = "loginSubmit";
    if (!formKey.currentState!.validate()) {
      Toastr.show(message: "Please fill all the required fields!");
      return;
    }

    try {
      /// Prepare form data to be sent to server
      Map<String, dynamic> body = {
        "email": identifierInput.text,
        "password": passwordInput.text,
      };

      /// Initialize the Service and request server
      _authService.init(_client);

      /// Call api to login user
      ApiResponse response = await _authService.login(body: body, client: _client);

      // log.w(response.data);
      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        return;
      }

      if(response.status == true){
        await auth.setUserToken(response.data['token']);

        ApiResponse userResponse = await _authService.user(_client);

        await auth.setUserData(userResponse.data);

        Toastr.show(message: "${response.message}");

        /// Redirect user
        Get.offAllNamed(DashboardRoutes.dashboard);
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
