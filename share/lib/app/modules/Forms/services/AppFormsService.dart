import '/app/helpers/Request.dart';
import '/app/models/ApiResponse.dart';
import '/app/shared/services/Services.dart';
import 'FormsService.dart';

class AppFormsService extends BaseService  implements FormsService {
  late Request _request;
  AppFormsService() {
    _request = new Request();
  }

  /// Start the server request
  @override
  void init(String client) => _request.start(client);

  /// Stop the server request
  @override
  void close(String client) => _request.close(client);


  @override
  Future<ApiResponse> index(client, key) async {
    return await _request.get('/forms/'+key, client: client, authenticate: true);
  }

  @override
  Future<ApiResponse> store(client, endpoint, body, {key}) async {
    if(endpoint == '/'){
      print(body);
      return await _request.post("/form-requests", body: {
        "form_id": key,
        "payload": body
      }, client: client, authenticate: true);
    }
    else if(endpoint.contains('{id}')){
      return await _request.post(endpoint.replaceAll('{id}', body['id'].toString()), body: body, client: client, authenticate: true);
    }
    else {
      return await _request.post(endpoint, body: body, client: client, authenticate: true);
    }
  }
}

