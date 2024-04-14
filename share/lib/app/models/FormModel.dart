import '/app/models/FormFieldsModel.dart';

class FormModel {
  int? id;
  String? type;
  Name? name;
  String? key;
  String? endpoint;
  String? method;
  Name? title;
  Name? description;
  bool? isActive;
  String? createdAt;
  String? updatedAt;
  List<FormFieldsModel>? fields;

  FormModel({this.id, this.type, this.name, this.key, this.endpoint, this.method, this.title, this.description, this.isActive, this.createdAt, this.updatedAt, this.fields});

  FormModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    type = json['type'];
    name = json['name'] != null ? new Name.fromJson(json['name']) : null;
    key = json['key'];
    endpoint = json['endpoint'];
    method = json['method'];
    title = json['title'] != null ? new Name.fromJson(json['title']) : null;
    description = json['description'] != null ? new Name.fromJson(json['description']) : null;
    isActive = json['is_active'];
    createdAt = json['created_at'];
    updatedAt = json['updated_at'];
    if (json['fields'] != null) {
      fields = <FormFieldsModel>[];
      json['fields'].forEach((v) { fields!.add(new FormFieldsModel.fromJson(v)); });
    }
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['type'] = this.type;
    if (this.name != null) {
      data['name'] = this.name!.toJson();
    }
    data['key'] = this.key;
    data['endpoint'] = this.endpoint;
    data['method'] = this.method;
    if (this.title != null) {
      data['title'] = this.title!.toJson();
    }
    if (this.description != null) {
      data['description'] = this.description!.toJson();
    }
    data['is_active'] = this.isActive;
    data['created_at'] = this.createdAt;
    data['updated_at'] = this.updatedAt;
    if (this.fields != null) {
      data['fields'] = this.fields!.map((v) => v.toJson()).toList();
    }
    return data;
  }
}

class Name {
  String? ar;
  String? en;

  Name({this.ar, this.en});

  Name.fromJson(Map<String, dynamic> json) {
    ar = json['ar'];
    en = json['en'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['ar'] = this.ar;
    data['en'] = this.en;
    return data;
  }
}
