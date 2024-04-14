import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '../controllers/FormsController.dart';

class FormsPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<FormsController>(
      init: FormsController(),
      builder: (FormsController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: Obx(()=>Text(controller.name.value)),
                    body: ScrollConfiguration(
                        behavior: ScrollConfiguration.of(context).copyWith(
                          physics: const BouncingScrollPhysics(),
                          dragDevices: {
                            PointerDeviceKind.touch,
                            PointerDeviceKind.mouse,
                            PointerDeviceKind.trackpad
                          },
                        ),
                        child: RefreshIndicator(
                          displacement: 250,
                          backgroundColor: Colors.yellow,
                          color: Colors.red,
                          strokeWidth: 3,
                          triggerMode: RefreshIndicatorTriggerMode.onEdge,
                          onRefresh: () async {
                              await controller.load(Get.parameters['id']!);
                          },
                          child: SingleChildScrollView(
                            child: GestureDetector(
                            onTap: () => Keyboard.hide(context),
                            child: Container(
                              padding: EdgeInsets.symmetric(vertical: 30, horizontal: 30),
                              color: Colors.transparent,
                              width: double.infinity,
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.stretch,
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                mainAxisSize: MainAxisSize.max,
                                children: <Widget>[
                                  Obx(() => controller.getWidgets()),
                                  Button.block(
                                    key: UniqueKey(),
                                    label: "Save",
                                    onTap: (ButtonController btn) async {
                                      btn.setBusy(true).setDisabled(true);
                                      await controller.store(controller.form);
                                      btn.setBusy(false).setDisabled(false);
                                    },
                                  )
                                ],
                              ),
                            ),
                          ),
                      ),
                    ),
                    ),
                  );
      },
    );
  }
}

