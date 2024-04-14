import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '/app/modules/Profile/controllers/ProfileController.dart';

class ProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<ProfileController>(
      init: ProfileController(),
      builder: (ProfileController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Update Profile",
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
                              FormInput.text(
                                controller: controller.nameInput,
                                placeholder: "Name",
                                leading: Icon(Icons.person_outline),
                                validator: (value) =>
                                    Validator("Name", value!).required().validate(),
                              ),
                              SizedBox(height: 25),
                              FormInput.text(
                                controller: controller.phoneInput,
                                placeholder: "Phone",
                                leading: Icon(Icons.phone),
                                validator: (value) => Validator("phone", value!)
                                    .required()
                                    .validate(),
                              ),
                              SizedBox(height: 25),
                              FormInput.email(
                                controller: controller.emailInput,
                                placeholder: "Email",
                                leading: Icon(Icons.email_outlined),
                                validator: (value) => Validator("Email", value!)
                                    .email()
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
