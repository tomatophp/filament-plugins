import '/app/models/UserNotificationsModel.dart';

class UserNotificationsSingleModel {
  bool? success;
  String? message;
  String? errors;
  UserNotificationsModel? data;

  UserNotificationsSingleModel(
      {this.success, this.message, this.errors, this.data});

  UserNotificationsSingleModel.fromJson(Map<String, dynamic> json) {
    success = json['success'];
    message = json['message'];
    errors = json['errors'];
    data = (json['data'] != null
        ? new UserNotificationsModel.fromJson(json['data'])
        : []) as UserNotificationsModel?;
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['success'] = this.success;
    data['message'] = this.message;
    data['errors'] = this.errors;
    if (this.data != null) {
      data['data'] = this.data;
    }
    return data;
  }
}
