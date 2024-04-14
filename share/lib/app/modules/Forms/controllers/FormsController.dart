import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:ui_x/ui_x.dart';

import '/app/models/ApiResponse.dart';
import '/app/models/FormModel.dart';
import '/app/helpers/Global.dart';
import '/app/shared/controllers/AppController.dart';
import '/app/modules/Forms/services/FormsService.dart';

class FormsController extends AppController {
  /// Create and get the instance of the controller
  static FormsController get instance {
    if (!Get.isRegistered<FormsController>()) Get.put(FormsController());
    return Get.find<FormsController>();
  }

  /// Initialise [FormsModule] service
  final FormsService _formsService = FormsService.instance;

  /// Inputs
  GlobalKey<FormState> formKey = GlobalKey<FormState>(debugLabel: 'createCustomers');

  late RxString name = RxString("");
  late RxList<dynamic> fields = RxList([]);
  late FormModel form = FormModel();
  late RxMap<String, Rx> body = RxMap({});

  @override
  void onInit() {
    super.onInit();
    load(Get.parameters['id']!);
  }

  Future<void> load(key) async {
    setBusy(true);
    String _client = "fetchForm";

    _formsService.init(_client);

    ApiResponse response = await _formsService.index(_client, key);

    form = FormModel.fromJson(response.data);

    name.value = form.title!.en.toString();
    fields.value = form.fields!;
    Map<String, dynamic>? formValues = storage.read('form-values');

    if(formValues != null){
      for(var r=0; r<formValues.keys.length; r++){
        for(var i=0; i<fields.length; i++){
          if(formValues.keys.elementAt(r) == fields[i].name){
            if(fields[i].type == 'date'){
              body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
            }
            else if(fields[i].type == 'time'){
              body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
            }
            else if(fields[i].type == 'checkbox'){
              body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
            }
            else {
              body[fields[i].name] = Rx(TextEditingController());
              body[fields[i].name]?.value.text = formValues[formValues.keys.elementAt(r)];
            }
          }
          else if(body[fields[i].name] == null) {
            if(fields[i].type == 'date'){
              body[fields[i].name] = Rx("");
            }
            else if(fields[i].type == 'time'){
              body[fields[i].name] = Rx("");
            }
            else if(fields[i].type == 'checkbox'){
              body[fields[i].name] = Rx(false);
            }
            else {
              body[fields[i].name] = Rx(TextEditingController());
            }
          }
        }
      }
      body['id'] = Rx(formValues['id']);
    }
    else {
      for(var i=0; i<fields.length; i++){
        if(fields[i].type == 'date'){
          body[fields[i].name] = Rx("");
        }
        else if(fields[i].type == 'time'){
          body[fields[i].name] = Rx("");
        }
        else if(fields[i].type == 'checkbox'){
          body[fields[i].name] = Rx(false);
        }
        else {
          body[fields[i].name] = Rx(TextEditingController());
        }
      }
    }

    _formsService.close(_client);

    setBusy(false);
  }

  Widget getWidgets()
  {
    return new  Form(
        key: formKey,
        autovalidateMode: AutovalidateMode.onUserInteraction,
        child: Column(
          children: fields.map((item){
            var validation = (value, item) {
              Validator rules = Validator(capitalize((item.label!.en ?? item.name!).toString()), value!);
              if(item.isRequired){
                rules.required();
              }
              if(item.validation?.type == 'email'){
                rules.email();
              }
              if(item.type == 'number'){
                rules.number();
              }
              if(int.parse(item.validation?.max) > 0){
                rules.max(int.parse(item.validation?.max));
              }
              if(int.parse(item.validation?.min) > 0){
                rules.min(int.parse(item.validation?.min));
              }
              return rules.validate();
            };

            if(item.type == 'text'){
              return new Column(
                children: [
                  Obx(() => FormInput.text(
                    key: Key(item.id.toString()),
                    controller: body[item.name]?.value,
                    labelText: item.label!.en ?? null,
                    placeholder: capitalize((item.placeholder!.en ?? item.name!).toString()),
                    validator: (value) => validation(value, item),
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else if(item.type == 'number'){
              return new Column(
                children: [
                  Obx(() => FormInput.number(
                    key: Key(item.id.toString()),
                    controller: body[item.name]?.value,
                    labelText: item.label!.en ?? null,
                    placeholder: capitalize((item.placeholder!.en ?? item.name!).toString()),
                    validator: (value) => validation(value, item),
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else if(item.type == 'checkbox'){
              return new Column(
                children: [
                  Obx(() => Row(
                    children: [
                      Checkbox(
                        key: Key(item.id.toString()),
                        value: body[item.name]!.value ?? false,
                        onChanged: (value){
                          body[item.name]!.value = value;
                        },
                      ),
                      Text(capitalize(item.label?.en != null ? item.label?.en : item.name)),
                    ],
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else if(item.type == 'date' || item.type == 'datetime'){
              return new Column(
                children: [
                  Obx(() => FormInputDate(
                    label: capitalize(item.label!.en ?? item.name),
                    value: DateTime.tryParse(body[item.name]?.value) ?? null,
                    key: Key(item.id.toString()),
                    onChanged: (value) {
                      body[item.name]!.value = value.toString();
                    },
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else if(item.type == 'time'){
              return new Column(
                children: [
                  Obx(() => FormInputTime(
                    label: capitalize(item.label?.en != null ? item.label?.en : item.name),
                    value: body[item.name]!.value.toString().isNotEmpty ? body[item.name]!.value : null,
                    key: Key(item.id.toString()),
                    onChanged: (value) {
                      body[item.name]!.value = value;
                    },
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else if(item.type == 'textarea'){
              return new Column(
                children: [
                  Obx(() =>FormInput.multiline(
                    key: Key(item.id.toString()),
                    controller: body[item.name]?.value,
                    labelText: item.label!.en ?? null,
                    placeholder: capitalize((item.placeholder!.en ?? item.name!).toString()),
                    validator: (value) =>
                        Validator(capitalize((item.label!.en ?? item.name!).toString()), value!).required().max(255).validate(),
                  )),
                  SizedBox(height: 20),
                ],
              );
            }
            else {
              return new Text(item.label!.en ?? "");
            }
          }).toList()
        )
    );
  }

  Future<void> store(FormModel form) async {
    setBusy(true);
    Map<String, dynamic> collectBody = {};
    for(var i=0; i<body.keys.length; i++){
      if(body[body.keys.elementAt(i)]?.value is TextEditingController){
        collectBody[body.keys.elementAt(i)] = body[body.keys.elementAt(i)]?.value.text;
      }
      else if(body[body.keys.elementAt(i)]?.value is bool){
        collectBody[body.keys.elementAt(i)] = body[body.keys.elementAt(i)]?.value;
      }
      else if(body[body.keys.elementAt(i)]?.value is TimeOfDay){
        var context = Get.context;
        collectBody[body.keys.elementAt(i)] = body[body.keys.elementAt(i)]?.value.format(context).toString();
      }
      else {
        collectBody[body.keys.elementAt(i)] = body[body.keys.elementAt(i)]?.value;
      }
    }
    if (formKey.currentState!.validate()) {
      String _client = "storeForm";

      _formsService.init(_client);

      ApiResponse response = await _formsService.store(_client, form.endpoint ,collectBody, key: form.id);

      if (response.hasError() || response.hasValidationErrors()) {
        Toastr.show(message: "${response.message}");
        return;
      }
      Toastr.show(message: "${response.message}");

      _formsService.close(_client);

      formKey.currentState?.reset();

      Map<String, dynamic>? formValues =  null;
      if(storage.read('form-values') != null){
        formValues = response.data;
        storage.write('form-values', formValues);
      }

      if(formValues != null){
        for(var r=0; r<formValues.keys.length; r++){
          for(var i=0; i<fields.length; i++){
            if(formValues.keys.elementAt(r) == fields[i].name){
              if(fields[i].type == 'date'){
                body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
              }
              else if(fields[i].type == 'time'){
                body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
              }
              else if(fields[i].type == 'checkbox'){
                body[fields[i].name] = Rx(formValues[formValues.keys.elementAt(r)]);
              }
              else {
                body[fields[i].name] = Rx(TextEditingController());
                body[fields[i].name]?.value.text = formValues[formValues.keys.elementAt(r)];
              }
            }
            else if(body[fields[i].name] == null) {
              if(fields[i].type == 'date'){
                body[fields[i].name] = Rx("");
              }
              else if(fields[i].type == 'time'){
                body[fields[i].name] = Rx("");
              }
              else if(fields[i].type == 'checkbox'){
                body[fields[i].name] = Rx(false);
              }
              else {
                body[fields[i].name] = Rx(TextEditingController());
              }
            }
          }
        }
        body['id'] = Rx(formValues['id']);
      }
      else {
        for(var i=0; i<fields.length; i++){
          if(fields[i].type == 'date'){
            body[fields[i].name] = Rx("");
          }
          else if(fields[i].type == 'time'){
            body[fields[i].name] = Rx("");
          }
          else if(fields[i].type == 'checkbox'){
            body[fields[i].name] = Rx(false);
          }
          else {
            body[fields[i].name] = Rx(TextEditingController());
          }
        }
      }
      setBusy(false);


      String? redirect  = storage.read('form-redirect');
      if(redirect != null){
        Get.toNamed(redirect);
      }
    }
    else {
      setBusy(false);
    }
  }
}

