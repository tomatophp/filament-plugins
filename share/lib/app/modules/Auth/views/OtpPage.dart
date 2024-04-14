import 'package:feather_icons/feather_icons.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '/app/modules/Auth/controllers/OtpController.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/shared/views/layouts/AuthLayout.dart';

class OtpPage extends StatelessWidget {
  final OtpController controller = Get.put(OtpController());

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
                      Text("Verify", style: TextStyl.title(context)!.copyWith(fontWeight: FontWeight.w700)),
                      const SizedBox(height: spacer1),
                      Text("Please input the code from your email", style: TextStyl.body(context)!.copyWith(fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Form(
                    key: controller.formKey,
                    autovalidateMode: AutovalidateMode.onUserInteraction,
                    child: Column(
                      children: [
                        FormInput.text(
                          controller: controller.otpInput,
                          placeholder: "OTP",
                          leading: Icon(FeatherIcons.code),
                          validator: (value) => Validator("OTP", value!)
                              .required()
                              .max(6)
                              .min(6)
                              .validate(),
                        ),
                        SizedBox(height: 25),
                        Button.block(
                          key: UniqueKey(),
                          label: "Verify",
                          onTap: (ButtonController btn) async {
                            btn.setBusy(true).setDisabled(true);
                            await controller.verifyOtp();
                            btn.setBusy(false).setDisabled(false);
                          },
                        ),
                        SizedBox(height: 16),
                        GestureDetector(
                          onTap: () {
                            controller.resend();
                          },
                          child: Text.rich(
                            TextSpan(
                              text: "Don't get the Code?",
                              style: TextStyl.button(context)?.copyWith(color: Theme.of(context).textTheme.bodyText1?.color),
                              children: [
                                TextSpan(
                                  text: " Resend",
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
