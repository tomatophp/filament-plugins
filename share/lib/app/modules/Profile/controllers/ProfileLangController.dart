import 'package:flutter/material.dart';
import 'package:ui_x/helpers/Toastr.dart';


import 'package:get/get.dart';

import '/app/shared/controllers/AppController.dart';
import '/app/modules/Profile/services/ProfileService.dart';

class ProfileLangController extends AppController {
  /// Create and get the instance of the controller
  static ProfileLangController get instance {
    if (!Get.isRegistered<ProfileLangController>()) Get.put(ProfileLangController());
    return Get.find<ProfileLangController>();
  }

  /// Initialise [ProfileModule] service
  final ProfileService _profileService = ProfileService.instance;

  /// Observables
  var _exampleBool = false.obs;

  /// Getters
  bool get exampleBool => _exampleBool.value;

  final GlobalKey<FormState> formKey = GlobalKey<FormState>();
  final RxString selectedLanguage = (Get.locale.toString()).obs;

  @override
  void onInit() {
    super.onInit();
    selectedLanguage.value = Get.locale.toString();
  }

  Future<void> changeLanguage() async {
    Locale locale = new Locale(selectedLanguage.value);
    Get.updateLocale(locale);

    Toastr.show(message: "Your Language Has Been Updated");
  }
}
