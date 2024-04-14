import 'package:get/get.dart';

import '/app/modules/Auth/views/LoginPage.dart';
import '/app/modules/Auth/views/OtpPage.dart';
import '/app/modules/Auth/views/PasswordPage.dart';
import '/app/modules/Auth/views/RegisterPage.dart';
import '/app/modules/Auth/views/ResetPage.dart';

List<GetPage> authRoutes = [
  GetPage(name: '/login', page: () => LoginPage()),
  GetPage(name: '/register', page: () => RegisterPage()),
  GetPage(name: '/reset', page: () => ResetPage()),
  GetPage(name: '/otp', page: () => OtpPage()),
  GetPage(name: '/password', page: () => PasswordPage()),
];
