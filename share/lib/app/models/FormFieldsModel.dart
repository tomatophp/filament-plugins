class FormFieldsModel {
  int? id;
  int? formId;
  String? type;
  Label? label;
  Label? placeholder;
  Label? hint;
  String? name;
  String? group;
  String? default_;
  int? order;
  bool? isRequired;
  bool? isMulti;
  Label? requiredMessage;
  bool? isReactive;
  String? reactiveField;
  String? reactiveWhere;
  bool? isFromTable;
  String? tableName;
  bool? hasOptions;
  String? options;
  bool? hasValidation;
  Validation? validation;
  String? meta;
  String? createdAt;
  String? updatedAt;

  FormFieldsModel({this.id, this.formId, this.type, this.label, this.placeholder, this.hint, this.name, this.group, this.default_, this.order, this.isRequired, this.isMulti, this.requiredMessage, this.isReactive, this.reactiveField, this.reactiveWhere, this.isFromTable, this.tableName, this.hasOptions, this.options, this.hasValidation, this.validation, this.meta, this.createdAt, this.updatedAt});

  FormFieldsModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    formId = json['form_id'];
    type = json['type'];
    label = json['label'] != null ? new Label.fromJson(json['label']) : null;
    placeholder = json['placeholder'] != null ? new Label.fromJson(json['placeholder']) : null;
    hint = json['hint'] != null ? new Label.fromJson(json['hint']) : null;
    name = json['name'];
    group = json['group'];
    default_ = json['default'];
    order = json['order'];
    isRequired = json['is_required'];
    isMulti = json['is_multi'];
    requiredMessage = json['required_message'] != null ? new Label.fromJson(json['required_message']) : null;
    isReactive = json['is_reactive'];
    reactiveField = json['reactive_field'];
    reactiveWhere = json['reactive_where'];
    isFromTable = json['is_from_table'];
    tableName = json['table_name'];
    hasOptions = json['has_options'];
    options = json['options'];
    hasValidation = json['has_validation'];
    validation = json['validation'] != null ? new Validation.fromJson(json['validation']) : null;
    meta = json['meta'];
    createdAt = json['created_at'];
    updatedAt = json['updated_at'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['form_id'] = this.formId;
    data['type'] = this.type;
    if (this.label != null) {
      data['label'] = this.label!.toJson();
    }
    if (this.placeholder != null) {
      data['placeholder'] = this.placeholder!.toJson();
    }
    if (this.hint != null) {
      data['hint'] = this.hint!.toJson();
    }
    data['name'] = this.name;
    data['group'] = this.group;
    data['default'] = this.default_;
    data['order'] = this.order;
    data['is_required'] = this.isRequired;
    data['is_multi'] = this.isMulti;
    if (this.requiredMessage != null) {
      data['required_message'] = this.requiredMessage!.toJson();
    }
    data['is_reactive'] = this.isReactive;
    data['reactive_field'] = this.reactiveField;
    data['reactive_where'] = this.reactiveWhere;
    data['is_from_table'] = this.isFromTable;
    data['table_name'] = this.tableName;
    data['has_options'] = this.hasOptions;
    data['options'] = this.options;
    data['has_validation'] = this.hasValidation;
    if (this.validation != null) {
      data['validation'] = this.validation!.toJson();
    }
    data['meta'] = this.meta;
    data['created_at'] = this.createdAt;
    data['updated_at'] = this.updatedAt;
    return data;
  }
}

class Label {
  String? ar;
  String? en;

  Label({
    this.ar,
    this.en
  });

  Label.fromJson(Map<String, dynamic> json) {
    ar = json['ar'];
    en = json['en'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['en'] = this.en;
    data['ar'] = this.ar;
    return data;
  }
}

class Validation {
    String? max;
    String? min;
    String? type;

    Validation({this.max, this.min, this.type});

    Validation.fromJson(Map<String, dynamic> json) {
      max = json['max'];
      min = json['min'];
      type = json['type'];
    }

    Map<String, dynamic> toJson() {
      final Map<String, dynamic> data = new Map<String, dynamic>();
      data['max'] = this.max;
      data['min'] = this.min;
      data['type'] = this.type;
      return data;
    }
}
