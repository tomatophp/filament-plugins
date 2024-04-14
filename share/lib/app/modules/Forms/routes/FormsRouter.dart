import 'package:get/get.dart';

import '../FormsModule.dart';

List<GetPage> formsRoutes = [
  GetPage(name: '/forms/:id', page: () => FormsPage()),
];

