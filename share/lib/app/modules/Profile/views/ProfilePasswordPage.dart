import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '/app/modules/Profile/controllers/ProfilePasswordController.dart';

class ProfilePasswordPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProfilePasswordController>(
      init: ProfilePasswordController(),
      builder: (ProfilePasswordController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Change Password",
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
                              FormInput.password(
                                controller: controller.passwordInput,
                                placeholder: "Password",
                                leading: Icon(Icons.lock_outline),
                                validator: (value) => Validator("Password", value!)
                                    .required()
                                    .validate(),
                              ),
                              SizedBox(height: 25),
                              FormInput.password(
                                controller: controller.confirmPasswordInput,
                                placeholder: "Password Confirmation",
                                leading: Icon(Icons.lock_outline),
                                validator: (value) => Validator("Password Confirmation", value!)
                                    .required()
                                    .validate(),
                              ),
                              SizedBox(height: 25),
                              Button.block(
                                key: UniqueKey(),
                                label: "Update",
                                onTap: (ButtonController btn) {
                                  btn.setDisabled(true);
                                  controller.submit();
                                  btn.setDisabled(false);
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
