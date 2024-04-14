import '/app/shared/views/components/SideMenu.dart';
import 'package:flutter/material.dart';
import '/config/Config.dart';
import 'package:get/get.dart';

class MasterLayout extends StatelessWidget {
  final Widget body;
  final SideMenu? drawer;
  final Color? backgroundColor;
  final dynamic title;
  final List<Widget>? actions;

  MasterLayout({
    Key? key,
    required this.body,
    this.drawer,
    this.backgroundColor,
    this.title,
    this.actions,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Directionality(
      textDirection: Get.locale.toString() == 'ar_EG' ?
        TextDirection.rtl :
        TextDirection.ltr,
      child : SafeArea(
        child: Scaffold(
          backgroundColor: backgroundColor,
          appBar: AppBar(
            elevation: 0,
            title: title is String
                ? Text(
                    "${title != null ? title : Config.appName}",
                  )
                : title,
            actions: actions,
          ),
          drawer: drawer ?? null,
          body: body,
        ),
      )
    );
  }
}
