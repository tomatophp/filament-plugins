import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Auth//services/AuthService.dart';

class OtpController extends AppController {
  /// Create and get the instance of the controller
  static OtpController get instance {
    if (!Get.isRegistered<OtpController>()) Get.put(OtpController());
    return Get.find<OtpController>();
  }

  final AuthService _authService = AuthService.instance;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController otpInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();
  }

  Future<void> resend() async {
    String _client = "resendSubmit";

    try {
      /// Prepare form data to be sent to server
      Map<String, dynamic> body = {
        "email": storage.read("email"),
      };

      /// Initialize the Service and request server
      _authService.init(_client);

      /// Call api to login user
      ApiResponse response = await _authService.resend(body: body, client: _client);

      // log.w(response.data);
      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        return;
      }

      if(response.status == true){
        Toastr.show(message: "${response.message}");
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

  Future<void> verifyOtp() async {
    String _client = "otpSubmit";
    if (!formKey.currentState!.validate()) {
      Toastr.show(message: "Please fill all the required fields!");
      return;
    }

    try {
      /// Prepare form data to be sent to server
      Map<String, dynamic> body = {
        "otp_code": otpInput.text,
        "email": storage.read("email"),
      };

      /// Initialize the Service and request server
      _authService.init(_client);


      ApiResponse response =  storage.hasData("activated") ?
      await _authService.verifyOtpAndActivate(body: body, client: _client):
      await _authService.verifyOtp(body: body, client: _client);

      // log.w(response.data);
      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        return;
      }

      if(response.status == true){
        Toastr.show(message: "${response.message}");

        storage.write("otp", true);
        storage.write("otp_code", otpInput.text);

        if(storage.hasData("activated")){
          /// Redirect user
          Get.offAllNamed(AuthRoutes.login);
        }
        else {
          /// Redirect user
          Get.offAllNamed(AuthRoutes.password);
        }
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

