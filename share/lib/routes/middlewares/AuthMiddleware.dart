import 'package:flutter/material.dart';
import 'package:get/get.dart';

class AuthMiddleware extends GetMiddleware {
  @override
  int? get priority => 9;

  @override
  RouteSettings? redirect(String? route) {
    // if (auth.check()) {
    //   return RouteSettings(name: '/login');
    // }
    return null;
  }
}
