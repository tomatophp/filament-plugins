class UserNotificationsModel {
    int? id;
    String? created_by;
    String? model_type;
    String? model_id;
    String? template_id;
    String? title;
    String? url;
    String? icon;
    String? description;
    String? type;
    String? privacy;
    String? data;

  UserNotificationsModel({
    this.id,
    this.created_by,
    this.model_type,
    this.model_id,
    this.template_id,
    this.title,
    this.url,
    this.icon,
    this.description,
    this.type,
    this.privacy,
    this.data,
  });

  UserNotificationsModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    created_by = json['created_by'];
    model_type = json['model_type'];
    model_id = json['model_id'];
    template_id = json['template_id'];
    title = json['title'];
    url = json['url'];
    icon = json['icon'];
    description = json['description'];
    type = json['type'];
    privacy = json['privacy'];
    data = json['data'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();

    data['id'] = this.id;
    data['created_by'] = this.created_by;
    data['model_type'] = this.model_type;
    data['model_id'] = this.model_id;
    data['template_id'] = this.template_id;
    data['title'] = this.title;
    data['url'] = this.url;
    data['icon'] = this.icon;
    data['description'] = this.description;
    data['type'] = this.type;
    data['privacy'] = this.privacy;
    data['data'] = this.data;

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

