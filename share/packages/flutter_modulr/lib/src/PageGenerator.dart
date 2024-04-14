import 'package:dcli/dcli.dart';
import 'package:modulr/Module/views/ModuleSinglePage.dart' as modulrView;

import 'utilities/Utils.dart';

String moduleName = "";
String modulePath = "";
String viewName = "";
String viewPath = "";

Future<void> generate(List<String> args) async {
  /// Validate arguments
  if (!_validateArgs(args)) return;

  /// Assign variable values
  viewName = args.first;

  viewPath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/views";

  /// Generate Controller
  await generateController();
}

/// Generate ModuleController
Future<void> generateController() async {
  /// Check and create directory
  Utils.makeDir(viewPath);

  String _viewFile = modulrView.stub.replaceAll('{PAGE}', Utils.ucFirst(viewName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$viewPath/${Utils.ucFirst(viewName, preserveAfter: true)}Page.dart", _viewFile);

  /// Show Success message
  print(green('"$viewPath/${Utils.ucFirst(viewName, preserveAfter: true)}Page.dart" generated successfully.'));
}

bool _validateArgs(List<String> args) {
  /// Check if there are any args
  if (args.length <= 0) {
    print(red('Please provide view name and module name\nExample "flutter pub run modulr:page PageName --on=module_name"'));
    return false;
  }

  /// Get the module name
  moduleName = args.where((element) => element.contains('--on=')).isNotEmpty ? args.where((element) => element.contains('--on=')).first : "";

  /// Check if the module name is provided or not.
  if (moduleName == "") {
    print(red('Please provide module name to generate the page\nExample [--on=<module_name>]'));
    return false;
  }

  /// Assign module name
  moduleName = moduleName.replaceAll('--on=', '');

  /// Assign module path
  modulePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}";

  /// Check if the module exists or not
  if (!exists(modulePath)) {
    print(red('The module with name "$moduleName" does not exist.'));
    return false;
  }
  return true;
}
