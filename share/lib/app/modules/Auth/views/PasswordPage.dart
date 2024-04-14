import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '/app/modules/Auth/controllers/ResetPasswordController.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/shared/views/layouts/AuthLayout.dart';

class PasswordPage extends StatelessWidget {
  final ResetPasswordController controller = Get.put(ResetPasswordController());

  @override
  Widget build(BuildContext context) {
    var screen = Get.size;

    return AuthLayout(
      body: SafeArea(
        child: SingleChildScrollView(
          child: GestureDetector(
            onTap: () => Keyboard.hide(context),
            child: Container(
              padding: EdgeInsets.symmetric(vertical: 30, horizontal: 30),
              color: Colors.transparent,
              width: double.infinity,
              height: screen.height - 55,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                mainAxisSize: MainAxisSize.max,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      SizedBox(height: 60),
                      Container(
                        child: Image.asset(
                          image('logo.png'),
                          width: 75,
                        ),
                      ),
                      const SizedBox(height: spacer),
                      Text("Reset Password,", style: TextStyl.title(context)!.copyWith(fontWeight: FontWeight.w700)),
                      const SizedBox(height: spacer1),
                      Text("Please Enter your email to reset password", style: TextStyl.body(context)!.copyWith(fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Form(
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
                          label: "Reset Password",
                          onTap: (ButtonController btn) async {
                            btn.setBusy(true).setDisabled(true);
                            await controller.reset();
                            btn.setBusy(false).setDisabled(false);
                          },
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
