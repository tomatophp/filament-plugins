import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'FormsService.dart';

class MockFormsService extends BaseService implements FormsService {
  /// Start the server request
  @override
  void init(String client) => null;

  /// Stop the server request
  @override
  void close(String client) => null;

  @override
  Future<ApiResponse> index(client, key) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> store(client, endpoint, body, {key}) async {
    throw UnimplementedError();
  }
}

