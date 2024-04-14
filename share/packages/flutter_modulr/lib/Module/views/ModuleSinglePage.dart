const String stub = """
import 'package:flutter/material.dart';

import '../../../shared/views/layouts/MasterLayout.dart';

class {PAGE}Page extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MasterLayout(
      title: "{PAGE}",
      body: SafeArea(
        child: Container(
          child: Text("Build awesome page here."),
        ),
      ),
    );
  }
}
""";
