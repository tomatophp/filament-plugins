import '../../../models/ApiResponse.dart';
import '../../../shared/services/Services.dart';
import 'LocationService.dart';
 
class MockLocationService extends BaseService implements LocationService {
  @override
  Future<ApiResponse> doSomething() async {
    throw UnimplementedError();
  }
}

