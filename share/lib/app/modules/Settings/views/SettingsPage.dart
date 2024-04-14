import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/helpers/Global.dart';
import '/app/modules/Modules.dart';
import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';

class SettingsPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<SettingsController>(
      init: SettingsController(),
      builder: (SettingsController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Settings",
                    body: SafeArea(
                      child: Padding(
                        padding: EdgeInsets.all(12.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              "Theme",
                              style: TextStyl.bodySm(context),
                            ),
                            Row(
                              children: [
                                Expanded(
                                  child: InkWell(
                                    splashFactory: NoSplash.splashFactory,
                                    onTap: () => controller.changeTheme(
                                        context, "system"),
                                    child: Container(
                                      padding: const EdgeInsets.symmetric(
                                          vertical: 16),
                                      decoration: BoxDecoration(
                                        color: Theme.of(context)
                                            .colorScheme
                                            .background,
                                        borderRadius: BorderRadius.circular(8),
                                        border: Border.all(
                                            color: controller.selectedTheme ==
                                                    'system'
                                                ? kcPrimary
                                                : Colors.transparent,
                                            width: 2),
                                      ),
                                      child: Column(
                                        children: [
                                          Icon(Icons.brightness_4_rounded,
                                              size: 32),
                                          Text(
                                            'System',
                                            style: TextStyl.bodySm(context),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                ),
                                SizedBox(width: 8),
                                Expanded(
                                  child: InkWell(
                                    splashFactory: NoSplash.splashFactory,
                                    onTap: () => controller.changeTheme(
                                        context, "light"),
                                    child: Container(
                                      padding: const EdgeInsets.symmetric(
                                          vertical: 16),
                                      decoration: BoxDecoration(
                                        color: Theme.of(context)
                                            .colorScheme
                                            .background,
                                        borderRadius: BorderRadius.circular(8),
                                        border: Border.all(
                                            color: controller.selectedTheme ==
                                                    'light'
                                                ? kcPrimary
                                                : Colors.transparent,
                                            width: 2),
                                      ),
                                      child: Column(
                                        children: [
                                          Icon(Icons.brightness_5_rounded,
                                              size: 32),
                                          Text(
                                            'Light',
                                            style: TextStyl.bodySm(context),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                ),
                                SizedBox(width: 8),
                                Expanded(
                                  child: InkWell(
                                    splashFactory: NoSplash.splashFactory,
                                    onTap: () =>
                                        controller.changeTheme(context, "dark"),
                                    child: Container(
                                      padding: const EdgeInsets.symmetric(
                                          vertical: 16),
                                      decoration: BoxDecoration(
                                        color: Theme.of(context)
                                            .colorScheme
                                            .background,
                                        borderRadius: BorderRadius.circular(8),
                                        border: Border.all(
                                            color: controller.selectedTheme ==
                                                    'dark'
                                                ? kcPrimary
                                                : Colors.transparent,
                                            width: 2),
                                      ),
                                      child: Column(
                                        children: [
                                          Icon(Icons.brightness_2_rounded,
                                              size: 32),
                                          Text(
                                            'Dark',
                                            style: TextStyl.bodySm(context),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                )
                              ],
                            ),
                            const SizedBox(height: 16),
                            Text(
                              "Account",
                              style: TextStyl.bodySm(context),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: () {
                                Get.toNamed(ProfileRoutes.profile);
                              },
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Update Profile",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: () {
                                Get.toNamed(ProfileRoutes.password);
                              },
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Change Password",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: () {
                                Get.toNamed(ProfileRoutes.lang);
                              },
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Change Language",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: () {
                                Get.delete<NotificationsIndexController>();
                                Get.toNamed(NotificationsRoutes.index);
                              },
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Notifications",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: () {
                                Get.dialog(Dialog(
                                  child: Container(
                                    padding: EdgeInsets.all(20),
                                    child: Column(
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Container(
                                          padding: EdgeInsets.all(30),
                                          child: Text(
                                              "Are you sure you want to close your account?",
                                              style: TextStyle(
                                                  fontSize: 18,
                                                  fontWeight: FontWeight.bold)),
                                        ),
                                        ButtonBar(
                                          children: [
                                            Button(
                                                key: Key('cancel'),
                                                label: 'Cancel',
                                                onTap: (e) {
                                                  Get.back();
                                                }),
                                            Button(
                                                key: Key('close'),
                                                label: 'Close',
                                                onTap: (e) async {
                                                  await controller
                                                      .closeAccount();
                                                }),
                                          ],
                                        )
                                      ],
                                    ),
                                  ),
                                ));
                              },
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Close Account",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            ),
                            const SizedBox(height: 4),
                            InkWell(
                              onTap: auth.logout,
                              child: Container(
                                width: double.maxFinite,
                                decoration: BoxDecoration(
                                  color:
                                      Theme.of(context).colorScheme.background,
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                padding: EdgeInsets.symmetric(
                                    vertical: 12, horizontal: 16),
                                child: Text(
                                  "Logout",
                                  style: TextStyl.button(context),
                                ),
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                  );
      },
    );
  }
}
