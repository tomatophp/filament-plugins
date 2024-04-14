import 'package:dcli/dcli.dart';
import 'package:modulr/Module/Module.dart' as modulrModule;
import 'package:modulr/Module/controllers/ModuleController.dart' as modulrController;
import 'package:modulr/Module/routes/Router.dart' as modulrRouter;
import 'package:modulr/Module/routes/Routes.dart' as modulrRoutes;
import 'package:modulr/Module/services/AppModuleService.dart' as modulrAppService;
import 'package:modulr/Module/services/MockModuleService.dart' as modulrMockService;
import 'package:modulr/Module/services/ModuleService.dart' as modulrService;
import 'package:modulr/Module/views/ModulePage.dart' as modulrView;

import 'utilities/Utils.dart';

String moduleName = "";
String modulePath = "";
String controllerPath = "";
String servicePath = "";
String viewPath = "";
String routePath = "";

Future<void> generate(List<String> args) async {
  /// Assign variable values
  moduleName = args.first;
  modulePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}";
  controllerPath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/controllers";
  servicePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/services";
  viewPath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/views";
  routePath = "lib/app/modules/${Utils.ucFirst(moduleName, preserveAfter: true)}/routes";

  /// Generate Controller
  await generateController();

  /// Generate Route
  await generateModuleClass();

  /// Generate Services
  await generateService();

  /// Generate Views
  await generateView();

  /// Generate Route
  await generateRoute();
}

/// Generate ModuleController
Future<void> generateController() async {
  /// Check and create directory
  Utils.makeDir(controllerPath);

  String _controllerFile = modulrController.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));
  _controllerFile = _controllerFile.replaceAll('{CAMEL_MODULE}', moduleName.toLowerCase());

  /// Write File
  Utils.writeFile("$controllerPath/${Utils.ucFirst(moduleName, preserveAfter: true)}Controller.dart", _controllerFile);

  /// Show Success message
  print(green('"$controllerPath/${Utils.ucFirst(moduleName, preserveAfter: true)}Controller.dart" generated successfully!'));
}

/// Generate ModuleClass
Future<void> generateModuleClass() async {
  /// Check and create directory
  Utils.makeDir(modulePath);

  String _moduleFile = modulrModule.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$modulePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Module.dart", _moduleFile);

  /// Show Success message
  print(green('"$modulePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Module.dart" generated successfully!'));
}

/// Generate [ModuleService]
Future<void> generateService() async {
  /// Check and create directory
  Utils.makeDir(servicePath);

  String _serviceFile = modulrService.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));
  String _serviceMockFile = modulrMockService.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));
  String _serviceAppFile = modulrAppService.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$servicePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart", _serviceFile);
  Utils.writeFile("$servicePath/Mock${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart", _serviceMockFile);
  Utils.writeFile("$servicePath/App${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart", _serviceAppFile);

  /// Show Success message
  print(green('"$servicePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart" generated successfully.'));
  print(green('"$servicePath/Mock${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart" generated successfully.'));
  print(green('"$servicePath/App${Utils.ucFirst(moduleName, preserveAfter: true)}Service.dart" generated successfully.'));
}

/// Generate ModuleView
Future<void> generateView() async {
  /// Check and create directory
  Utils.makeDir(viewPath);

  /// Process View File
  String _viewFile = modulrView.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));

  /// Write File
  Utils.writeFile("$viewPath/${Utils.ucFirst(moduleName, preserveAfter: true)}Page.dart", _viewFile);

  /// Show Success message
  print(green('"$viewPath/${Utils.ucFirst(moduleName, preserveAfter: true)}Page.dart" generated successfully!'));
}

/// Generate ModuleRoute
Future<void> generateRoute() async {
  /// Check and create directory
  Utils.makeDir(routePath);

  /// Process Router File
  String _routeFile = modulrRouter.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));
  _routeFile = _routeFile.replaceAll('{MODULE_URL}', Utils.kebab(moduleName));
  _routeFile = _routeFile.replaceAll('{CAMEL_MODULE}', moduleName.toLowerCase());

  /// Process Routes File
  String _routesFile = modulrRoutes.stub.replaceAll('{MODULE}', Utils.ucFirst(moduleName, preserveAfter: true));
  _routesFile = _routesFile.replaceAll('{MODULE_URL}', Utils.kebab(moduleName));

  /// Write File
  Utils.writeFile("$routePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Router.dart", _routeFile);
  Utils.writeFile("$routePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Routes.dart", _routesFile);

  /// Show Success message
  print(green('"$routePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Router.dart" generated successfully!'));
  print(green('"$routePath/${Utils.ucFirst(moduleName, preserveAfter: true)}Routes.dart" generated successfully!'));
}
