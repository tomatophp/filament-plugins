const String stub = """
import '../../../models/ApiResponse.dart';
import '../../../shared/services/Services.dart';
import '{MODULE}Service.dart';
 
class Mock{MODULE}Service extends BaseService implements {MODULE}Service {
  @override
  Future<ApiResponse> doSomething() async {
    throw UnimplementedError();
  }
}
""";
