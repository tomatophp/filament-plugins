import 'package:dcli/dcli.dart';
import 'package:modulr/Module/controllers/ModuleSingleController.dart' as modulrController;

import 'utilities/Utils.dart';

String moduleName = "";
String modulePath = "";
String controllerName = "";
String controllerPath = "";

Future<void> generate(List<String> args) async {
  /// Validate arguments
  if (!_validateArgs(args)) return;

  /// Assign variable values
  controllerName = args.first;

  controllerPath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/controllers";

  /// Generate Controller
  await generateController();
}

/// Generate ModuleController
Future<void> generateController() async {
  /// Check and create directory
  Utils.makeDir(controllerPath);

  String _controllerFile = modulrController.stub.replaceAll('{CONTROLLER}', Utils.ucFirst(controllerName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$controllerPath/${Utils.ucFirst(controllerName, preserveAfter: true)}Controller.dart", _controllerFile);

  /// Show Success message
  print(green('"$controllerPath/${Utils.ucFirst(controllerName, preserveAfter: true)}Controller.dart" generated successfully.'));
}

bool _validateArgs(List<String> args) {
  /// Check if there are any args
  if (args.length <= 0) {
    print(red('Please provide controller name and module name\nExample "flutter pub run modulr:controller ControllerName --on=module_name"'));
    return false;
  }

  /// Get the module name
  moduleName = args.where((element) => element.contains('--on=')).isNotEmpty ? args.where((element) => element.contains('--on=')).first : "";

  /// Check if the module name is provided or not.
  if (moduleName == "") {
    print(red('Please provide module name to generate the controller\nExample [--on=<module_name>]'));
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
