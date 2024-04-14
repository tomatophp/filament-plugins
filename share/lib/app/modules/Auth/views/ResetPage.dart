import 'package:feather_icons/feather_icons.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/shared/views/layouts/AuthLayout.dart';
import '/app/modules/Auth/controllers/ForgetController.dart';

class ResetPage extends StatelessWidget {
  final ForgetController controller = Get.put(ForgetController());

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
                        FormInput.text(
                          controller: controller.identifierInput,
                          placeholder: "Email",
                          leading: Icon(FeatherIcons.user),
                          validator: (value) => Validator("Email", value!).email().required().validate(),
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
                        SizedBox(height: 16),
                        GestureDetector(
                          onTap: () => Get.offNamed("/register"),
                          child: Text.rich(
                            TextSpan(
                              text: "Don't have an account?",
                              style: TextStyl.button(context)?.copyWith(color: Theme.of(context).textTheme.bodyText1?.color),
                              children: [
                                TextSpan(
                                  text: " Join Now",
                                  style: TextStyl.button(context)?.copyWith(color: Theme.of(context).primaryColor),
                                ),
                              ],
                            ),
                          ),
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
