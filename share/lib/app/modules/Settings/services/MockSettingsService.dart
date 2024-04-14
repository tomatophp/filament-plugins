import '/app/models/ApiResponse.dart';
import 'SettingsService.dart';

class MockSettingsService implements SettingsService {

  @override
  void init(client) async {
    throw UnimplementedError();
  }

  @override
  void close(client) async {
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> closeAccount(client) async {
    throw UnimplementedError();
  }

}
