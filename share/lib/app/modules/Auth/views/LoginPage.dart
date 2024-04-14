import 'package:feather_icons/feather_icons.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/shared/views/layouts/AuthLayout.dart';
import '/app/modules/Auth/controllers/LoginController.dart';

class LoginPage extends StatelessWidget {
  final LoginController controller = Get.put(LoginController());

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
                      Text("welcomeBack".tr, style: TextStyl.title(context)!.copyWith(fontWeight: FontWeight.w700)),
                      const SizedBox(height: spacer1),
                      Text("SignInToContinue".tr, style: TextStyl.body(context)!.copyWith(fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Form(
                    key: controller.formKey,
                    autovalidateMode: AutovalidateMode.onUserInteraction,
                    child: Column(
                      children: [
                        Align(
                          alignment: Alignment.topLeft,
                          child: Get.locale.toString() == 'en_US' ? Button.primary(
                            label: 'ع',
                            key: Key('lang'),
                            onTap: (ButtonController btn) {
                              Get.updateLocale(Locale('ar_EG'));
                            },
                          ): Button.primary(
                            label: 'En',
                            key: Key('lang'),
                            onTap: (ButtonController btn) {
                              Get.updateLocale(Locale('en_US'));
                            },
                          ),
                        ),
                        SizedBox(height: 25),
                        FormInput.text(
                          controller: controller.identifierInput,
                          placeholder: "email".tr,
                          leading: Icon(FeatherIcons.user),
                          validator: (value) => Validator("Identifier", value!).required().validate(),
                        ),
                        SizedBox(height: 25),
                        FormInput.password(
                          controller: controller.passwordInput,
                          placeholder: "password".tr,
                          leading: Icon(Icons.lock_outline),
                          validator: (value) => Validator("password", value!).required().validate(),
                          action: TextInputAction.done,
                        ),
                        SizedBox(height: 25),
                        Button.block(
                          key: UniqueKey(),
                          label: "login".tr,
                          onTap: (ButtonController btn) async {
                            btn.setBusy(true).setDisabled(true);
                            await controller.submit();
                            btn.setBusy(false).setDisabled(false);
                          },
                        ),
                        SizedBox(height: 16),
                        GestureDetector(
                          onTap: () => Get.offNamed("/register"),
                          child: Text.rich(
                            TextSpan(
                              text: "register".tr,
                              style: TextStyl.button(context)?.copyWith(color: Theme.of(context).textTheme.bodyText1?.color),
                              children: [
                                TextSpan(
                                  text: "join".tr,
                                  style: TextStyl.button(context)?.copyWith(color: Theme.of(context).primaryColor),
                                ),
                              ],
                            ),
                          ),
                        ),
                        SizedBox(height: 16),
                        GestureDetector(
                          onTap: () => Get.offNamed("/reset"),
                          child: Text.rich(
                            TextSpan(
                              text: "forgetPassword".tr,
                              style: TextStyl.button(context)?.copyWith(color: Theme.of(context).textTheme.bodyText1?.color),
                              children: [
                                TextSpan(
                                  text: "reset".tr,
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
