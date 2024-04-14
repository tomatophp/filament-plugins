const String stub = """
import 'package:get/get.dart';

import '../{MODULE}Module.dart';

List<GetPage> {CAMEL_MODULE}Routes = [
  GetPage(name: '/{MODULE_URL}', page: () => {MODULE}Page()),
];
""";
