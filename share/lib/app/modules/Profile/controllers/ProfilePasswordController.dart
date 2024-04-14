import 'package:flutter/material.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import 'package:get/get.dart';

import '/app/models/UserModel.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Auth/routes/AuthRoutes.dart';
import '/app/modules/Profile/services/ProfileService.dart';

class ProfilePasswordController extends AppController {
  /// Create and get the instance of the controller
  static ProfilePasswordController get instance {
    if (!Get.isRegistered<ProfilePasswordController>()) Get.put(ProfilePasswordController());
    return Get.find<ProfilePasswordController>();
  }

  /// Initialise [ProfileModule] service
  final ProfileService _profileService = ProfileService.instance;

  /// Observables
  var _exampleBool = false.obs;

  /// Getters
  bool get exampleBool => _exampleBool.value;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
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
    String _client = "passwordUpdate";

    try {
      Map<String, dynamic> body = UserModel(
        password: passwordInput.text?? null,
        password_confirmation: confirmPasswordInput.text?? null,
      ).toJson();

      /// Initialize the Service and request server
      _profileService.init(_client);

      ApiResponse response = await _profileService.password(_client, body);

      print(response.toJson());

      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        setBusy(false);
        return;
      }

      /// Close the Service and request server
      _profileService.close(_client);

      Toastr.show(message: "${response.message}");

      setBusy(false);
      /// Login the user after registration
      Get.delete<ProfilePasswordController>();
      formKey.currentState?.reset();

      auth.logoutSilently();
      Get.offAllNamed(AuthRoutes.login);
    } on Exception catch (e) {
      setBusy(false);
      Get.to(() => ErrorPage(message: "${e.toString()}"));
    }
  }


}
