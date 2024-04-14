import 'dart:math';

import 'package:flutter/material.dart';
import 'package:ui_x/helpers/Toastr.dart';

import '/app/helpers/Global.dart';
import '/app/models/ApiResponse.dart';
import 'package:get/get.dart';

import '/app/models/UserModel.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/shared/views/errors/ErrorPage.dart';
import '/app/modules/Profile/routes/ProfileRoutes.dart';
import '/app/modules/Profile/services/ProfileService.dart';

class ProfileController extends AppController {
  /// Create and get the instance of the controller
  static ProfileController get instance {
    if (!Get.isRegistered<ProfileController>()) Get.put(ProfileController());
    return Get.find<ProfileController>();
  }

  /// Initialise [ProfileModule] service
  final ProfileService _profileService = ProfileService.instance;

  /// Observables
  var _exampleBool = false.obs;

  /// Getters
  bool get exampleBool => _exampleBool.value;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final TextEditingController nameInput = TextEditingController();
  final TextEditingController emailInput = TextEditingController();
  final TextEditingController phoneInput = TextEditingController();

  @override
  void onInit() {
    super.onInit();

    /// Do something here
    getData();
  }

  Future<void> getData() async {
    setBusy(true);
    var _client = "viewProfile";
    _profileService.init(_client);
    ApiResponse response = await _profileService.getData(_client);
    UserModel user = UserModel.fromJson(response.data);
    _profileService.close(_client);

    nameInput.text = user.name!;
    emailInput.text = user.email!;
    phoneInput.text = user.phone!;

    setBusy(false);
  }

  Future<void> submit() async {
    setBusy(true);
    if (!formKey.currentState!.validate()) {
      return;
    }
    String _client = "updateProfile";

    try {
      Map<String, dynamic> body = UserModel(
        name: nameInput.text?? null,
        email: emailInput.text?? null,
        phone: phoneInput.text?? null,
      ).toJson();

      /// Initialize the Service and request server
      _profileService.init(_client);

      ApiResponse response = await _profileService.update(_client, body);

      print(response.toJson());

      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.error(message: "${response.message}");
        setBusy(false);
        return;
      }

      /// Close the Service and request server
      _profileService.close(_client);

      Toastr.show(message: "${response.message}");

      await auth.setUserData(response.data);

      setBusy(false);
    } on Exception catch (e) {
      setBusy(false);
      Get.to(() => ErrorPage(message: "${e.toString()}"));
    }
  }


}
