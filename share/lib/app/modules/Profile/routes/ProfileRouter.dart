import 'package:get/get.dart';

import '/app/modules/Profile/views/ProfileLanguagePage.dart';
import '/app/modules/Profile/views/ProfilePage.dart';
import '/app/modules/Profile/views/ProfilePasswordPage.dart';

List<GetPage> profileRoutes = [
  GetPage(name: '/profile', page: () => ProfilePage()),
  GetPage(name: '/profile/password', page: () => ProfilePasswordPage()),
  GetPage(name: '/profile/lang', page: () => ProfileLanguagePage()),
];

