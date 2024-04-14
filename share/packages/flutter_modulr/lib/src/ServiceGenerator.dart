import 'package:dcli/dcli.dart';
import 'package:modulr/Module/services/AppModuleService.dart' as modulrAppService;
import 'package:modulr/Module/services/MockModuleService.dart' as modulrMockService;
import 'package:modulr/Module/services/ModuleService.dart' as modulrService;

import 'utilities/Utils.dart';

String moduleName = "";
String modulePath = "";
String serviceName = "";
String servicePath = "";

Future<void> generate(List<String> args) async {
  /// Validate arguments
  if (!_validateArgs(args)) return;

  /// Assign variable values
  serviceName = args.first;

  /// Generate Controller
  await generateService();
}

/// Generate ModuleController
Future<void> generateService() async {
  /// Check and create directory
  Utils.makeDir(servicePath);

  /// Check if the service name already contains service suffix
  if (serviceName.contains('Service')) {
    serviceName = serviceName.replaceAll('Service', "");
  }

  String _serviceFile = modulrService.stub.replaceAll('{MODULE}', Utils.ucFirst(serviceName, preserveAfter: true));
  String _serviceMockFile = modulrMockService.stub.replaceAll('{MODULE}', Utils.ucFirst(serviceName, preserveAfter: true));
  String _serviceAppFile = modulrAppService.stub.replaceAll('{MODULE}', Utils.ucFirst(serviceName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$servicePath/${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart", _serviceFile);
  Utils.writeFile("$servicePath/Mock${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart", _serviceMockFile);
  Utils.writeFile("$servicePath/App${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart", _serviceAppFile);

  /// Show Success message
  print(green('"$servicePath/${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart" generated successfully.'));
  print(green('"$servicePath/Mock${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart" generated successfully.'));
  print(green('"$servicePath/App${Utils.ucFirst(serviceName, preserveAfter: true)}Service.dart" generated successfully.'));
}


bool _validateArgs(List<String> args) {
  /// Check if there are any args
  if (args.length <= 0) {
    print(red('Please provide service name and module name\nExample "flutter pub run modulr:service ServiceName --on=module_name"'));
    return false;
  }

  /// Get the module name
  moduleName = args.where((element) => element.contains('--on=')).isNotEmpty ? args.where((element) => element.contains('--on=')).first : "";

  /// Check if the module name is provided or not.
  if (moduleName == "") {
    print(red('Please provide module name to generate the service\nExample [--on=<module_name>]'));
    return false;
  }

  /// Assign module name
  moduleName = moduleName.replaceAll('--on=', '');

  /// Assign module path
  modulePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}";

  servicePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/services";

  /// Check if services directory already exists
  if (exists(servicePath)) {
    print(red('The services already exist in "$moduleName" Module.'));
    return false;
  }

  /// Check if the module exists or not
  if (!exists(modulePath)) {
    print(red('The module with name "$moduleName" does not exist.'));
    return false;
  }
  return true;
}
