import '/app/models/ApiResponse.dart';
import 'ProfileService.dart';

class MockProfileService implements ProfileService {
  /// Start the server request
  @override
  void init(String client) => null;

  /// Stop the server request
  @override
  void close(String client) => null;

  @override
  Future<ApiResponse> getData(client) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> update(client, body) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> password(client, body) async {
    throw UnimplementedError();
  }
}

