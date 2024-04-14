import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '/app/modules/Profile/controllers/ProfileLangController.dart';

class ProfileLanguagePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProfileLangController>(
      init: ProfileLangController(),
      builder: (ProfileLangController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Change Language",
                    body: SafeArea(
                      child: Container(
                        padding: EdgeInsets.symmetric(vertical: 30, horizontal: 30),
                        color: Colors.transparent,
                        width: double.infinity,
                        child: Form(
                          key: controller.formKey,
                          autovalidateMode: AutovalidateMode.onUserInteraction,
                          child: Column(
                            children: [
                              Obx(() => FormSelect(
                                key: Key("lang"),
                                value: controller.selectedLanguage.value,
                                options: [
                                  DropdownMenuItem(
                                    child: Text('Arabic'),
                                    value: 'ar_EG',
                                  ),
                                  DropdownMenuItem(
                                    child: Text('English'),
                                    value: 'en_US',
                                  ),
                                ],
                                onChanged: (value) {
                                  controller.selectedLanguage(value) ;
                                },
                              )),
                              SizedBox(height: 25),
                              Button.block(
                                key: UniqueKey(),
                                label: 'hello'.tr,
                                onTap: (ButtonController btn) {
                                  btn.setBusy(true).setDisabled(true);
                                  controller.changeLanguage();
                                  btn.setBusy(false).setDisabled(false);
                                },
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                  );
      },
    );
  }
}
