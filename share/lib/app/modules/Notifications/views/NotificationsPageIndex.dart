import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/widgets/buttons/Button.dart';

import '/app/helpers/Global.dart';
import '/app/modules/Notifications/controllers/NotificationsIndexController.dart';
import '/app/shared/views/components/SideMenu.dart';
import '/app/shared/views/errors/NotConnectedErrorPage.dart';
import '/app/shared/views/layouts/MasterLayout.dart';
import '/app/shared/views/widgets/LoadingIconWidget.dart';
import '/config/theme/AppTheme.dart';

class NotificationsPageIndex extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetBuilder<NotificationsIndexController>(
      init: NotificationsIndexController(),
      builder: (NotificationsIndexController controller) {
        return !controller.isConnected
            ? NotConnectedErrorPage()
            : controller.isBusy
                ? LoadingIconWidget(message: "Please wait...")
                : MasterLayout(
                    title: "Notifications",
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
                          await controller.index();
                        },
                        child: Container(
                          padding:
                              EdgeInsets.symmetric(vertical: 5, horizontal: 5),
                          color: Colors.transparent,
                          width: double.infinity,
                          height: screen.height - 55,
                          child: !controller.data.isEmpty ||
                                  (controller.searchInput.text.length != 0)
                              ? Column(
                                  crossAxisAlignment:
                                      CrossAxisAlignment.stretch,
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  mainAxisSize: MainAxisSize.max,
                                  children: [
                                    Expanded(
                                      child: SizedBox(
                                        child: Container(
                                          child: NotificationListener(
                                            onNotification: (notification) {
                                              if (notification
                                                      is ScrollEndNotification &&
                                                  notification.metrics
                                                          .extentAfter ==
                                                      0) {
                                                // User has reached the end of the list
                                                // Load more data or trigger pagination in flutter
                                                controller.loadMore();
                                              }
                                              return false;
                                            },
                                            child: ListView.builder(
                                              itemCount: controller.data.length,
                                              itemBuilder:
                                                  (BuildContext context,
                                                      int index) {
                                                return Container(
                                                  height: 100,
                                                  margin:
                                                      EdgeInsets.only(top: 5),
                                                  decoration: BoxDecoration(
                                                      color: currentTheme() ==
                                                              ThemeMode.dark
                                                          ? AppTheme.darkTheme
                                                              .primaryColorDark
                                                          : Colors.white70,
                                                      border: Border.all(
                                                        color:
                                                            Colors.transparent,
                                                      ),
                                                      borderRadius:
                                                          BorderRadius.all(
                                                              Radius.circular(
                                                                  5))),
                                                  padding: EdgeInsets.all(10),
                                                  child: GestureDetector(
                                                      child: Container(
                                                        padding:
                                                            EdgeInsets.all(10),
                                                        child: Column(
                                                          crossAxisAlignment:
                                                              CrossAxisAlignment
                                                                  .start,
                                                          children: [
                                                            Text(
                                                                '${controller.data[index]['title']}',
                                                                style: TextStyle(
                                                                    fontSize:
                                                                        20,
                                                                    fontWeight:
                                                                        FontWeight
                                                                            .bold,
                                                                    color: currentTheme() ==
                                                                            ThemeMode
                                                                                .dark
                                                                        ? Colors
                                                                            .white
                                                                        : Colors
                                                                            .black)),
                                                            RichText(
                                                                overflow:
                                                                    TextOverflow
                                                                        .ellipsis,
                                                                text: TextSpan(
                                                                    style: TextStyle(
                                                                        color: currentTheme() == ThemeMode.dark
                                                                            ? Colors
                                                                                .white60
                                                                            : Colors
                                                                                .black54),
                                                                    text:
                                                                        '${controller.data[index]['description']}')),
                                                          ],
                                                        ),
                                                      ),
                                                      onTap: () {
                                                        controller.makeRead(
                                                            controller
                                                                .data[index]
                                                                    ['id']
                                                                .toString());
                                                      }),
                                                );
                                              },
                                            ),
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                )
                              : Container(
                                  child: Center(
                                    child: Column(
                                      mainAxisAlignment:
                                          MainAxisAlignment.center,
                                      children: [
                                        Icon(Icons.error,
                                            size: 50, color: Colors.red),
                                        SizedBox(height: 20),
                                        Text(
                                          "No data found",
                                          style: TextStyle(fontSize: 20),
                                        )
                                      ],
                                    ),
                                  ),
                                ),
                        ),
                      ),
                    ),
                    drawer: SideMenu(),
                    actions: [
                      !controller.data.isEmpty
                          ? IconButton(
                              onPressed: () {
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
                                              "Are you sure you want to clear all notifications?",
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
                                                key: Key('clear'),
                                                label: 'Clear',
                                                onTap: (e) {
                                                  Get.delete<
                                                      NotificationsIndexController>();
                                                  controller.clearAll();
                                                }),
                                          ],
                                        )
                                      ],
                                    ),
                                  ),
                                ));
                              },
                              icon: Icon(Icons.delete))
                          : SizedBox()
                    ],
                  );
      },
    );
  }
}
