import '/app/helpers/Request.dart';
import '/app/models/ApiResponse.dart';
import 'AuthService.dart';

class AppAuthService implements AuthService {
  late Request _request;
  AppAuthService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);

  @override
  Future<ApiResponse> login({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/login', client: client, body: body);
  }

  @override
  Future<ApiResponse> register({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/register', client: client, body: body);
  }

  @override
  Future<ApiResponse> user(String client) async {
    return await _request.get('/profile', client: client, authenticate: true);
  }

  @override
  Future<ApiResponse> google({required String client}) {
    // TODO: implement google
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> facebook({required String client}) {
    // TODO: implement facebook
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> github({required String client}) {
    // TODO: implement github
    throw UnimplementedError();
  }

  @override
  Future<ApiResponse> verifyOtp({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/otp-check', client: client, body: body);
  }

  @override
  Future<ApiResponse> verifyOtpAndActivate({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/otp', client: client, body: body);
  }

  @override
  Future<ApiResponse> forget({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/reset', client: client, body: body);
  }

  @override
  Future<ApiResponse> password({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/password', client: client, body: body);
  }

  @override
  Future<ApiResponse> resend({required String client, required Map<String, dynamic> body}) async {
    return await _request.post('/resend', client: client, body: body);
  }
}
