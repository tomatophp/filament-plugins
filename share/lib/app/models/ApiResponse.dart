import 'dart:convert';

ApiResponse apiResponseModelFromJson(String str) => ApiResponse.fromJson(json.decode(str));

String apiResponseModelToJson(ApiResponse data) => json.encode(data.toJson());

class ApiResponse {
  ApiResponse({
    this.status,
    this.message,
    this.validationError,
    this.data,
  });

  bool? status;
  String? message;
  String? validationError;
  dynamic data;

  factory ApiResponse.fromJson(Map<String, dynamic> json) => ApiResponse(
        status: json["exception"] == null ? json["status"] : false,
        message: json["message"] == null ? null : json["message"],
        validationError: json["errors"] == null ? null : json['errors'][json["errors"].keys.first].first,
        data: json["data"] == null ? null : json["data"],
      );

  Map<String, dynamic> toJson() => {
        "success": status,
        "message": message == null ? null : message,
        "errors": validationError == null ? null : validationError,
        "data": data == null ? null : data,
      };

  ApiResponse copyWith({bool? status, String? message, dynamic validationError, dynamic data}) {
    return ApiResponse(
      status: status ?? this.status,
      message: message ?? this.message,
      validationError: validationError ?? this.validationError,
      data: data ?? this.data,
    );
  }

  bool isOk() => status == true;

  bool isSuccessful() => status == true;

  bool hasError() => status == false;

  bool hasValidationErrors() => validationError != null;

  bool hasData() {
    if (data == null) {
      return false;
    } else if (data is List) {
      if (data.length > 0) return true;
    } else if (data is Map) {
      if (data.length > 0) return true;
    }

    return false;
  }
}
